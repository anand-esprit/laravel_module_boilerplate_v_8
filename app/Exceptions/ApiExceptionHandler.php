<?php

namespace App\Exceptions;
use Dingo\Api\Http\Request;


// use Specialtactics\L5Api\Exceptions\RestfulApiExceptionHandler;
// use Exception;
use Dingo\Api\Exception\Handler as DingoHandler;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


/**
 * This class extends the Dingo API Exception Handler, and can be used to modify it's functionality, if required
 *
 * Unless you have a specific-use case and understand what you are doing, it's best not to modify this.
 *
 * Class ApiHandler
 */
class ApiExceptionHandler extends DingoHandler
{
	public function handle($exception)
    {
    	// dd($exception);die;
    	// return $exception;
    	/*
    	$debug = env('API_DEBUG',false);
    	$statusCode = $exception->getStatusCode();
    	$message = $exception->getMessage();
    	
    	// Custom Exception Handler if debug is false. when debug true we will send proper information to debuging 
    	// if(!$debug){
    	// 	// Dingo has already handle this but we need a API structure with our response so we have added here
    	// 	$request = new Request();
	    // 	if ($exception instanceof MethodNotAllowedHttpException){
	    //         $response = setResponse('EXCEPTION');
	    //         return send_json_response($request, $response);
	    //     }
	    // } 

	    $custom_exception_name = 'EXCEPTION';
    	if ($exception instanceof NotFoundHttpException){
	        $custom_exception_name = 'INVALID_URL';
	    } 

    	$request = new Request();
    	$data = [];
	    if($debug){
	    	$data['debug'] = [
		    	'line' => $exception->getLine(),
		        'file' => $exception->getFile(),
		        'class' => get_class($exception),
		        'trace' => explode("\n", $exception->getTraceAsString())
		    ];
	    }

	    $response = setResponse($custom_exception_name,[$message],$data);
	    $response['code'] = $statusCode;
        // unset($response['code']);
	    return send_json_response($request, $response);
        // return parent::handle($exception);
	    */

        $FinalException = parent::handle($exception);

    	// extend final response with our custom response formate
    	$r = json_decode($FinalException->getContent());
    	$debug = env('API_DEBUG',false);
    	$statusCode = $r->statusCode;
    	$message = $r->message;
    	
	    $custom_exception_name = 'EXCEPTION';
    	if ($exception instanceof NotFoundHttpException){
	        $custom_exception_name = 'INVALID_URL';
	    } 
	    if ($exception instanceof UnauthorizedHttpException){
	        $custom_exception_name = 'AUTH_FAILED';
	    } 

    	$request = new Request();
    	$data = [];
	    if($debug){
	    	$data['debug'] = (array) $r->debug;
	    }
	    $response = setResponse($custom_exception_name,[$message],$data);
	    $response['code'] = $statusCode;
	    return send_json_response($request, $response);
    }
}
