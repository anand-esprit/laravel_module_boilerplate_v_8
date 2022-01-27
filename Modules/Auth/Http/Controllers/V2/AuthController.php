<?php

namespace Modules\Auth\Http\Controllers\V2;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\V2\BaseController;
use DB;
use Auth;
use Validator;

class AuthController extends BaseController
{
    
    /**  
    * Get a JWT token 
    *     
    * @param  \Illuminate\Http\Request $request     
    * @return \Illuminate\Http\JsonResponse
    */  
       
    public function login(Request $request)
    {
        exit;
    }    
}
