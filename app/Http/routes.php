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
  // TODO create welcome view for API
  return view('welcome');
});

/// NOTE temp routes
Route::get('/map_vis', 'VisualizationController@showElectionMapVis');

/// ElectionController
Route::get('/elections', 'ElectionController@getAllElections');
Route::get('/{electionSlug}', 'ElectionController@getElection');
Route::get('/{electionSlug}/parties', 'ElectionController@getParties');
Route::get('/{electionSlug}/states', 'ElectionController@getStates');
Route::get('/{electionSlug}/{latitude},{longitude}',
  'ElectionController@getResultsForLocation'
);

/// VisualizationController
Route::get('/{electionSlug}/visualization',
  'VisualizationController@showElectionDonutVis'
);

/// StateController
Route::get('/{electionSlug}/{stateSlug}', 'StateController@getState');
Route::get('/{electionSlug}/{stateSlug}/districts', 'StateController@getDistricts');
Route::get('/{electionSlug}/{stateSlug}/{latitude},{longitude}',
  'StateController@getResultsForLocation'
);

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
      $districts = getDistricts($electionObj->slug, 'results');
      $election = clone $electionObj;
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

  // calculate percentage for each district
  foreach ($districts as $district) {
    $district->results = calculateResultsPercentage($district->results);
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

  // calculate percentage
  $results = calculateResultsPercentage($results);

  return $results;
}

/// Locations

/**
 * Function for returning the District with latitude and longitude
 */
function getLocation ($latitude, $longitude) {
  //load API-Key from .env
  $api_key = env('API_KEY');
  $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&language=de&key='.$api_key;
  // get the json response
  $resp_json = file_get_contents($url);

  // decode the json
  $all_location_data = json_decode($resp_json, true);

  if ($all_location_data['status'] === 'OK') {
    foreach ($all_location_data['results'] as $component) {

      if (in_array('administrative_area_level_1', $component['types'])) {
        $state = $component['address_components'][0]['short_name'];
        $result['state'] = $state;
      }
      if (in_array('postal_town', $component['types'])) {
        $postal_town = $component['address_components'][0]['short_name'];
        $result['district'] = $postal_town;
      }
    }
    return $result;
  }
  else{
    return 'no district for geolocation found';
  }
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


function calculateResultsPercentage ($results) {
  // calculate total votes
  $totalVotes = 0;
  foreach ($results as $result) {
    $totalVotes += is_object($result) ? $result->votes : $result['votes'];
  }

  // calculate percentage
  foreach ($results as $rIx => $result) {
    $votes = is_object($result) ? $result->votes : $result['votes'];
    $percentage = ($votes * 100) / $totalVotes;

    if ( is_object($results[$rIx]) ) {
      $results[$rIx]->percent = round($percentage, 2);
      $results[$rIx]->exact = $percentage;
    } else {
      $results[$rIx]['percent'] = round($percentage, 2);
      $results[$rIx]['exact'] = $percentage;
    }
  }

  return $results;
}


function logArray ($arr) {
  echo '<pre>';
  print_r($arr);
  echo '</pre>';
  echo '<hr>';
}


// returns slug of a state
function mapStateNameToSlug ($stateName) {
  $elections = getElections();

  foreach ($elections as $election){
    $states = $election->states;
    foreach ($states as $state){
      if($state->name == $stateName){
        $stateSlug = $state->slug;
      }
    }

  }
  return $stateSlug;
}
