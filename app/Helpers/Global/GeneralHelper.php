<?php

if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if(!function_exists('setResponse'))
{
    function setResponse($type, $message=[], $result = [])
    {
        switch (strtoupper($type)) {
            case 'SUCCESS':
                $code = 200;
                $default_message['NOTIFICATION'] = $message;
                break;
            case 'AUTH_FAILED':
                $code = 401;
                $default_message['NOTIFICATION'] = [__('api.notifications.AUTH_FAILED')];
                break;
            case 'SESSION_EXPIRED':
                $code = 401;
                $default_message['NOTIFICATION'] = [__('api.notifications.SESSION_EXPIRED')];
                break;
            case 'INVALID_API_KEY':
                $code = 403;
                $default_message['NOTIFICATION'] = [__('api.notifications.INVALID_API_KEY')];
                $result = empty($result) ? ['STATUS_KEY' => 'INVALID_API_KEY'] : $result;
                break;
            case 'INVALID_PLATFORM':
                $code = 403;
                $default_message['NOTIFICATION'] = [__('api.notifications.INVALID_PLATFORM')];
                $result = empty($result) ? ['STATUS_KEY' => 'INVALID_PLATFORM'] : $result;
                break;
            case 'LICENSE_EXPIRED':
                $code = 403;
                $default_message['NOTIFICATION'] = [__('api.notifications.LICENSE_EXPIRED')];
                $result = empty($result) ? ['STATUS_KEY' => 'LICENSE_EXPIRED'] : $result;
                break;
            case 'INVALID_IP':
                $code = 403;
                $default_message['NOTIFICATION'] = [__('api.notifications.INVALID_IP')];
                $result = empty($result) ? ['STATUS_KEY' => 'INVALID_IP'] : $result;
                break;
            case 'INVALID_URL':
                $code = 404;
                $default_message['NOTIFICATION'] = [__('api.notifications.INVALID_URL')];
                break;
            case 'VALIDATION_ERROR':
                $code = 422;
                $default_message = $message;
                break;
            case 'ACCOUNT_DISABLED':
                $code = 423;
                $default_message['NOTIFICATION'] = [__('api.notifications.ACCOUNT_DISABLED')];
                break;
            case 'LOGIN_FAILED':
                $code = 423;
                $default_message['NOTIFICATION'] = [__('api.notifications.LOGIN_FAILED')];
                break;
            case 'INVALID_COMPANY':
                $code = 423;
                $default_message['NOTIFICATION'] = [__('api.notifications.INVALID_COMPANY')];
                break;
            case 'OTHER_ERROR':
                $code = 423;
                $default_message['NOTIFICATION'] = $message;
                break;            
            case 'EXCEPTION':
                $code = '';
                $default_message['NOTIFICATION'] = $message;
                break;
            default:
                break;
        }

        $data = [];
        $data['STATUS'] = $default_message;
        if(!empty($result)){
            $data = array_merge($data,$result);
        }
        return ['data' => $data,'code' => $code];
    }
}

if(!function_exists('send_json_response'))
{
    function send_json_response($request, $response)
    {
        addLog($request, $response);
        return response()->json($response['data'],$response['code']);
        // return response($response, $code);
    }
}

if (! function_exists('hoursandmins')) {
    function hoursandmins($time, $format = '%02d:%02d')
    {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }
}

if(!function_exists('addLog')){
    function addLog($request = '', $response = '')
    {
        $cyd    = date('Y/m');
        $cf     = 'api-log/'.$cyd;
        $fname  = $cf.'/logs-'.date('Y-m-d').'.html';
        if(!is_dir($cf)) {  @mkdir($cf, 0777, true); }
        if(!file_exists($fname)){
            $html="<!DOCTYPE html>
                    <html>
                        <head>
                            <meta name='viewport' content='width=device-width, initial-scale=1'>
                            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css'>
                            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
                            <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js'></script>
                            <style>
                                body{ font-size:16px; }
                                .main_div { background: #efefef;border-bottom: 4px solid #D32121;box-shadow: 0 -1px 2px #B89595; }
                                .request_div { background:#93E0EA;padding:10px 10px; }
                                .req_span{ background: #93EAA8; }
                                .req_para { background: #93E0EA;margin-top:10px;margin-bottom:5px; overflow-wrap: break-word;}
                                .res{ background: #93EAA8;padding: 10px; overflow-wrap: break-word;}
                            </style>
                        </head>
                        <body>
                            <div class='container-flude'>
                            </div>
                        </body>
                    </html>";
            file_put_contents($fname, $html.PHP_EOL , FILE_APPEND | LOCK_EX);
        }
        $lines = array();
        $html1='
            <br>
            <div class="main_div">
                <div class="request_div">
                    <b>Request :</b>
                    <span class="req_span">'.$request->method().' Method</span>
                    <b> Time </b> : <span class="req_span">'.date('Y-m-d H:i:s').'</span>
                    <b>URL</b> :  <span class="req_span">'.$request->url().'</span>
                    <div class="req_para"><b>Request Headers</b> ------- : '.json_encode(collect($request->header())->toArray()).'</div>
                    <div class="req_para"><b>GET Request</b> ------- : '.json_encode($request->query()).'</div>
                    <div class="req_para"><b>POST Request</b> ------- : '.json_encode($request->post()).'</div>
                </div>
                <br>
                <div class="res">
                    <b>Json Response </b> -------<br>'.json_encode($response).'
                </div>
            </div>';
        $html1.="\n";
        foreach(file($fname) as $line) {
            array_push($lines, $line);
            if(strpos($line, "<div class='container-flude'>") !== FALSE){ array_push($lines, $html1); }
        }
        $myfile = file_put_contents($fname, $lines);
    }
}