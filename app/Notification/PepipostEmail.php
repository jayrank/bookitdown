<?php

namespace App\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;

class PepipostEmail extends Controller
{
    public static function sendEmail()
    {
        $setting = Setting::first();
        $curl = curl_init();

        curl_setopt_array($curl, array(
              CURLOPT_URL => "https://emailapi.netcorecloud.net/v5/mail/send",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "{\"from\":{\"email\":\"confirmation@pepisandbox.com\",\"name\":\"Flight confirmation\"},\"subject\":\"Your Barcelona flight e-ticket : BCN2118050657714\",\"content\":[{\"type\":\"html\",\"value\":\"Hello Lionel, Your flight for Barcelona is confirmed.\"}],\"personalizations\":[{\"to\":[{\"email\":\"tjcloudtest@gmail.com\",\"name\":\"Lionel Messi\"}]}]}",
              CURLOPT_HTTPHEADER => array(
            "api_key: tjcloudtest_7fca42047a98651534c949a6f734cde1",
            "content-type: application/json"
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}