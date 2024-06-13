<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\PosLog;
use App\Models\Setting;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{


    public function setting(request $request)
    {
        $data['setting'] = Setting::where('id', 1)->first();
        return view ('setting', $data);

    }


    public function update_setting(request $request)
    {
        $data['setting'] = Setting::where('id', 1)->first();

        Setting::where('id', 1)->update([

            'pos_charge' => $request->pos_charge,
            'cap' => $request->cap,
            'transfer_out_charge'=>$request->transfer_out_charge,
            'transfer_in_charge'=>$request->transfer_in_charge,
            'eletric_charge'=>$request->eletric_charge,
            'mtn_airtime'=>$request->mtn_airtime,
            'glo_airtime'=>$request->glo_airtime,
            'airtel_airtime'=>$request->airtel_airtime,
            'mobile9_airtime'=>$request->mobile9_airtime,
            'mtn_data'=>$request->mtn_data,
            'glo_data'=>$request->glo_data,
            'mobile9_data'=>$request->mobile9_airtime,
            'cable_charge'=>$request->cable_charge,
            'swift'=>$request->swift,
            'spectranaect'=>$request->spectranaect,

        ]);

        return back()->with('message', "Charge has been successfully updated");


    }







    public function admin_login(request $request)
    {

        if (Auth::attempt([
            'email' => $request->email, 'password' => $request->password],
            $request->get('remember'))) {

            $user['user'] = Auth::user();
            $user['token'] = auth()->user()->createToken('API Token')->accessToken;

            return response()->json([
                'status' => true,
                'data' => $user

            ], 200);

        } else {

            return response()->json([
                'status' => false,
                'message' => "Email or Password is Incorrect"

            ], 422);

        }

    }


}
