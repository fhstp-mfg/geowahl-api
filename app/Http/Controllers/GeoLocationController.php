<?php

namespace App\Http\Controllers;


class GeoLocationController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }
  /*
  *
  *
  */
  public function getLocation($latitude, $longitude){
    require_once dirname(__DIR__).'/includes/api_key.php';
    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&key=".$api_key;
    // get the json response
    $resp_json = file_get_contents($url);
    // decode the json
    $all_location_data = json_decode($resp_json, true);
    var_dump($all_location_data);
    foreach ($all_location_data as $component) {
      if(in_array('postal_town', $component['types'])){
        $postal_town = $component;
      }
    }
    return $postal_town;
  }
}
