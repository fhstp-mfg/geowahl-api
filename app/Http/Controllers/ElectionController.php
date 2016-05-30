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

    /*    public function loadJSON()
        {
            $string = file_get_contents("data/json/states.json");
            $statesData = json_decode($string);
            $states = $statesData->states;

            foreach ($states as $state)
            {
                echo '<pre>';
                echo print_r($state);
                echo '</pre>';
            }

            return $statesData;
        }*/

    public function showPath()
    {
        echo storage_path();
    }
}
