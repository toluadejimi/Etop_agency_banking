<?php


use App\Models\User;
use App\Models\Terminal;
use App\Models\TidConfig;
use App\Models\VirtualAccount;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
use App\Models\OauthAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;




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

        $response = Http::post('https://api.telegram.org/bot6140179825:AAGfAmHK6JQTLegsdpnaklnhBZ4qA1m2c64/sendMessage?chat_id=1316552414', [
            'chat_id' => "1316552414",
            'text' => $message,

        ]);
        $responseData = $response->json();


    }
}


http://102.216.128.75:9090/identity/api/v1/authenticate
if (!function_exists('psb_token')) {

    function psb_token()
    {

        $publickey = env('9PSBPUBKEY');
        $privatekey = env('9PSBPRIKEY');
        $Url = env('9PSBURL');



        $curl = curl_init();
        $data = array(
            'publickey' => $publickey,
            'privatekey' => $privatekey,

        );
        $post_data = json_encode($data);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/authenticate",
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
            $message = "Error from 9psb ========> \n\n"."Response ======> $res;
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
                'amounttype' => "EXACT"
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
            CURLOPT_URL => "$Url/create",
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
        }return [];
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


