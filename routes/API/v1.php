<?php
use Dingo\Api\Routing\Router;

/** @var \Dingo\Api\Routing\Router $api */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function (Router $api) {
    $api->group(['namespace' => 'App\Http\Controllers\V1', 'prefix' => 'v1', 'as' => 'v1.'], function ($api) {
  		// $api->get('/', function () {
		//     echo 'Welcome to our API';
		// });
    });
});
