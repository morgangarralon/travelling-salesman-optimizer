<?php
/**
 * User: momomo
 * Date: 14/03/2018
 */

define('API_KEY', 'AIzaSyBBGgsmfRsGYRyPqWKmwwKFFw__1XWBmOA');

if(!empty($_POST)) {
    $route = $_POST['genetic_conclusion'];

    $endCity     = array_pop($route);
    $startCity   = array_shift($route);

    $url = 'https://maps.googleapis.com/maps/api/directions/json?';
    $url .= 'origin='.urlencode($startCity['lat'] . ',' . $startCity['lng']);
    $url .= '&destination='.urlencode($endCity['lat'] . ',' . $endCity['lng']).'&waypoints=';

//    foreach($route as $city) {
//        $url .= 'via:' . urlencode($city['lat'] . ',' . $city['lng']);
//        $url .= '|';
//    }
    for($i = 0; $i < count($route); $i++) {
        $url .= 'via:' . urlencode($route[$i]['lat'] . ',' . $route[$i]['lng']);
        $url .= '|';
        if($i % 7 === 0) {
            $url = substr($url, 0, -1);
            $url .= '&mode=' . urlencode('driving');
            $url .= '&unites=' . urlencode('metric');
            $url .= '&key=' . API_KEY;

            $maps_request = curl_init();
            curl_setopt($maps_request, CURLOPT_URL, $url);
            curl_setopt($maps_request, CURLOPT_POST, 0);
            curl_setopt($maps_request, CURLOPT_RETURNTRANSFER, true);
            $maps_response = curl_exec($maps_request);
            curl_close ($maps_request);
        }
    }

    echo json_encode($maps_response);
}

?>