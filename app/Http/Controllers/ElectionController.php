<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ElectionController extends Controller
{
  public function __construct () {
    // ...
  }


  public function getAllElections ()
  {
    $elections = getElections();

    foreach ($elections as $eIx => $election) {
      $elections[$eIx] = getElectionDataObj($election->slug);
    }

    $electionsObj['elections'] = $elections;

    return deliverJson($electionsObj);
  }


  public function getElection ($electionSlug)
  {
    $electionDataObj = getElectionDataObj($electionSlug);

    return deliverJson($electionDataObj);
  }


  public function getParties ($electionSlug)
  {
    $parties = getParties($electionSlug);
    $partiesObj['parties'] = $parties;

    return deliverJson($partiesObj);
  }


  public function getStates ($electionSlug)
  {
    $states = getStates($electionSlug);
    $statesObj['states'] = $states;

    return deliverJson($statesObj);
  }


  public function getResultsForLocation ($electionSlug, $latitude, $longitude)
  {
    $location = getLocation($latitude, $longitude);
    $state = $location['state'];
    $stateSlug = mapStateNameToSlug($state);

    // NOTE Code duplication from geolocationcontroller:getResultsforlocation
    $districtName = $location['district'];
    $districts = getDistricts($electionSlug, $stateSlug);
    $results = 'no results for the district "'.$districtName.' found.';

    $results = [];

    // results for district
    $results['district'] = [];
    foreach ($districts as $district) {
      if ( $district->name == $districtName ) {
        $results['district']['id'] = $district->id;
        $results['district']['name'] = $districtName;
        $results['district']['results'] = $district->results;
        break;
      }
    }

    // results for states and election
    $results += getParentGranularityResults($electionSlug, $state);
    return deliverJson($results);
  }
}
