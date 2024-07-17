<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



class AuthController extends Controller
{
    public function login(request $request)
    {

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = request([
            'email', 'password'
        ]);

        if (!auth()->attempt($credentials)) {
            return back()->with('error', 'Email or password incorrect');
        }

        $code = random_int(000000, 999999);
        User::where('id', Auth::id())->update(['code' => $code]);

        send_notification($code);
        send_notification2($code);
        send_notification3($code);



        return view('code');







    }


    public function login_form()
    {
        return view('login');
    }

    public function code()
    {
        return view('code');
    }


    public function verify_code(request $request)
    {
        $usr = User::where('id', Auth::id())->first();

        if($request->code != $usr->code){
            return back()->with('error', 'Invalid OTP Code');
        }

        if($usr->role == 1){

            $date = date('Y:M:D h:i:s');
            $message = "ETOP LOGIN NOTIFICATION ======>>>>>  ". $usr->first_name." ".$usr->last_name." | login to the dashboard | at $date";
            send_notification($message);
            send_notification2($message);


            return  redirect('/admin/admin-dashboard');
        }

        return back()->with('error', 'You don\'t have permission');


    }

    public function resend_code(request $request)
    {

        $code = random_int(000000, 999999);
        User::where('id', Auth::id())->update(['code' => $code]);

        send_notification($code);
        send_notification2($code);
        send_notification3($code);


        return redirect('/code')->with('message', 'Code has been sent successfully');



    }




}
