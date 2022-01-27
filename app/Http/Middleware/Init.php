<?php
namespace App\Http\Middleware;

use Closure;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use Lcobucci\JWT\Parser;

class Init
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle($request, Closure $next)
    {
        // print_r('init');exit;
        try{
            $path = $request->path();
            // $platform       = self::verifyPlatform($request);
            // if(!empty($platform)) {
            //     return send_json_response($request,$platform);
            // }

            if(str_contains($path, '/general/auth/init')){
                return $next($request);
            } else {
                // $result = ['status' => 422,'errors' => ["general" => ['Unauthorized Action']]];
                // return response()->json($result,422);
                // $api_key        = self::VerifyAPIKEY($request);
                // $user_account   = self::VeriFyUserAccount($request);

                // if(!empty($api_key)){
                //     return send_json_response($request,$api_key);
                // } elseif(!empty($user_account)) {
                //     return send_json_response($request,$platform);
                // } else {
                    return $next($request);
                // }
            }
        } catch (\Exception $e) {
            // $response = setResponse('EXCEPTION');
            // return response()->json($response,$response['code']);
            // return get_response($request, $data);
        }
    }

    public static function VeriFyUserAccount($request)
    {
        $data = array();
        if(!Auth::guest()){
            // $userdata = DB::table('users')->where('id', $userid)->first();
            // if(empty($userdata)) {
            //     $data = setResponse('VALIDATION_ERROR', __('messages.invalid_user'));
            // } elseif ($userdata->status!='1') {
            //     $data = setResponse('ACCOUNT_DISABLED');
            // } elseif ($userdata->status!='1') {
            //     $data = setResponse('LICENSE_EXPIRED');
            // } else {
            //     $id = (new Parser())->parse($token)->getHeader('jti');
            //     $tokenExpiryTime = DB::table('oauth_access_tokens')->where('id', $id)->first()->expires_at;
            //     if (Carbon::parse($tokenExpiryTime) < Carbon::now()) {
            //         $data = setResponse('SESSION_EXPIRED');
            //     }
            // }
        }   
        return $data;
    }

    public static function verifyPlatform(Request $request)
    {
        $data = array();
        $platform = $request->header('PLATFORM');
        $validate_platform = explode(',', config('CommonValidator.platform'));
        if(!in_array($platform, $validate_platform)){
            $data = setResponse('INVALID_PLATFORM');
        }
        return $data;
    }

    public static function VerifyAPIKEY(Request $request)
    {
        $data = array();
        $api_key = $request->header('APIKEY');
        $company = '';//Company::select('api_key')->first();
        $validate_apikey = '';

        if(isset($company->api_key)){
            $validate_apikey = $company->api_key;
        }

        if(!$api_key || !$validate_apikey || ($api_key != $validate_apikey)){
            $data = setResponse('INVALID_API_KEY');
        }

        return $data;
    }

}
