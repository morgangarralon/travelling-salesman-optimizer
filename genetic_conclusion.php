<?php
/**
 * User: jerem & momomo
 * Date: 14/03/2018
 */

include_once 'class/GeneticOptimizer.php';

if(!empty($_POST)) {
    $inputs = $_POST;

    try {
        $geneticOptimizer = new GeneticOptimizer($_POST['iteration_number'],'data/map.json');
    } catch (Exception $e) {
        echo json_encode($e->getMessage());
    }

    $optimization = $geneticOptimizer->doOptimization();
    $optimization['itinerary'] =  array_values($optimization['itinerary']);

    echo json_encode($optimization);
}

?>