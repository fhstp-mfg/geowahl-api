<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


/// General

$app->get('/', function () use ($app) {
  return $app->version();
});


/// Elections

$app->get('/elections',
  function () {
    $elections = getElections();

    return response()->json($elections);
  }
);


/// States

$app->get('/{electionId}/states',
  function ($electionId) {
    $elections = getElections();
    $states    = getStates($elections, $electionId);

    return response()->json($states);
  }
);


/// Districts

$app->get('/{electionId}/{stateSlug}/districts',
  function ($electionId, $stateSlug) {
    $elections = getElections();
    $states    = getStates($elections, $electionId);
    $districts = getDistricts($states, $stateSlug);

    return response()->json($districts);
  }
);


// ...

/// END routes





/// functions

function getElections () {
  $electionsData = file_get_contents('data/json/elections.json');
  $elections = json_decode($electionsData);

  return $elections;
}


function getStates ($elections, $electionId) {
  $states = 'no states found';

  foreach ($elections as $election ) {
    if ( $election->id == $electionId ) {
      $states = $election->states;
      break;
    }
  }

  return $states;
}


function getDistricts ($states, $stateSlug) {
  $districts = 'no districts found';

  foreach ($states as $state) {
    if ( $state->slug == $stateSlug ) {
      // NOTE interpolation is nicer but slower than concatenation
      // $districtPath  = "data/json/$stateSlug.json";
      $districtPath  = 'data/json/'.$stateSlug.'.json';
      $districtsData = file_get_contents($districtPath);
      $districts     = json_decode($districtsData);
      break;
    }
  }

  return $districts;
}
