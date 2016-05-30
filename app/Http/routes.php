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
  $elections = file_get_contents('data/json/elections.json');

  return $elections;
});


$app->get('/{electionId}/states',
  function ($electionId) {
    $electionsData = file_get_contents('data/json/elections.json');
    $elections = json_decode($electionsData);

    return getStates($elections, $electionId);
  }
);


$app->get('/{electionId}/{stateSlug}/districts',
  function ($electionId, $stateSlug) {
    $electionsData = file_get_contents('data/json/elections.json');
    $elections = json_decode($electionsData);
    $statesData = getStates($elections, $electionId);
    $states = json_decode($statesData);
    $districts = 'no districts found';

    foreach ($states as $state) {
      if ( $state->slug == $stateSlug ) {
        $districtPath = 'data/json/' . $stateSlug . '.json';
        $districts = file_get_contents($districtPath);
        break;
      }
    }

    return $districts;
  }
);



/// functions

function getStates ($elections, $electionId) {
  $states = 'no states found';

  foreach ($elections as $election ) {
    if ( $election->id == $electionId) {
      $states = json_encode($election->states);
      break;
    }
  }

  return $states;
}
