<?php
/**
 * Created by: momomo
 * Date: 26/06/2018
 */

class City implements JsonSerializable {
    private $id;
    private $lat;
    private $lng;
    private $name;

    /**
     * Cities constructor.
     */
    public function __construct($index, $name, $lat, $lng) {
        if(is_null($name) || strlen($name) === 0
            || is_null($lat) || strlen($lat) === 0
            || is_null($lng) || strlen($lng) === 0
            ) {
            throw new Exception("The JSON file representing cities seems to be corrupted!");
        } else {
            $this->id = $index;
            $this->name = $name;
            $this->lat = $lat;
            $this->lng = $lng;
        }
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
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

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lat' => $this->lat,
            'lng' => $this->lng
        ];
    }
}