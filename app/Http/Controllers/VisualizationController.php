<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class VisualizationController extends Controller
{
    //
    public function showElectionDonutVis($electionSlug)
    {
      $electionDataObj = getElectionDataObj($electionSlug);

      $electionResult = json_encode($electionDataObj->results);
      //return $electionResult;
      return view('visualization')->with('visData',$electionResult);
    }

    public function showStateDonutVis($electionSlug, $stateSlug)
    {
      $districts = getDistricts($electionSlug, $stateSlug);

      $results = json_encode(getDistrictsResults($districts));
      //return $results;
      return view('visualization')->with('visData',$results);
    }

    public function showElectionMapVis()
    {
      return view('map_visualization');
    }
}
