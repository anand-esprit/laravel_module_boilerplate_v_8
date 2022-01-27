<?php
namespace App\Traits;

use Illuminate\Support\Facades\Mail;

use Auth; 
use DB;
use Validator;
use Carbon\Carbon;
use Exception;

trait EmailPoolTrait {

    public function email_date_format($date, $EMAIL_DATE_FORMAT = 'd-m-Y'){
        if($date){
            $date = Carbon::parse($date)->format($EMAIL_DATE_FORMAT);
        } else {
            $date = 'N/A';
        }

        return $date;
    }

    public function sendPushNotification($payload, $device_token) {
        // print_r($payload->fcm_data->route);exit;
        $title = config('CONSTANT.NOTIFICATION.TEXT.'.$payload->type_id);

        $message = $payload->message;
        $click_action = $payload->fcm_data->route;
        $fcmFields = [
            'priority' => 'high', 
            'notification' => [
                "title" => $title,
                'sound' => 'default',
                "body" => $message,
                "click_action" => $click_action,
            ],
            'data' => $payload->fcm_data,
        ];

        if(is_array($device_token)){
            $fcmFields["registration_ids"] = $device_token;
        } else {
            $fcmFields["to"] = $device_token;
        }

        $url = 'https://fcm.googleapis.com/fcm/send';
        $server_key = env('FCM_SERVER_KEY');
        $headers    = array('Content-Type:application/json', 'Authorization:key='.$server_key);
        $ch = curl_init();
        // print_r($fcmFields); die;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
        $result = curl_exec($ch);
        // error_log("Request \n user : " . json_encode($user) ." \n info :" . json_encode($fcmFields), 3, "notifications.log");
        // error_log("\nResponse \n".$result."\n", 3, "notifications.log");
        // error_log("\n==============================================\n", 3, "notifications.log");
        curl_close($ch);
        if ($result === FALSE) { 
            return 'Oops! FCM Send Error: '.curl_error($ch); 
        } else { 
            $payload->update(['fcm_status' => 1]);
            return $result;
        }
    }

}     