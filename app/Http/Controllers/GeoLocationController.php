<?php

namespace App\Http\Controllers;


class GeoLocationController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct ()
  {
    // ...
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

/// Helper functions

// NOTE code duplication with deliverJson from routes.php
function deliverJson ($data) {
  $responseCode = 200;

  $header = [
    'Content-Type' => 'application/json; charset=UTF-8',
    'charset' => 'utf-8'
  ];

  return response()->json($data, $responseCode, $header, JSON_UNESCAPED_UNICODE);
}