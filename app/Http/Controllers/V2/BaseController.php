<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    // protected function respondWithToken($token, $data = [])
    // {
    //     return response()->json([
    //         'data' => $data,
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => Auth::guard()->factory()->getTTL()
    //     ]);
    // }
}
