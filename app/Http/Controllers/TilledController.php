<?php

namespace App\Http\Controllers;

use App\JsonReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TilledController extends Controller
{
    public function index(){
        return view('tilled/index');
    }
    public function submitTilled($tilledAccountId){
        // $obj = new \stdClass();
        // $obj->amount = 1;
        // $obj->currency = 'usd';
        // $obj->payment_method_types = ['card'];
        $response = Http::withHeaders([
            'Content-Type'=> 'application/json',
            'Authorization' => 'Bearer sk_KxrZ7VPExlAt1M5k4r2irGTM1Bp7U025Amn1L5F0nKOb8KgRPcBmtNjttstCFMGPvpJFPmGpEd5oaxkqUMUcoPDNdx9JhIZm6Bqe',
            'Tilled-Account'=> $tilledAccountId,
        ])->post('https://sandbox-api.tilled.com/v1/payment-intents', [
            "amount"=> 100,
            "currency"=> 'usd',
            "payment_method_types"=> ['card','ach_debit'],
        ]);
        $responseBody = $response->json();
        $data['client_secret'] = $responseBody['client_secret'];
        return JsonReturn::success($data);
    }
}
