<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class VisualizationController extends Controller
{
    //
    public function showDonutVis($electionSlug)
    {
      $electionPath = 'data/json/'.$electionSlug.'/results.json';
      $electionData = file_get_contents($electionPath);
      $election = json_decode($electionData);

      return view('visualization')->with($election);
    }
}
