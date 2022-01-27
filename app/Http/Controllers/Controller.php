<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
    
/**
* @OA\Info(
*     description="L5 Swagger OpenApi description",
*     version="1.0.0",
*     title="Every Thing Civic OpenApi Documentation",
* )
*/

/**
* @OA\Server(
*     url=L5_SWAGGER_CONST_HOST_V1,
*     description="Every Thing Civic V1"
* )
*/    

/**
* @OA\Server(
*     url=L5_SWAGGER_CONST_HOST_V2,
*     description="Every Thing Civic V2"
* ) 
*/

/**
* @OA\SecurityScheme(
*     type="http",
*     description="Login with email and password to get the authentication token",
*     name="Token based Based",
*     in="header",
*     scheme="bearer",
*     bearerFormat="JWT",
*     securityScheme="apiAuth",
* )
*/

/**
* @OA\SecurityScheme(
*    securityScheme="PLATFORM",
*    type="apiKey",
*    in="header",
*    name="PLATFORM"
* )
*/

/**
* @OA\SecurityScheme(
*    securityScheme="APIKEY",
*    type="apiKey",
*    in="header",
*    name="APIKEY"
* )
*/

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
