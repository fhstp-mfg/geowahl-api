<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;


class VisualizationController extends Controller
{
  public function __construct () {
    // ...
  }


  public function showElectionDonutVis ($electionSlug)
  {
    $electionDataObj = getElectionDataObj($electionSlug);
    $electionResult = json_encode($electionDataObj->results);

    return view('visualization')->with('visData', $electionResult);
  }


  public function showStateDonutVis ($electionSlug, $stateSlug)
  {
    $electionDataObj = getElectionDataObj($electionSlug);

    $electionResult = json_encode($electionDataObj->results);
    $electionParties = json_encode($electionDataObj->parties);

    //logArray($electionParties);
    $colorData = array();
    foreach ($electionDataObj->parties as $key) {
      $colorData[$key->name] = $key->hex;
    }
    logArray($colorData);
    //return $electionResult;
    return view('donut_visualization')->with('visData', $electionResult);
  }

  public function showDistrictDonutVis ($electionSlug, $stateSlug, $districtId)
  {
    $districts = getDistricts($electionSlug, $stateSlug);
    $districtsObj['districts'] = $districts;

    foreach ($districtsObj['districts'] as $district) {
      if($district->id == $districtId){
        $results['district'] = json_encode($district->results);
      }
    }

    return view('visualization')->with('visData', $results['district']);
  }


  public function showElectionMapVis ()
  {
    return view('map_visualization');
  }
}
