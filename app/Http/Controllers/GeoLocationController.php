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

  /*
   * Function for returning the District with latitude and longitude
   *
   */
  public function getLocation ($latitude, $longitude) {
    //load API-Key from .env
    $api_key = env('API_KEY');
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&key='.$api_key;
    // get the json response
    $resp_json = file_get_contents($url);

    // decode the json
    $all_location_data = json_decode($resp_json, true);

    if ($all_location_data['status'] === 'OK') {
      foreach ($all_location_data['results'] as $component) {
        if (in_array('postal_town', $component['types'])) {
          $postal_town = $component['address_components'][0]['short_name'];
          return $postal_town;
        }
      }
    }

    return 'no district for geolocation found';
  }


  public function getResultsForLocation ($electionSlug, $stateSlug, $latitude, $longitude) {
    $districtName = $this->getLocation($latitude, $longitude);
    $districts = getDistricts($electionSlug, $stateSlug);
    $results = 'no results for coordinates "'.$latitude.','.$longitude.'" found';

    foreach ($districts as $district) {
      if ( $district->name == $districtName) {
        $results = [];
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
