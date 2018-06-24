<?php
/**
 * Created by: momomo
 * Date: 26/06/2018
 */

class Chromosome {
    private $cities;

    /**
     * Cities constructor.
     */
    public function __construct($cities) {
        $this->cities = $cities;
    }

    /**
     * @param City $city
     * @return array
     */
    public function addCity($city) {
        array_push($this->cities, $city);

        return $this->cities;
    }

    /**
     * @param char $city_id
     * @return array
     */
    public function getCity($city_id) {
        return $this->cities[$city_id];
    }

    /**
     * @return array
     */
    public function getCities() {
        return $this->cities;
    }
}