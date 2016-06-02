<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class VisualizationController extends Controller
{
    //
    public function showDonutVis($electionSlug)
    {
      $electionDataObj = getElectionDataObj($electionSlug);

      $electionResult = json_encode($electionDataObj->results);
      //return $electionResult;
      return view('visualization')->with('visData',$electionResult);
    }
}
