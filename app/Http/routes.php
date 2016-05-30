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
    $election = file_get_contents("data/json/elections.json");
    return $election;
});

$app->get('/{electionId}/states', function ($electionId) {
    $electionsData = file_get_contents("data/json/elections.json");
    $elections = json_decode($electionsData);

    $states = 'no states found';
    foreach ($elections as $election) {
        if ( $election->id == $electionId) {
            $states = json_encode($election->states);
            break;
        }
    }

    return $states;
});

$app->get('/:electionId/:stateId/districts', function () {
    $districts = file_get_contents("data/json/states.json");
    return $districts;
});