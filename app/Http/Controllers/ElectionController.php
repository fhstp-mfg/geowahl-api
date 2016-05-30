<?php

namespace App\Http\Controllers;

class ElectionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function loadJSON() {
      // $filename = "states";
      // $path = storage_path() . "/json/$filename.json"; // ie: /var/www/storage/json/filename.json
      //
      // $file = File::get($path);
      // return $file;

      $string = file_get_contents("storage/json/states.json");
      $json_a = json_decode($string, true);

      foreach ($json_a as $person_name => $person_a) {
         echo $person_a['status'];
      }
    }
    public function showPath(){
      echo storage_path();
    }
}
