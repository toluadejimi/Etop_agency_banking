<?php

namespace App\Http\Controllers\Agents;

use App\Models\Beneficiary;
use App\Models\ErrandKey;
use App\Models\OauthAccessToken;
use App\Models\User;
use App\Models\Feature;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Passport;

class AuthController extends Controller
{


    public function verify_pin(request $request)
    {

        try {

            $pin = $request->pin;

            $get_pin = User::where('id', Auth::id())
                ->first()->pin;

            if (Hash::check($pin, $get_pin)) {
                return response()->json([
                    'status' => true,
                    'data' => "Pin Verified",
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Invalid pin please try again",
                ], 500);
            }
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }


    public function user_info(request $request)
    {

        $GetToken = $request->header('Authorization');

        $string = $GetToken;
        $toBeRemoved = "Bearer ";
        $token = str_replace($toBeRemoved, "", $string);

        $virtual_account = virtual_account();
        $user = Auth()->user();
        $user['token'] = $token;
        $user['user_virtual_account_list'] = $virtual_account;
        $user['terminal_info'] = terminal_info();
        $tid_config = tid_config();

        return response()->json([
            'status' => true,
            'data' => $user,
            'tid_config' => $tid_config,
        ], 200);

    }


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
                    'status' => false,
                    'message' => "Pin can not be empty"
                ], 500);

            }

            if($request->password  == null) {

                return response()->json([
                    'status' => false,
                    'message' => "Password can not be empty"
                ], 500);

            }

            if($request->email  == null) {

                return response()->json([
                    'status' => false,
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
                    'status' => false,
                    'message' => "Incorrect Pin \n\n Please try again!"
                ], 500);
            }

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => "Incorrect Pin \n\n Please try again!"
                ], 500);
            }

            $user = User::where('email', $email)->first();

            if ($user->status == 5) {


                return response()->json([

                    'status' => false,
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
                    'status' => false,
                    'message' => "Pin can not be empty"
                ], 500);

            }


            if($request->password  == null) {

                return response()->json([
                    'status' => false,
                    'message' => "Password can not be empty"
                ], 500);

            }


            $credentials = request(['phone', 'password']);
            Passport::tokensExpireIn(Carbon::now()->addMinutes(20));
            Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(20));
            $check_status = User::where('phone', $phone)->first()->status ?? null;

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => "Incorrect Pin \n\n Please try again!"
                ], 500);
            }


            $get_pin = User::where('phone', $phone)->first()->pin;
            if (Hash::check($pin, $get_pin) == false) {

                return response()->json([
                    'status' => false,
                    'message' => "Incorrect Pin \n\n Please try again!"
                ], 500);
            }

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => "pIncorrect Pin \n\n Please try again!"
                ], 500);
            }

            if (Auth::user()->status == 5) {


                return response()->json([

                    'status' => false,
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
                'status' => true,
                'data' => $user,
                'permission' => $feature,
                'isNewDevice' => false,
                'setting' => $setting,
                'tid_config' => $tid_config,

            ], 200);


        }

        if($request->pin  == null) {

            return response()->json([
                'status' => false,
                'message' => "Pin can not be empty"
            ], 500);

        }


        if($request->password  == null) {

            return response()->json([
                'status' => false,
                'message' => "Password can not be empty"
            ], 500);

        }

        if($request->email  == null || $request->phone  == null) {

            return response()->json([
                'status' => false,
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
    public function forgot_pin(Request $request)
    {

        try {

            $email = $request->email;

            if (Auth::user()->email != $email) {

                return response()->json([

                    'status' => false,
                    'message' => 'Please enter the email attached to this acccount',

                ], 500);
            }

            $check = User::where('email', $email)
                ->first()->email ?? null;

            $first_name = User::where('email', $email)
                ->first()->first_name ?? null;

            if ($check == $email) {

                //send email
                $data = array(
                    'fromsender' => 'noreply@etopng.com', 'ETOP AGENCY',
                    'subject' => "Reset Pin",
                    'toreceiver' => $email,
                    'first_name' => $first_name,
                    'link' => url('') . "/reset-pin/?email=$email",
                );

                Mail::send('emails.pinlink', ["data1" => $data], function ($message) use ($data) {
                    $message->from($data['fromsender']);
                    $message->to($data['toreceiver']);
                    $message->subject($data['subject']);
                });

                return response()->json([
                    'status' => true,
                    'message' => 'Check your inbox or spam for instructions',
                ], 200);
            } else {

                return response()->json([

                    'status' => false,
                    'message' => 'User not found on our system',

                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function get_beneficary()
    {

        $bens = Beneficiary::select('id','name', 'bank_code', 'acct_no')->where('user_id', Auth::id())->get() ?? [];

        return response()->json([
            'status' => true,
            'data' => $bens,
        ], 200);

    }
    public function update_beneficary(request $request)
    {
        Beneficiary::where('id', $request->id)->update([
            'name'=> $request->customer_name,
        ]);

        return response()->json([
            'status' => true,
            'message' => "Beneficiary Updated Successfully",
        ], 200);

    }
    public function delete_beneficary(request $request)
    {
        Beneficiary::where('id', $request->id)->delete();

        return response()->json([
            'status' => true,
            'message' => "Beneficiary Deleted Successfully",
        ], 200);

    }
    public function update_business(request $request)
    {

        try {

            $b_name = $request->b_name;
            $b_number = $request->b_number;
            $b_address = $request->b_address;

            $update = User::where('id', Auth::id())
                ->update([

                    'b_name' => $b_name,
                    'b_number' => $b_number,
                    'b_address' => $b_address,

                ]);

            return response()->json([
                'status' => true,
                'message' => "Business Details has been successfully updated",

            ], 200);
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }
    public function reset_pin(request $request)
    {
        $email = $request->email;
        return view('reset-pin', compact('email'));
    }
    public function reset_password(request $request)
    {
        $email = $request->email;
        return view('reset-password', compact('email'));
    }
    public function success()
    {

        return view('success');
    }
    public function reset_pin_now(Request $request)
    {

        $email = $request->email;



        $input = $request->validate([
            'password' => ['required', 'confirmed', 'int'],
        ]);

        $pin = Hash::make($request->password);


        $chk_pin_length = strlen($request->password);


        if ($chk_pin_length > 4) {
            return back()->with('error', 'Your pin digit is more than 4');
        }

        $check_email = User::where('email', $email)->first();


        if ($check_email == null) {

            return back()->with('error', 'User not found');
        }

        $update_pin = User::where('email', $email)
            ->update(['pin' => $pin]);


        return redirect('success')->with('message', 'Your pin has been successfully updated');
    }
    public function reset_password_now(Request $request)
    {

        $email = $request->email;



        $input = $request->validate([
            'password' => ['required', 'confirmed', 'string'],
        ]);

        $password = Hash::make($request->password);


        $check_email = User::where('email', $email)->first();


        if ($check_email == null) {

            return back()->with('error', 'User not found');
        }

        $update_pin = User::where('email', $email)
            ->update(['password' => $password]);


        return redirect('success')->with('message', 'Your password has been successfully updated');
    }





}
