<?php
use Dingo\Api\Routing\Router;

/** @var \Dingo\Api\Routing\Router $api */
$api = app('Dingo\Api\Routing\Router');
$api->version('v1',function (Router $api) {   

    $api->group(['prefix' => 'v2', 'namespace'=>'Modules\Auth\Http\Controllers\V2'], function ($api) { 

        /* Authentication */       
        $api->group(['prefix' => 'auth'], function (Router $api) {
            $api->get('login', 'AuthController@login'); 
        });
        /* /Authentication */
    });
});