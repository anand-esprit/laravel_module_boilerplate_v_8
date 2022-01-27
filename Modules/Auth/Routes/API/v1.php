<?php
use Dingo\Api\Routing\Router;

/** @var \Dingo\Api\Routing\Router $api */
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['middleware' => ['api']],function (Router $api) {   

    $api->group(['prefix' => 'v1', 'namespace'=>'Modules\Auth\Http\Controllers\V1'], function ($api) { 

        /* Authentication */       
        $api->group(['prefix' => 'auth'], function (Router $api) {
            $api->post('login', 'AuthController@login'); 
            /* logged after handle it*/
            $api->group(['middleware' => ['jwt.auth']], function ($api) {  
                $api->get('/logout', 'AuthController@logout');  
                // $api->get('/me', 'AuthController@me');
            });
            /* /logged after handle it*/
        });
        /* /Authentication */
    });
});