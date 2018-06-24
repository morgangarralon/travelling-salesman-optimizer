<?php
/**
 * Created by: momomo
 * Date: 26/06/2018
 */

include_once 'JSONParser.php';
include_once 'Chromosome.php';

class GeneticOptimizer {
    private $itinerary;
    private $generation;
    private $costMatrixMaps;
    private $iterationNumber;
    private $costMatrixTheoric;
    const GENERATION_NUMBER = 100;
    const SELECTED_PARENT_NUMBER = 10;

    /**
     * GeneticOptimizer constructor.
     * @param $iterationNumber
     * @param $jsonUrl
     */
    public function __construct($iterationNumber, $jsonUrl) {
        $jsonMap = file_get_contents($jsonUrl);
        $jsonParser = new JSONParser($jsonMap);
        $this->itinerary = $jsonParser->getMap();
        $this->iterationNumber = $iterationNumber;

        $this->createCostMatrices();
        $this->doBigBang();
    }

    public function doOptimization() {
        $i = 0;

        $parentFemales  = $this->doFitness(self::SELECTED_PARENT_NUMBER);
        $parentMales    = $parentFemales;
        while($i < self::GENERATION_NUMBER) {

            foreach($parentFemales as $idFemale => $female) {
                foreach($parentMales as $idMale => $male) {
                    if($idFemale !== $idMale) {
                        $newChromosome = $this->doSequentialConstructiveCrossover($female, $male);
                        $this->generation []= $newChromosome;
                    }
                }
            }
            $this->generation = $this->doFitness(self::GENERATION_NUMBER);
            if($i < self::GENERATION_NUMBER - 1) {
                $this->doMutation();
                $parentFemales  = array_slice($this->generation, 0, self::SELECTED_PARENT_NUMBER);
                $parentMales    = $parentFemales;
            }

            $i++;
        }

        $finalItineraryAndDistance = $this->getFinalItineraryAndDistance();

        return $finalItineraryAndDistance;
    }

    private function getFinalItineraryAndDistance() {
        $distances = $this->calculateDistancesTheoric();

        asort($distances, SORT_NUMERIC);
        $finalDistance = array_slice($distances, 0, 1, true);

        $finalChromosome = array_intersect_key($this->generation, $finalDistance);
        reset($finalChromosome);
        $finalChromosome = $finalChromosome[key($finalChromosome)];
        $finalItinerary = $finalChromosome->getCities();
        reset($finalItinerary);
        $startCity = $finalChromosome->getCity(key($finalItinerary));
        end($finalItinerary);
        $endCity = $finalChromosome->getCity(key($finalItinerary));
        reset($finalDistance);
        $finalDistance = $finalDistance[key($finalDistance)];

        $finalItinerary['end'] = $startCity;
        $finalDistance += $this->getDistance($startCity->getLat(), $startCity->getLng(), $endCity->getLat(), $endCity->getLng());

        return [
            'distance' => $finalDistance,
            'itinerary' => $finalItinerary
            ];
    }

    private function doSequentialConstructiveCrossover($female, $male) {
        $childCities = array();
        $cityNumber = count($female->getCities());

        if($cityNumber  !== count($male->getCities())) {
            return false;
        }

        $maleCities = $male->getCities();
        $femaleCities = $female->getCities();
        $currentCity = current($femaleCities);
        unset($maleCities[$currentCity->getId()]);
        unset($femaleCities[$currentCity->getId()]);
        $nextMaleCity = current($maleCities);
        $nextFemaleCity = current($femaleCities);
        $childCities[$currentCity->getId()] = $currentCity;

        for($i = 0; $i < $cityNumber ; $i++) {
            if($nextMaleCity === false) {
                $childCities += $femaleCities;
                break;
            } elseif($nextFemaleCity === false) {
                $childCities += $maleCities;
                break;
            } else {
                if ($this->costMatrixTheoric[$currentCity->getId()][$nextMaleCity->getId()] <= $this->costMatrixTheoric[$currentCity->getId()][$nextFemaleCity->getId()]) {
                    if (!array_key_exists($nextMaleCity->getId(), $childCities)) {
                        $childCities[$nextMaleCity->getId()] = $nextMaleCity;
                        $currentCity = $nextMaleCity;
                    }
                } else {
                    if (!array_key_exists($nextFemaleCity->getId(), $childCities)) {
                        $childCities[$nextFemaleCity->getId()] = $nextFemaleCity;
                        $currentCity = $nextFemaleCity;
                    }
                }
            }

            unset($maleCities[$currentCity->getId()]);
            unset($femaleCities[$currentCity->getId()]);
            $maleKeys = array_keys($maleCities);
            $femaleKeys = array_keys($femaleCities);
            $maleIndex = array_search($currentCity->getId(), $maleKeys);
            $femaleIndex = array_search($currentCity->getId(), $femaleKeys);

            if(isset($maleKeys[$maleIndex + 1])) {
                $nextMaleIndex = $maleKeys[$maleIndex + 1];
                $nextMaleCity = $maleCities[$nextMaleIndex];
            } else {
                $nextMaleCity = false;
            }
            if(isset($femaleKeys[$femaleIndex + 1])) {
                $nextFemaleIndex = $femaleKeys[$femaleIndex + 1];
                $nextFemaleCity = $femaleCities[$nextFemaleIndex];
            } else {
                $nextFemaleCity = false;
            }
        }

        $child = new Chromosome($childCities);

        return $child;
    }

    private function doOnePointCrossover() {
        #TODO
    }

    private function doMutation() {
        $chromosomeToSwap = array_rand($this->generation, self::GENERATION_NUMBER / 10);

        for($i = 0; $i < self::GENERATION_NUMBER / 10; $i++) {
            $citiesToSwap = $this->generation[$chromosomeToSwap[$i]]->getCities();
            $startCity = array_slice($citiesToSwap, 0, 1, true);
            array_shift($citiesToSwap);
            $keyToSwap = array_rand($citiesToSwap, 1);

            $oldCityToSwap = $citiesToSwap[$keyToSwap[0]];
            unset($citiesToSwap[$keyToSwap[0]]);
            $citiesToSwap[$keyToSwap[0]] = $oldCityToSwap;
            
            $citiesToSwap = $startCity + $citiesToSwap;
            $this->generation[$chromosomeToSwap[$i]] = new Chromosome($citiesToSwap);
        }
    }

    private function calculateDistancesTheoric() {
        $distances = array();

        foreach($this->generation as $index => $chromosome) {
            $cities = $chromosome->getCities();
            $cityFrom = array_pop($cities);
            $cityTo = current($cities);
            $distance = 0;

            while(sizeof($cities) >= 2) {
                $distance += $this->costMatrixTheoric[$cityFrom->getId()][$cityTo->getId()];
                $cityFrom = $cityTo;
                $cityTo = array_pop($cities);
            }

            $distances[$index] = $distance;
        }

        return $distances;
    }

    private function doFitness($selectionNumber) {
        $parents = array();
        $distances = $this->calculateDistancesTheoric();

        asort($distances, SORT_NUMERIC);
        $fitestDistances = array_slice($distances, 0, $selectionNumber, true);

        $parents = array_intersect_key($this->generation, $fitestDistances);

        return $parents;
    }

    private function doBigBang() {
        $index = 0;
        $this->generation = array();
        $tmpItinerary = $this->itinerary;
        array_pop($tmpItinerary);
        reset($this->itinerary);
        $departureCity = current($this->itinerary);

        while($index < self::GENERATION_NUMBER) {
            $newItinerary = array($departureCity->getId() => $departureCity) + $this->shuffle_assoc($tmpItinerary);

            $this->generation[$index] = new Chromosome($newItinerary);

            $index++;
        }
    }

    private function createCostMatrices() {
        $this->costMatrixTheoric = array();

        foreach($this->itinerary as $cityy) {
            $idy = $cityy->getId();
            $this->costMatrixTheoric[$idy] = array();
            foreach($this->itinerary as $cityx) {
                $idx = $cityx->getId();
                if($idy <> $idx) {
                    $this->costMatrixTheoric[$idy][$idx] = $this->getDistance($cityy->getLat(), $cityy->getLng(), $cityx->getLat(), $cityx->getLng());

                    #TODO from Maps DirectionMatrix
                    $this->costMatrixMaps[$idy][$idx] = null;
                } else {
                    $this->costMatrixMaps[$idy][$idx] = -1;
                }
            }
        }
    }

    private function getDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo) {
        $radius = M_PI / 180;
        $theta = $longitudeFrom - $longitudeTo;
        $distance = sin($latitudeFrom * $radius) * sin($latitudeTo * $radius) +  cos($latitudeFrom * $radius) * cos($latitudeTo * $radius) * cos($theta * $radius);

        return acos($distance) / $radius * 60 *  1.853;
    }

    private function shuffle_assoc($array) {
        $keys = array_keys($array);
        shuffle($keys);

        return array_merge(array_flip($keys), $array);
    }

//    private function array_key_replace($array, $oldKey, $newKey, $newValue) {
//        $newArray = [];
//        foreach ($array as $key => $value) {
//            $newArray[$key === $oldKey ? $newKey : $key] = $newValue;
//        }
//        return $newArray;
//    }

    /**
     * @return mixed
     */
    public function getGeneration() {
        return $this->generation;
    }

    /**
     * @param mixed $generation
     */
    public function setGeneration($generation) {
        $this->generation = $generation;
    }

    /**
     * @return mixed
     */
    public function getCostMatrixTheoric() {
        return $this->costMatrixTheoric;
    }

    /**
     * @return mixed
     */
    public function getCostMatrixMaps() {
        return $this->costMatrixMaps;
    }

    /**
     * @return array
     */
    public function getItinerary() {
        return $this->itinerary;
    }
}
