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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/elections', function () {
  $elections = getElections();

  foreach ($elections as $election) {
    unset($election->states);
  }

  return deliverJson($elections);
});

Route::get('/{electionSlug}',
  function ($electionSlug) {
    $electionDataObj = getElectionDataObj($electionSlug);

    return deliverJson($electionDataObj);
  }
);

Route::get('/{electionSlug}/parties',
  function ($electionSlug) {
    $elections = getElections();
    $parties = getParties($electionSlug);

    return deliverJson($parties);
  }
);

Route::get('/{electionSlug}/states',
  function ($electionSlug) {
    $states = getStates($electionSlug);
    return deliverJson($states);
  }
);
Route::get('/{electionSlug}/visualization', ['uses' =>'VisualizationController@showDonutVis']);

Route::get('/{electionSlug}/{stateSlug}',
  function ($electionSlug, $stateSlug) {
    $districts = getDistricts($electionSlug, $stateSlug);
    $results = getDistrictsResults($districts);

    return deliverJson($results);
  }
);


Route::get('/{electionSlug}/{stateSlug}/districts',
  function ($electionSlug, $stateSlug) {
    $districts = getDistricts($electionSlug, $stateSlug);

    // foreach ($districts as $district) {
    //   unset($district->results);
    // }

    return deliverJson($districts);
  }
);

Route::get('/geolocation/{latitude},{longitude}', ['uses' =>'GeoLocationController@getLocation']);

/// END routes





/// Elections

function getElections () {
  $electionsData = file_get_contents('data/json/elections.json');
  $elections = json_decode($electionsData);

  return $elections;
}

function getElectionDataObj ($electionSlug) {
  $elections = getElections();
  $election = 'no election with slug "'.$electionSlug.'" found';

  foreach ($elections as $electionObj) {
    if ( $electionObj->slug == $electionSlug ) {
      $districts = getDistricts($electionObj->slug, 'all');
      $election = $electionObj;
      $election->results = getDistrictsResults($districts);
      break;
    }
  }

  return $election;
}

/// Parties

function getParties ($electionSlug) {
  $elections = getElections();
  $parties = 'no parties found';

  foreach ($elections as $election) {
    if ($election->slug == $electionSlug) {
      $parties = $election->parties;
      break;
    }
  }

  return $parties;
}

/// States

function getStates ($electionSlug) {
  $elections = getElections();
  $states = 'no states found';

  foreach ($elections as $election) {
    if ($election->slug == $electionSlug) {
      $states = $election->states;
      break;
    }
  }

  return $states;
}


/// Districts

function getDistricts ($electionSlug, $stateSlug) {
  $states = getStates($electionSlug);
  $districts = 'no districts found';

  foreach ($states as $state) {
    if ($state->slug == $stateSlug) {
      // NOTE interpolation is nicer but slower than concatenation
      $districtPath = 'data/json/'.$electionSlug.'/'.$stateSlug.'.json';
      $districtsData = file_get_contents($districtPath);
      $districts = json_decode($districtsData);
      break;
    }
  }

  return $districts;
}

function getDistrictsResults ($districts) {
  $results = null;

  foreach ($districts as $district) {
    // init results parties
    if ( is_null($results) ) {
      foreach ($district->results as $rIx => $result) {
        $results[$rIx] = [];
        $results[$rIx]['name'] = $result->name;
        $results[$rIx]['votes'] = 0;
      }
    }

    // calculate results
    foreach ($district->results as $rIx => $result) {
      $results[$rIx]['votes'] += $result->votes;
    }
  }

  return $results;
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

function logArray ($arr) {
  echo '<pre>';
  print_r($arr);
  echo '</pre>';
  echo '<hr>';
}
