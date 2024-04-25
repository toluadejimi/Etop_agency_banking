<?php

namespace App\Http\Controllers;

use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TerminalopController extends Controller
{


    public
    function get_terminal_details(request $request)
    {

        $SerialNo = $request->header('serialnumber');

        $data = GetTerminalDetails($SerialNo);

        if ($data == null) {
            $message = "Terminal Not Found";
            return error_response($message);
        }

        return response()->json([
            'status' => true,
            'terminal' => $data,
            'terminals' => null,
            'error' => null
        ], 200);

    }


    public  function reset_pin(request $request)
{

    $newpin = $request->newPin;
    $oldpin = $request->oldPin;


    $SerialNo = $request->header('serialnumber');

    if ($SerialNo == null) {
        $message = "Serial Number can not be empty";
        return error_response($message);
    }

    $check_user = Terminal::where('serialNumber', $SerialNo)->first();
    if ($check_user == null) {
        $message = "Terminal not found";
        return error_response($message);
    }

    $get_pin = Terminal::where('serialNumber', $SerialNo)->first()->pin ?? null;
    $pin_ck = $request->newPin;


    if (Hash::check($oldpin, $get_pin)) {
        if (Hash::check($pin_ck, $get_pin)) {
            $message = "Please choose another pin";
            return error_pin_response($message);

        }

        $pin = Hash::make($newpin);
        Terminal::where('serialNumber', $SerialNo)->update(['pin' => $pin]);
        return response()->json([
            'success' => true,
            'error' => null,
        ], 200);
        return error_response($message);

    } else {
        $message = "Incorrect old pin";
        return error_pin_response($message);
    }


}

public function verify_pin(request $request)
{

    $SerialNo = $request->header('serialnumber');

    if ($SerialNo == null) {
        $message = "Serial Number can not be empty";
        return error_response($message);
    }

    $check_user = Terminal::where('serialNumber', $SerialNo)->first();
    if ($check_user == null) {
        $message = "Terminal not found";
        return error_response($message);
    }

    $oldpin = $request->pin;
    $get_pin = Terminal::where('serialNumber', $SerialNo)->first()->pin ?? null;
    if (Hash::check($oldpin, $get_pin)) {
        return response()->json([
            'success' => true,
            'error' => null,
        ], 200);

    } else {
        $message = "Incorrect pin";
        return error_pin_response($message);
    }


}


}
