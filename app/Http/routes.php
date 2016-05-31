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

$app->get('/', function () use ($app) {
  return $app->version();
});

$app->get('/elections', function () {
  $elections = getElections();
  return deliverJson($elections);
});

$app->get('/{electionId}/states',
  function ($electionId) {
    $states = getStates($electionId);
    return deliverJson($states);
  }
);

$app->get('/{electionId}/{stateSlug}/districts',
  function ($electionId, $stateSlug) {
    $districts = getDistricts($electionId, $stateSlug);
    return deliverJson($districts);
  }
);

/// END routes


/// Elections

function getElections () {
  $electionsData = file_get_contents('data/json/elections.json');
  $elections = json_decode($electionsData);

  return $elections;
}


/// States

function getStates ($electionId) {
  $elections = getElections();
  $states = 'no states found';

  foreach ($elections as $election) {
    if ($election->id == $electionId) {
      $states = $election->states;
      break;
    }
  }

  return $states;
}


/// Districts

function getDistricts ($electionId, $stateSlug) {
  $states = getStates($electionId);
  $districts = 'no districts found';

  foreach ($states as $state) {
    if ($state->slug == $stateSlug) {
      // NOTE interpolation is nicer but slower than concatenation
      // $districtPath  = "data/json/$stateSlug.json";
      $districtPath = 'data/json/' . $stateSlug . '.json';
      $districtsData = file_get_contents($districtPath);
      $districts = json_decode($districtsData);
      break;
    }
  }

  return $districts;
}


/// Helper functions

function deliverJson ($data) {
  $responseCode = 200;

  $header = [
    'Content-Type' => 'application/json; charset=UTF-8',
    'charset' => 'utf-8'
  ];

  return response()->json($data, $responseCode, $header, JSON_UNESCAPED_UNICODE);
}
