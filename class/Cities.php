<?php
/**
 * Created by: momomo
 * Date: 26/06/2018
 */

class Cities {
    private $name;
    private $lat;
    private $lng;

    /**
     * Cities constructor.
     */
    public function __construct($name, $lat, $lng) {
        if(is_null($name) || strlen($name) === 0
            || is_null($lat) || strlen($lat) === 0
            || is_null($lng) || strlen($lng) === 0
            ) {
            throw new Exception("The JSON file representing cities seems to be corrupted!");
        } else {
            $this->name = $name;
            $this->lat = $lat;
            $this->lng = $lng;
        }
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * @return mixed
     */
    public function getLng() {
        return $this->lng;
    }

}