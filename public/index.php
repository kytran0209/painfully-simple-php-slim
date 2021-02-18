<?php
require '../vendor/autoload.php';

$app = new \Slim\App();

// Global variable
$countries = array(
    array('name' => 'USA'),
    array('name' => 'India'),
    array('name' => 'Argentina'),
    array('name' => 'Armenia'),
);

$app->get('/', function($request, $response, $args) {
    $response->write('Welcome to Slim crash course');
});

// Pass global variable into this function with 'use'
$app->get('/countries', function($request, $response, $args) use ($countries) {
    // $countries is an array so we have to use withJson
    return $response->withJson($countries);
});

function startsWith($string, $substring) {
    $len = strlen($substring);
    return (substr($string, 0, $len) === $substring);
}

// Sample request url /countries/search?term=a
$app->get('/countries/search', function($request, $response, $args) use ($countries) {
    $term = $request->getQueryParams()['term'];

    if ($term === '') {
        return $response->withHeader('Access-Control-Allow-Origin', '*')->withJson(array());
    }

    $filteredCountries = array();
    
    foreach($countries as $key => $value) {
        if (startsWith(strtolower($value['name']), strtolower($term))) {
            array_push($filteredCountries, $value);
        }
    }

    return $response->withHeader('Access-Control-Allow-Origin', '*')->withJson($filteredCountries);
});

$app->run();

?>