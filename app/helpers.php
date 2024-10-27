<?php


use App\Models\Dyaccount;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Terminal;
use App\Models\Webaccount;
use App\Models\TidConfig;
use App\Models\VirtualAccount;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Passport;
use App\Models\OauthAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


function getPaginate($paginate = 20)
{
    return $paginate;
}

function paginateLinks($data)
{
    return $data->appends(request()->all())->links();
}

if (!function_exists('error_response')) {

    function error_response($message)
    {
        return response()->json([
            'success' => false,
            'terminal' => null,
            'terminals' => null,
            'error' => $message,
        ], 200);
    }
}

if (!function_exists('error_pin_response')) {

    function error_pin_response($message)
    {
        return response()->json([
            'success' => false,
            'error' => $message,
        ], 200);
    }
}

if (!function_exists('error_pin_response')) {

    function error_pin_response($message)
    {
        return response()->json([
            'success' => false,
            'error' => $message,
        ], 200);
    }
}

if (!function_exists('wallet_balance')) {

    function wallet_balance()
    {

        $Url = env('9PSTRANSFERURL');
        $token = psb_token();
        $Url = env('9PSTRANSFERURL');
        $url = $Url."/merchant/account/balanceenquiry";
        $account = env('DEBITACCOUNT');



        $data = array(
                'account' => [
                   'accountnumber'=> $account
            ]
        );

        $post_data = json_encode($data);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ),
        ));

        $var = curl_exec($curl);

        //dd($var, $url);

        curl_close($curl);
        $var = json_decode($var);






        $bal = $var->account->accountbalance ?? 0;



        return $bal;
    }



}

if (!function_exists('settlement')) {

    function settlement()
    {

        $Url = env('9PSTRANSFERURL');
        $token = psb_token();
        $Url = env('9PSTRANSFERURL');
        $url = $Url."/merchant/account/balanceenquiry";
        $account = env('INSTANTACCOUNT');



        $data = array(
            'account' => [
                'accountnumber'=> $account
            ]
        );

        $post_data = json_encode($data);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ),
        ));

        $var = curl_exec($curl);

        //dd($var, $url);

        curl_close($curl);
        $var = json_decode($var);

        $bal = $var->account->accountbalance ?? 0;
        return $bal;
    }



}

if (!function_exists('user_balance')) {

    function user_balance($SerialNo)
    {
        $balance = Terminal::where('serialNumber', $SerialNo)->first()->accountBalance ?? null;
        return $balance;
    }
}

if (!function_exists('send_notification')) {

    function send_notification($message)
    {

        $boturl = env('BOTURL');
        $chat_id = env('BOTID');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $boturl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'chat_id' => $chat_id,
                'text' => $message,

            ),
            CURLOPT_HTTPHEADER => array(),
        ));

        $var = curl_exec($curl);
        curl_close($curl);

        $var = json_decode($var);
    }



    if (!function_exists('create_p_account')) {

        function create_p_account($name, $bvn)
        {

            $curl = curl_init();
            $data = array(
                "account_name" => $name,
                "bvn" => $bvn,
            );

            $databody = json_encode($data);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://vps.providusbank.com/vps/api/appdevapi/api/PiPCreateReservedAccountNumber',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $databody,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Client-Id: dGVzdF9Qcm92aWR1cw==',
                    'X-Auth-Signature: b900d355dd66f3507c775ba52bcd3ba6b6f3f4093448ea24f3aa6500bbbce5c1e63c12214acd08d8057b7bec36d37a8f66a504a1b7a8df54af00ba6ba825a9c4',
                ),
            ));

            $var = curl_exec($curl);

            curl_close($curl);
            $var = json_decode($var);

            // dd($var);

            $status = $var->responseCode ?? null;
            $p_acct_no = $var->account_number ?? null;
            $p_acct_name = $var->account_name ?? null;

            $pbank = "PROVIDUS BANK";

            if ($status == 00) {

                $create = new VirtualAccount();
                $create->v_account_no = $p_acct_no;
                $create->v_account_name = $p_acct_name;
                $create->v_bank_name = $pbank;
                $create->save();

                $user = User::find(Auth::id());
                $user->p_account_no = $p_acct_no;
                $user->p_account_name = $p_acct_name;
                $user->save();


                return response()->json(['account_no' => $p_acct_no,  'account_name' => $p_acct_name]);

                $message = "Account Created on Providus";
                send_notification($message);
            }


            $message = "Error from Providus Account Creation | Account Created on Providus";
            send_notification($message);
        }
    }





    if (!function_exists('create_dynamic_p_account')) {

        function create_dynamic_p_account($name, $business_id)
        {


            $client_id = env('CLIENTID');
            $hashkey = env('HASHKEY');


            $curl = curl_init();
            $data = array(
                "account_name" => $name,
            );

            $databody = json_encode($data);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://vps.providusbank.com/vps/api/PiPCreateDynamicAccountNumber',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $databody,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    "Client-Id: $client_id",
                    "X-Auth-Signature: $hashkey",
                ),
            ));

            $var = curl_exec($curl);

            curl_close($curl);
            $var = json_decode($var);


            $status = $var->responseCode ?? null;
            $p_acct_no = $var->account_number ?? null;
            $p_acct_name = $var->account_name ?? null;


            $pbank = "PROVIDUS BANK";

            $usr = User::where('business_id', $business_id)->first();

            if ($status == 00) {

                $create = new VirtualAccount();
                $create->v_account_no = $p_acct_no;
                $create->v_account_name = $p_acct_name;
                $create->v_bank_name = $pbank;
                $create->business_id = $business_id ?? null;
                $create->save();

                // $user = User::find(Auth::id());
                // $user->p_account_no = $p_acct_no;
                // $user->p_account_name = $p_acct_name;
                // $user->save();

                $message = "Account Created on Providus";
                send_notification($message);


                $data_array = array();
                $data_array[0] = [
                    "account_no" => $p_acct_no,
                    "amount_name" => $p_acct_name,
                ];


                return $data_array;
            }


            $message = "Error from Providus Account Creation | Account Created on Providus";
            send_notification($message);
        }
    }
}

if (!function_exists('send_notification3')) {

    function send_notification3($message)
    {

        $response = Http::post('https://api.telegram.org/bot6952129107:AAHKz-1BFleGnGRZE9ro-Bb2poUQI5QYTxs/sendMessage?chat_id=6968158861', [
            'chat_id' => "6968158861",
            'text' => $message,

        ]);
        $responseData = $response->json();



    }
}

if (!function_exists('send_notification2')) {

    function send_notification2($message)
    {

        $response = Http::post('https://api.telegram.org/bot7494514558:AAFzCBij1k8MVhJ5FckqpdKrGwvMyVL0fsw/sendMessage?chat_id=1630013186', [
            'chat_id' => "1630013186",
            'text' => $message,

        ]);
        $responseData = $response->json();


    }
}

if (!function_exists('psb_token')) {

    function psb_token()
    {

        $publickey = env('9PSBPUBKEY');
        $privatekey = env('9PSBPRIKEY');
        $Url = env('9PSBURL');
        $url = $Url."/merchant/virtualaccount/authenticate";



        $curl = curl_init();
        $data = array(
            'publickey' => $publickey,
            'privatekey' => $privatekey,

        );
        $post_data = json_encode($data);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);



        $access_token = $var->access_token ?? null;



        return $access_token;
    }
}

if (!function_exists('psb_vas_token')) {

    function psb_vas_token()
    {


        $username = env('9PSBVASUSERNAME');
        $password = env('9PSBVASPASSWORD');
        $Url = env('9PSBVASAUTHURL');


        $response = Http::post($Url, [
            'username' => $username,
            'password' => $password,
        ]);
        $responseData = $response->json();
        $status = $responseData['responseCode'] ?? null;



        if ($status == 200) {
            $responseData = $response->json();
            $token = $responseData['data']['accessToken'];
            return $token;


        } else {

            $res = json_encode($responseData);
            $message = "Error from 9psb ========> \n\n"."Response ======> $res";
            send_notification($message);

            return 0;
        }





    }
}

if (!function_exists('reference')) {

    function reference(){

        $ref = date('ymdhis').random_int(0000000, 99999999);
        return $ref;

    }

}

if (!function_exists('create_9psb_v_account')) {

    function create_9psb_v_account()
    {

        $Url = env('9PSBURL');
        $token = psb_token();
        $curl = curl_init();
        $data = array(

            'transaction' => [
                'reference' => reference()
            ],

            'order' => [
                'amount' => 1,
                'currency' => "NGN",
                'description' => "Test TRF",
                'country' => "NGA",
                'amounttype' => "ANY"
            ],

            'customer' => [

                'account' => [
                    'name' => Auth::user()->first_name." ".Auth::user()->last_name,
                    'type' => "STATIC"
                ]

            ],


        );
        $post_data = json_encode($data);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/merchant/virtualaccount/create",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);
        $status = $var->message ?? null;

        if($status == "Success"){

            $ver = new VirtualAccount();
            $ver->v_account_no = $var->customer->account->number;
            $ver->user_id = Auth::id();
            $ver->v_account_name = $var->customer->account->name;
            $ver->v_bank_name = $var->customer->account->bank;
            $ver->save();

            return 2;

        }

        $message = $var;
        send_notification($message);

        return 0;
    }
}

if (!function_exists('create_9psb_v_account_dymamic')) {

    function create_9psb_v_account_dymamic($user_id, $description, $name, $amount)
    {

        $current_time = new DateTime();
        $current_time->setTimezone(new DateTimeZone('Europe/London')); // +01:00 as per your example
        $formatted_date = $current_time->format('Y-m-d\TH:i:s.uP');

        $hour = 1.;
        $zero = 0;


        //dd( $current_time, $current_time, $formatted_date);

        $Url = env('9PSBURL');
        $token = psb_token();
        $curl = curl_init();
        $data = array(

            'transaction' => [
                'reference' => reference()
            ],

            'order' => [
                'amount' => $amount,
                'currency' => "NGN",
                'description' => $description,
                'country' => "NGA",
                'amounttype' => "EXACT"
            ],

            'customer' => [
                'account' => [
                    'name' => $name,
                    'type' => "DYNAMIC",
                    'expiry' => [
                    'hours' => 1,
                    'date'=> null
                    ],
                ],


            ],


        );


        $post_data = json_encode($data);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/merchant/virtualaccount/create",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);
        $status = $var->message ?? null;


        if($status == "Success"){

            $ver = new Webaccount();
            $ver->v_account_no = $var->customer->account->number;
            $ver->user_id = $user_id;
            $ver->v_account_name = $var->customer->account->name;
            $ver->v_bank_name = $var->customer->account->bank;
            $ver->amount = $amount;

            $ver->save();

            $data['account_no'] = $var->customer->account->number;
            $data['account_name'] = $var->customer->account->name;
            $data['user_id'] = $user_id;
            $data['name'] = $var->customer->account->name;

            return $data;

        }

        $data['9psb'] = $var;
        $data['request'] = $post_data;

        $message = $data;
        send_notification($message);

        return $data;
    }
}


if (!function_exists('login')) {

    function login($deviceIdentifier, $phone, $deviceName, $password, $email, $device_id)
    {



        if($phone != null){

            $credentials = (['phone'=> $phone, 'password'=> $password]);
            Passport::tokensExpireIn(Carbon::now()->addMinutes(20));
            Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(20));




            $check_status = User::where('phone', $phone)->first()->status ?? null;



            if ($check_status == 3) {

                return 3;
            }




            if (!auth()->attempt($credentials)) {

                return 0;
            }


            $get_token = OauthAccessToken::where('user_id', Auth::id())->first()->user_id ?? null;

            if ($get_token != null) {
                OauthAccessToken::where('user_id', Auth::id())->delete();
            }


            $get_device_id = Auth::user()->device_id ?? null;
            $get_deviceIdentifier = Auth::user()->deviceIdentifier ?? null;
            $get_deviceName = Auth::user()->deviceName ?? null;
            $get_ip = Auth::user()->ip_address ?? null;


            $get_device_id = User::where('device_id', $device_id)
                ->first()->device_id ?? null;

            if ($get_device_id == null) {

                $update = User::where('id', Auth::id())
                    ->update([
                        'device_id' => $device_id ?? null,
                    ]);
            }

            if ($get_deviceName == null) {

                $update = User::where('id', Auth::id())
                    ->update([
                        'deviceName' => $deviceName ?? null,
                    ]);
            }

            if ($get_deviceIdentifier == null) {

                $update = User::where('id', Auth::id())
                    ->update([
                        'deviceIdentifier' => $deviceIdentifier ?? null,
                    ]);
            }




            if (Auth::user()->status == 5) {

                return 1;
            }


            return 2;


        }
        if($email != null){



            $credentials = (['email'=> $email, 'password'=> $password]);
            Passport::tokensExpireIn(Carbon::now()->addMinutes(20));
            Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(20));

            $check_status = User::where('email', $email)->first()->status ?? null;

            if ($check_status == 3) {

                return 3;
            }

            if (!auth()->attempt($credentials)) {

                return 0;
            }


            $get_token = OauthAccessToken::where('user_id', Auth::id())->first()->user_id ?? null;

            if ($get_token != null) {
                OauthAccessToken::where('user_id', Auth::id())->delete();
            }


            $get_device_id = Auth::user()->device_id ?? null;
            $get_deviceIdentifier = Auth::user()->deviceIdentifier ?? null;
            $get_deviceName = Auth::user()->deviceName ?? null;
            $get_ip = Auth::user()->ip_address ?? null;


            $get_device_id = User::where('device_id', $device_id)
                ->first()->device_id ?? null;

            if ($get_device_id == null) {

                $update = User::where('id', Auth::id())
                    ->update([
                        'device_id' => $device_id ?? null,
                    ]);
            }

            if ($get_deviceName == null) {

                $update = User::where('id', Auth::id())
                    ->update([
                        'deviceName' => $deviceName ?? null,
                    ]);
            }

            if ($get_deviceIdentifier == null) {

                $update = User::where('id', Auth::id())
                    ->update([
                        'deviceIdentifier' => $deviceIdentifier ?? null,
                    ]);
            }




            if (Auth::user()->status == 5) {

                return 1;
            }


            return 2;


        }
        return 7;

    }
}


if (!function_exists('virtual_account')) {

    function virtual_account()
    {
        $account = VirtualAccount::where('user_id', Auth::id())->get() ?? null;
        if ($account !== null) {
            foreach ($account as $item) {
                $account_array[] = array(
                    "bank_name" => $item['v_bank_name'],
                    "account_no" => $item['v_account_no'],
                    "account_name" => $item['v_account_name'],
                );
            }
            return $account_array ?? [];
        }
        return [];
    }
}


if (!function_exists('terminal_info')) {
    function terminal_info()
    {
        $tm = Terminal::select('merchantNo', 'terminalNo', 'merchantName', 'deviceSN')->where('user_id', Auth::id())->first() ?? null;
        if ($tm != null) {
            return $tm;
        }
        return $tm;
    }
}


if (!function_exists('tid_config')) {
    function tid_config()
    {
        $tm = TidConfig::select('ip', 'port', 'ssl', 'compKey1', 'compKey2', 'baseUrl', 'logoUrl')->where('user_id', Auth::id())->first() ?? null;
        if ($tm != null) {
            return $tm;
        }

        return (object)[];
    }
}


if (!function_exists('select_account')) {

    function select_account()
    {



        $account = User::where('id', Auth::id())->first();


        $account_array = array();
        $account_array[0] = [
            "title" => "Main Account",
            "amount" => $account->main_wallet,
            "key" => "main_account",

        ];
//        $account_array[1] = [
//            "title" => "Bonus Account",
//            "amount" => $account->bonus_wallet,
//            "key" => "bonus_account",
//        ];

        return $account_array;
    }

}

if (!function_exists('send_api_notification')) {

        function send_api_notification($sessionid, $receiver_account_number, $amount)
        {

            try {

                $curl = curl_init();
                $data = array(

                    'sessionid' => $sessionid,
                    'receiver_account_number' => $receiver_account_number,
                    'amount' => $amount,

                );
                $post_data = json_encode($data);

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://web.sprintpay.online/api/e-payment',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $post_data,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));

                $var = curl_exec($curl);
                curl_close($curl);


            } catch (QueryException $e) {
                echo "$e";
            }
        }




    }

if (!function_exists('revesal')) {

    function revesal($ref,$amount)
    {

        try {

            $Url = env('9PSTRANSFERURL');
            $token = psb_token();

            $curl = curl_init();
            $data = array(

                'transaction' => [
                    'reference' => $ref,
                    'amount' => $amount
                ]

            );
            $post_data = json_encode($data);

            curl_setopt_array($curl, array(
                //https://baas.9psb.com.ng/ipaymw-api/v1
                CURLOPT_URL => "https://baas.9psb.com.ng/ipaymw-api/v1/merchant/account/transfer/reversalstatus",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    "Authorization: Bearer $token"
                ),
            ));

            $var = curl_exec($curl);
            curl_close($curl);

            $message['data'] = $var;
            $message['token'] = $token;

            Log::info('Reversal', ['message' => $message]);
            //send_notification($message);


            $code = $var->code ?? null;
            $message2 = $var->message ?? null;

            if($code == "46"){
                $message = "ETOP REVESAL Transaction Failed and its been reversed";
                send_notification($message);
                return 0;
            }else{

                $message = "ETOP REVESAL =>>>>".$message2;
                //send_notification($message);

                return 1;
            }



        } catch (QueryException $e) {
            echo "$e";
        }
    }

}

