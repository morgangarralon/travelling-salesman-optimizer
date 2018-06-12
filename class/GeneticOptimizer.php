<?php
/**
 * Created by: momomo
 * Date: 26/06/2018
 */

include_once 'JSONParser.php';

class GeneticOptimizer {
    private $map;

    public function __construct($iterationNumber, $jsonUrl) {
        $jsonMap = file_get_contents($jsonUrl);
        $jsonParser = new JSONParser($jsonMap);
        $this->map = $jsonParser->getMap();
    }

    public function getOptimization() {
        $city_names = "";

        foreach($this->map as $city) {
            $city_names .= $city->getName();
        }

        return $city_names;
    }
}
