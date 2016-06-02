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

    return deliverJson($results);
  }


  public function getDistricts ($electionSlug, $stateSlug)
  {
    $districts = getDistricts($electionSlug, $stateSlug);

    return deliverJson($districts);
  }

  
  public function getResultsForLocation ($electionSlug, $stateSlug, $latitude, $longitude) {
    $location = getLocation($latitude, $longitude);
    $districtName = $location['district'];
    $districts = getDistricts($electionSlug, $stateSlug);
    $results = 'no results for coordinates "'.$latitude.','.$longitude.'" found';

    $results = [];
    foreach ($districts as $district) {
      if ( $district->name == $districtName) {
        $results['name'] = $districtName;
        $results['results'] = $district->results;
        break;
      }
    }
    return deliverJson($results);
  }
}
