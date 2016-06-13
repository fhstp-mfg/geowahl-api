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
    //if there is an error, return it
    if (isset($districts['errors'])) {
      return deliverJson($districts);
    }

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
    //initialize error object
    $results = [
      'errors' => [
        'status' => 420,
        'title' => '\''.$districtId.'\' not found',
        'detail' => 'Sorry! We could not find a district with the id  \''.$districtId.'\'.'
      ]
    ];

    foreach ($districts as $district) {
      if ($district->id == $districtId) {
        $results['district'] = $district;
      }
    }

    // results for states and election
    $state = mapStateSlugToName($stateSlug);
    $parentGranularityResults = getParentGranularityResults($electionSlug, $state);

    if ( ! empty($results) ) {
      $results = array_merge($results, $parentGranularityResults);
    }

    return deliverJson($results);
  }
}
