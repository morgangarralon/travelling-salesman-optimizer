<?php
/**
 * Created by: momomo
 * Date: 26/06/2018
*/

include_once 'City.php';

class JSONParser {
    private $cities = array();

    public function __construct($jsonMap) {
        $index = 'a';
        $jsonMap = json_decode($jsonMap, true);

        foreach($jsonMap as $city) {
            $this->cities[$index] = new City($index, $city['city'], $city['lat'],$city['lng']);
            $index++;
        }
    }

    public function getMap() {
        return $this->cities;
    }
}