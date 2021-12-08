<?php

namespace App\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;

class TelnyxSms extends Controller
{
    public static function sendTelnyxSms($to, $from, $body)
    {
        $setting = Setting::first();
		
		$toNumber = str_replace(" ","",$to);
		$toNumber = str_replace("-","",$toNumber);
		$toNumber = str_replace("+","",$toNumber);
		
        $to = "+".$toNumber;
        $from = "+".$from;
        
        $data_array =  array(
           "from" => $from,
           "to" => $to,
           "text" => $body,
           "webhook_url" => "https://schedulethat.tjcg.in/Telnyx_log"
        );
        
        $data = json_encode($data_array);
        
        $url = "https://api.telnyx.com/v2/messages";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$setting->telnyx_api_key,
        ));
        
        $result = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($result);
        return $response; 
    }
}