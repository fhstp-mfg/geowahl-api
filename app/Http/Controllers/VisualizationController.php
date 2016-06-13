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
    $electionParties = json_encode($electionDataObj->parties);

    $colorData = array();
    foreach ($electionDataObj->parties as $key) {
      $colorData[] = $key->hex;
    }
    $data = array('data' => $electionResult, 'color' => $colorData );
    logArray($data);
    $data = json_encode($data);

    return view('donut_visualization')->with('visData', $data);
  }


  public function showStateDonutVis ($electionSlug, $stateSlug)
  {
    $districts = getDistricts($electionSlug, $stateSlug);
    $electionDistrictResult = json_encode(getDistrictsResults($districts));

    $electionDataObj = getElectionDataObj($electionSlug);
    $electionParties = json_encode($electionDataObj->parties);

    $colorData = array();
    foreach ($electionDataObj->parties as $key) {
      $colorData[] = $key->hex;
    }
    $data = array('data' => $electionDistrictResult, 'color' => $colorData );
    //logArray($data);
    $data = json_encode($data);
    return view('donut_visualization')->with('visData', $data);
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
