<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use function Pest\Laravel\json;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Subscription;

class PayMongoController extends Controller
{   

    public function paymongoPayment(Request $request){
        $promoId = $request->input('promo_id');
        // return response()->json(['success'=>false, 'promoId'=>$promoId]);
        $promos = Promo::where('promo_id', $promoId)->get();
        $promo_name = $promos[0]->name;
        $promo_price = $promos[0]->price * 100;
        $promo_quantity = 1;
        $cancelURL = 'http://127.0.0.1:8000/upgrade';
        $successURL = 'http://127.0.0.1:8000/testpay';
        // Now you can access the logged-in user's information
        $user = Auth::user();
        $userfirstName = $user->firstname;
        $usermiddleName = $user->middlename;
        $userlastName = $user->lastname;
        //error handling if the middle name is not null
        if($usermiddleName == null){
            $usermiddleName = '';
        }else{
            $usermiddleName = $usermiddleName.' ';
        }
        $userName = $userfirstName.' '.$usermiddleName.''.$userlastName;
        $userEmail = $user->email;
        

        // return response()->json(['success'=>false, 'promo_name'=>$promo_name, 'promo_price'=>$promo_price, 'promo_quantity'=>$promo_quantity, 'cancelURL'=>$cancelURL, 'successURL'=>$successURL, 'userName'=>$userName, 'userEmail'=>$userEmail]);
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
                                        'name' => $userName,
                                        'email' => $userEmail,
                                        'phone' => ''
                        ],
                        'send_email_receipt' => false,
                        'show_description' => true,
                        'show_line_items' => true,
                        'cancel_url' => $cancelURL,
                        'statement_descriptor' => $promo_name,
                        'description' => $promo_name,
                        'line_items' => [
                                        [
                                                                        'currency' => 'PHP',
                                                                        'images' => [
                                                                        asset('logo_icons/logo_head.png')
                                                                        ],
                                                                        'amount' => $promo_price,
                                                                        'description' => $promo_name,
                                                                        'name' => $promo_name,
                                                                        'quantity' => $promo_quantity
                                        ]
                        ],
                        'payment_method_types' => [
                                        'gcash'
                        ],
                        'reference_number' => 'testrefnum1',
                        'success_url' => $successURL
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
            return response()->json(['success' => false, 'message' => "cURL Error #:" . $err]);
        } else {
             $responseData = json_decode($response, true);
            if (isset($responseData['data']['attributes']['checkout_url'])) {
                $checkoutUrl = $responseData['data']['attributes']['checkout_url'];
                $checkoutId = $responseData['data']['id'];
                return response()->json(['success' => true, 'data' => json_encode(['checkout_url' => $checkoutUrl]), 'checkoutId'=> $checkoutId]);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to retrieve checkout URL.','response'=> $responseData]);
            }
        }


    }


    public function retrieve_payment(Request $request)
    {
        $checkoutId = $request->input('checkoutID');
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paymongo.com/v1/checkout_sessions/".$checkoutId,
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
            Log::error('cURL Error: ' . $err);
            return response()->json(['success' => false, 'message' => "cURL Error #:" . $err]);
        } else {
            $responseData = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON Decode Error: ' . json_last_error_msg());
                return response()->json(['success' => false, 'message' => 'JSON Decode Error: ' . json_last_error_msg()]);
            }

            if (!isset($responseData['data']['attributes']['payments'][0]['attributes']['status'])) {
                Log::error('Payment status not found in response', ['response' => $responseData]);
                return response()->json(['success' => false, 'message' => 'Payment status not found in response', 'response' => $responseData]);
            }

            $status = $responseData['data']['attributes']['payments'][0]['attributes']['status'];
            if ($status === 'paid') {
                // Payment was successful, add subscription
                $promoId = $request->input('promoID');// Retrieve promo_id from request
                $user = Auth::user();
                $startDate = Carbon::now();
                $promo = Promo::find($promoId);
                // return response()->json(['success' => false, 'promoId' => $promoId, 'user: ' . $user, 'startDate: ' . $startDate, 'promo: ' . $promo]);
                if (!$promo) {
                    Log::error('Promo not found with promo_id: ' . $promoId);
                    return response()->json(['success' => false, 'message' => 'Promo not found']);
                }

                $endDate = $startDate->copy()->addDays($promo->duration);
                
                // Cancel any active free trials for this user - note the capitalized 'Active' status
                $freeTrials = Subscription::where('user_id', $user->user_id)
                    ->where('status', 'Active')
                    ->where(function($query) {
                        $query->where('reference_number', 'like', '%Free Trial%')
                              ->orWhere('subscription_type', 'free');
                    })
                    ->get();
                    
                foreach ($freeTrials as $freeTrial) {
                    // Update using capitalized status value
                    $freeTrial->status = 'Cancelled';
                    $freeTrial->save();
                    
                    Log::info('Free trial cancelled due to paid subscription', [
                        'user_id' => $user->user_id,
                        'subscription_id' => $freeTrial->subscription_id,
                        'reference_number' => $freeTrial->reference_number
                    ]);
                }
                
                // Create new paid subscription with capitalized status
                $subscriptionData = [
                    'user_id' => $user->user_id,
                    'promo_id' => $promoId,
                    'reference_number' => $responseData['data']['id'],
                    'duration' => $promo->duration,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'Active',
                    'subscription_type' => 'paid',
                ];

                Subscription::create($subscriptionData);

                return response()->json(['success' => true, 'status' => $status]);
            } else {
                Log::info('Payment not successful', ['status' => $status]);
                return response()->json(['success' => false, 'message' => 'Payment not successful']);
            }
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
