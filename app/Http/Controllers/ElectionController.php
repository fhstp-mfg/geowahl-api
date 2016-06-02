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

    foreach ($elections as $election) {
      unset($election->states);
    }

    return deliverJson($elections);
  }


  public function getElection ($electionSlug)
  {
    $electionDataObj = getElectionDataObj($electionSlug);

    return deliverJson($electionDataObj);
  }


  public function getParties ($electionSlug)
  {
    $parties = getParties($electionSlug);

    return deliverJson($parties);
  }


  public function getStates ($electionSlug)
  {
    $states = getStates($electionSlug);

    return deliverJson($states);
  }


  public function getTest ($electionSlug, $latitude, $longitude)
  {
    $location = getLocation($latitude, $longitude);
    $state = $location['state'];
    $state = mapStateNameToSlug ($state);

    // NOTE Code duplication from geolocationcontroller:getResultsforlocation
    $districtName = $location['district'];
    $districts = getDistricts($electionSlug, $state);
    $results = 'no results for the district "'.$districtName.' found.';

    $results = [];

    $results['district'] = [];
    foreach ($districts as $district) {
      if ( $district->name == $districtName) {
        $results['district']['name'] = $districtName;
        $results['district']['results'] = $district->results;
        break;
      }
    }

    //get results for states
    $districts = getDistricts($electionSlug, $state);
    $results['state']['name'] = $location['state'];
    $results['state']['results'] = getDistrictsResults($districts);

    return deliverJson($results);
  }
}
