<?php

namespace App\Http\Controllers\Agents;

use App\Models\OauthAccessToken;
use App\Models\User;
use App\Models\Feature;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class AuthController extends Controller
{


    public function phone_login(Request $request)
    {
        if ($request->phone == null || $request->password == null) {

            return response()->json([
                'status' => false,
                'message' => "Phone or password can not be null"
            ], 500);

        }


        if ($request->phone != null) {


            $phone = $request->phone;
            $password = $request->password;
            $email = $request->email;
            $device_id = $request->device_id;
            $deviceName = $request->deviceName;
            $deviceIdentifier = $request->deviceIdentifier;



            $login = login($deviceIdentifier, $phone, $deviceName, $password, $email, $device_id);

            if ($login == 3) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account has restricted on ENKPAY',
                ], 500);
            }

            if ($login == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone No or Password Incorrect'
                ], 500);
            }

            if ($login == 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'You can not login at the moment, Please contact  support',

                ], 500);
            }

            if ($login == 2) {

                $feature = Feature::where('id', 1)->first();
                $token = auth()->user()->createToken('API Token')->accessToken;
                $virtual_account = virtual_account();

                $user = Auth()->user();
                $user['token'] = $token;
                $user['user_virtual_account_list'] = $virtual_account;
                $user['terminal_info'] = terminal_info();
                $tid_config = tid_config();



                $is_kyc_verified = Auth::user()->is_kyc_verified;
                $status = Auth::user()->status;
                $is_phone_verified = Auth::user()->is_phone_verified;
                $is_email_verified = Auth::user()->is_email_verified;
                $is_identification_verified = Auth::user()->is_identification_verified;


                if ($status !== 2 && $is_kyc_verified == 1 && $is_phone_verified == 1 && $is_email_verified == 1 && $is_identification_verified == 1) {

                    $update = User::where('id', Auth::id())
                        ->update([
                            'status' => 2
                        ]);
                }

                $setting = Setting::select('google_url', 'ios_url', 'version')
                    ->first();


                return response()->json([
                    'status' => true,
                    'data' => $user,
                    'permission' => $feature,
                    'isNewDevice' => false,
                    'setting' => $setting,
                    'tid_config' => $tid_config,

                ], 200);
            }



        }



        return response()->json([
            'status' => false,
            'message' => "Something went wrong"
        ], 500);



    }


    public function pin_login(Request $request)
    {


        $email = $request->email ?? null;
        $phone = $request->phone ?? null;
        $pin = $request->pin ?? null;
        $password = $request->password ?? null;



        if($email != null){

            if($request->pin  == null) {

                return response()->json([
                    'status' => $this->failed,
                    'message' => "Pin can not be empty"
                ], 500);

            }

            if($request->password  == null) {

                return response()->json([
                    'status' => $this->failed,
                    'message' => "Password can not be empty"
                ], 500);

            }

            if($request->email  == null) {

                return response()->json([
                    'status' => $this->failed,
                    'message' => "Phone or email can not be empty"
                ], 500);

            }


            $credentials = request(['email', 'password']);
            Passport::tokensExpireIn(Carbon::now()->addMinutes(20));
            Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(20));
            $check_status = User::where('email', $email)->first()->status ?? null;

            $get_pin = User::where('email', $email)->first()->pin;
            if (Hash::check($pin, $get_pin) == false) {

                return response()->json([
                    'status' => $this->failed,
                    'message' => "Incorrect Pin \n\n Please try again!"
                ], 500);
            }

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'status' => $this->failed,
                    'message' => "Incorrect Pin \n\n Please try again!"
                ], 500);
            }

            $user = User::where('email', $email)->first();

            if ($user->status == 5) {


                return response()->json([

                    'status' => $this->failed,
                    'message' => 'You can not login at the moment, Please contact  support',

                ], 500);
            }

            $get_token = OauthAccessToken::where('user_id', $user->id)->first()->user_id ?? null;

            if($get_token != null){
                OauthAccessToken::where('user_id', $user->id)->delete();
            }


            $feature = Feature::where('id', 1)->first();
            $token = auth()->user()->createToken('API Token')->accessToken;
            $virtual_account = virtual_account();

            $user = Auth()->user();
            $user['token'] = $token;
            $user['user_virtual_account_list'] = $virtual_account;
            $user['terminal_info'] = terminal_info();
            $tid_config = tid_config();


            $setting = Setting::select('google_url', 'ios_url', 'version')
                ->first();



            return response()->json([
                'status' => true,
                'data' => $user,
                'permission' => $feature,
                'isNewDevice' => false,
                'setting' => $setting,
                'tid_config' => $tid_config,

            ], 200);



        }

        if($phone != null){


            if($request->pin  == null) {

                return response()->json([
                    'status' => $this->failed,
                    'message' => "Pin can not be empty"
                ], 500);

            }


            if($request->password  == null) {

                return response()->json([
                    'status' => $this->failed,
                    'message' => "Password can not be empty"
                ], 500);

            }


            $credentials = request(['phone', 'password']);
            Passport::tokensExpireIn(Carbon::now()->addMinutes(20));
            Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(20));
            $check_status = User::where('phone', $phone)->first()->status ?? null;

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'status' => $this->failed,
                    'message' => "Incorrect Pin \n\n Please try again!"
                ], 500);
            }


            $get_pin = User::where('phone', $phone)->first()->pin;
            if (Hash::check($pin, $get_pin) == false) {

                return response()->json([
                    'status' => $this->failed,
                    'message' => "Incorrect Pin \n\n Please try again!"
                ], 500);
            }

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'status' => $this->failed,
                    'message' => "pIncorrect Pin \n\n Please try again!"
                ], 500);
            }

            if (Auth::user()->status == 5) {


                return response()->json([

                    'status' => $this->failed,
                    'message' => 'You can not login at the moment, Please contact  support',

                ], 500);
            }

            $get_token = OauthAccessToken::where('user_id', Auth::id())->first()->user_id ?? null;

            if($get_token != null){
                OauthAccessToken::where('user_id', Auth::id())->delete();
            }



            $feature = Feature::where('id', 1)->first();
            $token = auth()->user()->createToken('API Token')->accessToken;
            $virtual_account = virtual_account();

            $user = Auth()->user();
            $user['token'] = $token;
            $user['user_virtual_account_list'] = $virtual_account;
            $user['terminal_info'] = terminal_info();
            $tid_config = tid_config();



            $is_kyc_verified = Auth::user()->is_kyc_verified;
            $status = Auth::user()->status;
            $is_phone_verified = Auth::user()->is_phone_verified;
            $is_email_verified = Auth::user()->is_email_verified;
            $is_identification_verified = Auth::user()->is_identification_verified;


            $setting = Setting::select('google_url', 'ios_url', 'version')
                ->first();



            return response()->json([
                'status' => $this->success,
                'data' => $user,
                'permission' => $feature,
                'isNewDevice' => false,
                'setting' => $setting,
                'tid_config' => $tid_config,

            ], 200);


        }

        if($request->pin  == null) {

            return response()->json([
                'status' => $this->failed,
                'message' => "Pin can not be empty"
            ], 500);

        }


        if($request->password  == null) {

            return response()->json([
                'status' => $this->failed,
                'message' => "Password can not be empty"
            ], 500);

        }

        if($request->email  == null || $request->phone  == null) {

            return response()->json([
                'status' => $this->failed,
                'message' => "Phone or email can not be empty"
            ], 500);

        }

    }



    public function email_login(Request $request)
    {

        if ($request->email == null || $request->password == null) {

            return response()->json([
                'status' => false,
                'message' => "Email or password can not be null"
            ], 500);

        }

    if ($request->email != null) {



        $phone = $request->phone;
        $password = $request->password;
        $email = $request->email;
        $device_id = $request->device_id;
        $deviceName = $request->deviceName;
        $deviceIdentifier = $request->deviceIdentifier;



        $login = login($deviceIdentifier, $phone, $deviceName, $password, $email, $device_id);

        if ($login == 3) {
            return response()->json([
                'status' => false,
                'message' => 'Your account has restricted on ENKPAY',
            ], 500);
        }

        if ($login == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Email or Password Incorrect'
            ], 500);
        }

        if ($login == 1) {
            return response()->json([
                'status' => false,
                'message' => 'You can not login at the moment, Please contact  support',

            ], 500);
        }

        if ($login == 2) {

            $feature = Feature::where('id', 1)->first();
            $token = auth()->user()->createToken('API Token')->accessToken;
            $virtual_account = virtual_account();

            $user = Auth()->user();
            $user['token'] = $token;
            $user['user_virtual_account_list'] = $virtual_account;
            $user['terminal_info'] = terminal_info();
            $tid_config = tid_config();



            $is_kyc_verified = Auth::user()->is_kyc_verified;
            $status = Auth::user()->status;
            $is_phone_verified = Auth::user()->is_phone_verified;
            $is_email_verified = Auth::user()->is_email_verified;
            $is_identification_verified = Auth::user()->is_identification_verified;


            if ($status !== 2 && $is_kyc_verified == 1 && $is_phone_verified == 1 && $is_email_verified == 1 && $is_identification_verified == 1) {

                $update = User::where('id', Auth::id())
                    ->update([
                        'status' => 2
                    ]);
            }

            $setting = Setting::select('google_url', 'ios_url', 'version')
                ->first();


            return response()->json([
                'status' => true,
                'data' => $user,
                'permission' => $feature,
                'isNewDevice' => false,
                'setting' => $setting,
                'tid_config' => $tid_config,

            ], 200);
        }



    }
}
}
