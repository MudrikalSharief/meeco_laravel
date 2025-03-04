<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function Pest\Laravel\json;

class PayMongoController extends Controller
{
    
    public function paymongoPayment(){
        
        //====
        $curl = curl_init();

        curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paymongo.com/v1/checkout_sessions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'data' => [
                'attributes' => [
                        'billing' => [
                                        'name' => 'BomBilling',
                                        'email' => 'shariefkundo@gmail.com',
                                        'phone' => '09093392171'
                        ],
                        'send_email_receipt' => false,
                        'show_description' => true,
                        'show_line_items' => true,
                        'cancel_url' => 'http://127.0.0.1:8000/upgrade',
                        'statement_descriptor' => 'Davao',
                        'description' => 'You Are Subcribing to Bombom paw promo',
                        'line_items' => [
                                        [
                                                                        'currency' => 'PHP',
                                                                        'images' => [
                                                                        asset('logo_icons/logo_head.png')
                                                                        ],
                                                                        'amount' => 2000,
                                                                        'description' => 'Bombompaw unlimited',
                                                                        'name' => 'Bombompaw Promo',
                                                                        'quantity' => 1
                                        ]
                        ],
                        'payment_method_types' => [
                                        'gcash'
                        ],
                        'reference_number' => 'bombompaw1',
                        'success_url' => 'http://127.0.0.1:8000/capture'
                ]
            ]
        ]),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "accept: application/json",
            "authorization: Basic c2tfdGVzdF84bkNqNzNwYncxRG05MUZWd3lmbXd1MmM6"
        ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return response()->json(['success'=>true, 'data'=>$response]);
        }


    }


    public function retrieve_payment(){
        $curl = curl_init();

        curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paymongo.com/v1/checkout_sessions/cs_C9abBhiy4yMWGqsTm721xvDY",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Basic c2tfdGVzdF84bkNqNzNwYncxRG05MUZWd3lmbXd1MmM6"
        ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
        echo $response;
        }
    }

    public function expire_a_payment(){

        $curl = curl_init();

        curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paymongo.com/v1/checkout_sessions/cs_ikAXKwscLLdzchfGayZ5yybs/expire",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Basic c2tfdGVzdF84bkNqNzNwYncxRG05MUZWd3lmbXd1MmM6"
        ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
        echo $response;
        }
    }

}
