<?php

namespace Modules\Auth\Http\Controllers\V1;

use App\Http\Controllers\V1\BaseController;
use Specialtactics\L5Api\Http\Controllers\Features\JWTAuthenticationTrait;

use DB;
use Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\User;

class AuthController extends BaseController
{
    // use JWTAuthenticationTrait;

    /* middleware that verifies the user of application is authenticated  */
    public function __construct(Request $request)
    {
        $this->init($request);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contract s\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

    /**
    * This Method user for common Rules And Messages
    */
    public function init($request) 
    {
        $user = new User();
        $user_table_name = $user->getTable();

        $this->rules = (object) [
            'username' => 'required|exists:'.$user_table_name.',username',
            'login_id' => 'required|min:8',
            'email' => 'required|email|exists:'.$user_table_name.',email',
            'otp' => 'required|'.config('CommonValidator.digits.otp'),
            'password' => 'required|'.config('CommonValidator.min.password').'|'.config('CommonValidator.regex.password'),
            'new_password' => 'required|'.config('CommonValidator.min.password').'|'.config('CommonValidator.regex.password'),
            'device_token' => 'nullable',
            'widget_order' => 'required',
        ];

        $this->message = (object) [
            'username_required' => __('api.common.USERNAME_OR_EMAIL_REQUIRED'),
            'username_exists' => __('api.common.USERNAME_EXISTS'),
            'login_id_required' => __('api.common.LOGIN_ID_REQUIRED'),
            'login_id_min' => __('api.common.LOGIN_ID_MIN'),
            'email_required' => __('api.common.EMAIL_REQUIRED'),
            'email_invalid' => __('api.common.EMAIL_INVALID'),
            'email_exists' => __('api.common.EMAIL_EXISTS'),
            'otp_required' => __('api.common.OTP_REQUIRED'),
            'otp_digits' => __('api.common.6_DIGITS_OTP'),
            'password_required' => __('api.common.PASSWORD_REQUIRED'),
            'password_min' => __('api.common.PASSWORD_MIN_LENGTH_ERROR'),
            'password_regex' => __('api.common.PASSWORD_REGEX'),
            'new_password_required' => __('api.common.NEW_PASSWORD_REQUIRED'),
            'new_password_min' => __('api.common.NEW_PASSWORD_MIN_LENGTH_ERROR'),
            'new_password_regex' => __('api.common.NEW_PASSWORD_REGEX'),
            'device_token_required' => __('api.common.DEVICE_TOKEN_REQUIRED'),
            'widget_order_required' => __('api.common.WIDGET_ORDER_REQUIRED'),
        ];
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
    * @OA\Post(
    *    path="/auth/login",
    *    tags={"Auth"},
    *    summary="Login",
    *    operationId="login",    
    *    @OA\Parameter(
    *        name="email",
    *        in="query",
    *        required=true,
    *        @OA\Schema(
    *            type="string",
    *            example="admin@gmail.com"
    *        )
    *    ),
    *    @OA\Parameter(
    *        name="password",
    *        in="query",
    *        required=true,
    *        @OA\Schema(
    *            type="string",
    *            example="123456"
    *        )
    *    ),
    *    @OA\Response(
    *        response=200,
    *        description="Success",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *        )
    *    ),
    *    @OA\Response(
    *        response=401,
    *        description="Unauthorized"
    *    ),
    *    @OA\Response(
    *        response=400,
    *        description="Invalid request"
    *    ),
    *    @OA\Response(
    *        response=403,
    *        description="Unauthorized Access"
    *    ),
    *    @OA\Response(
    *        response=404,
    *        description="not found"
    *    ),
    *   security={{ "apiAuth": {} }}   
    *)
    */
       
    public function login(Request $request)
    {
        try{
            $rules = [
                'email' => $this->rules->email,
                'password' => $this->rules->password,
            ];
            $customMessages = [
                'email.required'  => $this->message->email_required,
                'email.exists' => $this->message->email_exists,
                'password.required' => $this->message->password_required,
                'password.min' => $this->message->password_min,
                'password.regex'  => $this->message->password_regex,
            ];
            
            $params = $request->all();
            $validator = Validator::make($params, $rules, $customMessages);

            if ($validator->fails()) {
                $response = setResponse('VALIDATION_ERROR',$validator->errors());
            } else {
                $user = User::where('email',$request->email)->first();

                if($user){
                    $credentials = $request->only('email', 'password');
                    // $this->guard()->factory()->setTTL();
                
                    // $token  =  $this->guard()->attempt($credentials);
                    if ($token = $this->guard()->attempt($credentials)) {

                        $response = setResponse('SUCCESS', [__('api.notifications.LOGIN_SUCCESSFULL')], ['token' => $token]);
                    }else{
                        $response = setResponse('OTHER_ERROR', [__('api.notifications.LOGIN_FAILED')]);
                    }
                } else {
                    $response = setResponse('OTHER_ERROR', [__('api.auth.USER_NOT_EXIST')]);
                }
            }
        } catch (\Exception $e) {
            $response = setResponse('OTHER_ERROR', [$e->getMessage()]);
        }
        return send_json_response($request,$response);
    }
    
    /**
    * @OA\Get(
    *    path="/auth/logout",
    *    tags={"Auth"},
    *    summary="Logout",
    *    operationId="logout",   
    *    @OA\Response(
    *        response=200,
    *        description="Success",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *        )
    *    ),
    *    @OA\Response(
    *        response=401,
    *        description="Unauthorized"
    *    ),
    *    @OA\Response(
    *        response=400,
    *        description="Invalid request"
    *    ),
    *    @OA\Response(
    *        response=404,
    *        description="not found"
    *    ),
    *   security={{ "apiAuth": {} }}
    *)
    */ 

    /**  
    * Logged out (token expire)
    *  
    * @return \Illuminate\Http\JsonResponse
    */ 
    public function logout(Request $request)
    {
        try {
            // $this->auditUserLog('Logged out','Users');
            $this->guard()->logout();
            $response = General::setResponse('SUCCESS', [__('api.notifications.LOGOUT_SUCCESSFULL')]);
        } catch (\Exception $e) {
            $response = General::setResponse('OTHER_ERROR', [$e->getMessage()]);
        }
        return send_json_response($request,$response);
    } 

    /**  
    * Refresh a token
    *  
    * @return \Illuminate\Http\JsonResponse
    */ 
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh()); 
    }

}
