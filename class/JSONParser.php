<?php
/**
 * Created by: momomo
 * Date: 26/06/2018
*/

include_once 'RulesBase.php';
include_once 'Rule.php';

class JSONParser {
    private $cities = array();

    public function __construct($jsonMap) {
        $jsonMap = json_decode($jsonMap, true);

        foreach($jsonMap as $city) {
            $this->cities []= new City($city['city'], $city['lat'],$city['lng']);
        }
    }

    public function getMap() {
        return $this->cities;
    }
}