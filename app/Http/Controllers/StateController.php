<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class StateController extends Controller
{
  public function __construct () {
    // ...
  }


  public function getState ($electionSlug, $stateSlug)
  {
    $districts = getDistricts($electionSlug, $stateSlug);
    $results = getDistrictsResults($districts);
    $stateObj['results'] = $results;

    return deliverJson($stateObj);
  }


  public function getDistricts ($electionSlug, $stateSlug)
  {
    $districts = getDistricts($electionSlug, $stateSlug);
    $districtsObj['districts'] = $districts;

    return deliverJson($districtsObj);
  }

  public function getDistrictById ($electionSlug, $stateSlug, $districtId)
  {
    $districts = getDistricts($electionSlug, $stateSlug);
    $districtsObj['districts'] = $districts;

    foreach ($districtsObj['districts'] as $district) {
      if ($district->id == $districtId) {
        $results['district'] = $district;
      }
    }

    //get results for states and election
    $state = mapStateSlugToName($stateSlug);
    $results += getParentGranularityResults($electionSlug, $state);

    return deliverJson($results);
  }
}
