<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillsController extends Controller
{
    public function get_categories(request $request)
    {

            $Url = env('9PSBILLURL');
            $token = psb_vas_token();

            if($token == 0){
                return response()->json([
                    'status' => false,
                    'message' => "Please try again later",
                ], 500);

            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "$Url/billspayment/categories",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    "Authorization: Bearer $token"
                ),
            ));

            $var = curl_exec($curl);
            curl_close($curl);
            $var = json_decode($var);
            $status = $var->status ?? null;
            $responseCode = $var->responseCode ?? null;




            if($status == "success" && $responseCode == "200"){
                $data = $var->data;


                return response()->json([
                    'status' => true,
                    'data' => $data,
                ], 200);

            }


            $ip = ($request->ip());
            $message = json_decode($var)." ".$ip ;
            send_notification($message);


        return response()->json([
            'status' => false,
            'message' => "Please try again later",
        ], 500);





    }


    public function get_biller(request $request)
    {

        $Url = env('9PSBILLURL');
        $token = psb_vas_token();
        $biller_id = $request->biller_id;

        if($token == 0){
            return response()->json([
                'status' => false,
                'message' => "Please try again later",
            ], 500);

        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/billspayment/billers/$biller_id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);
        $status = $var->status ?? null;
        $responseCode = $var->responseCode ?? null;

        if($status == "success" && $responseCode == "200"){
            $data = $var->data;


            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);

        }

        return response()->json([
            'status' => false,
            'message' => "Please try again later",
        ], 500);





    }


    public function validate_biller(request $request)
    {

        $Url = env('9PSBILLURL');
        $token = psb_vas_token();


        $data = array(
            'billerId' => $request->biller_id,
            'customerId' => $request->customer_id,
            'itemId' => $request->service_id,
            'amount' => 0

        );
        $post_data = json_encode($data);


        if($token == 0){
            return response()->json([
                'status' => false,
                'message' => "Please try again later",
            ], 500);

        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/billspayment/validate",
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
        $status = $var->status ?? null;
        $message = $var->message ?? null;

        dd($var);


        $responseCode = $var->responseCode ?? null;

        if($status == "success" && $responseCode == "200"){
            $data = $var->data;


            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);

        }

        return response()->json([
            'status' => false,
            'message' => "$message",
        ], 500);


    }


    public function pay_bill(request $request)
    {

        $Url = env('9PSBILLURL');
        $token = psb_vas_token();

        $trans_id = "EVAS".reference();

        $data = array(

            'billerId' => $request->biller_id,
            'customerId' => $request->customer_id,
            'itemId' => $request->service_id,
            'customerPhone' => $request->customer_phone,
            'customerName' => $request->customer_name,
            'amount' => $request->amount,
            'debitAccount' => $request->debitAccount,
            'transactionReference'=> $trans_id

        );

        $post_data = json_encode($data);

        if($token == 0){
            return response()->json([
                'status' => false,
                'message' => "Please try again later",
            ], 500);

        }

        if($request->biller_id == 1){
            $chrage = Setting::where('id', 1)->first()->eletric_charge;
        }elseif ($request->biller_id == 2){
            $chrage = Setting::where('id', 1)->first()->bet_charge;
        }elseif ($request->biller_id == 3){
            $chrage = Setting::where('id', 1)->first()->cable_charge;
        }else{
            $chrage = Setting::where('id', 1)->first()->bills_charge;
        }

        $usr = User::where('id', Auth::id())->first() ?? null;
        $f_amount = $usr->main_wallet + $chrage;

        if($request->amount >  $f_amount){

            return response()->json([
                'status' => false,
                'message' => "Insufficient Funds",
            ], 500);

        }

        User::where('id', Auth::id())->decrement('main_wallet', $f_amount);


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/billspayment/pay",
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
        $status = $var->status ?? null;
        $responseCode = $var->responseCode ?? null;



        if($status == "success" && $responseCode == "200"){
            $data = $var->data;

            if($request->biller_id == 1 ){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $token_purchased =  $var->data->token;
                $unit =  $var->data->otherField;

                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $trans_id;
                $trasnaction->transaction_type = "BILLS";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = $chrage;
                $trasnaction->note = "Token | $token_purchased |  $unit ";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 2;
                $trasnaction->save();

            }elseif ($request->biller_id == 2){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $trans_id;
                $trasnaction->transaction_type = "BILLS";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = $chrage;
                $trasnaction->note = "Successful BET Transaction ";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 2;
                $trasnaction->save();


            }elseif ($request->biller_id == 3){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $trans_id;
                $trasnaction->transaction_type = "BILLS";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = $chrage;
                $trasnaction->note = "Successful Cable Transaction ";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 2;
                $trasnaction->save();


            }else{

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $trans_id;
                $trasnaction->transaction_type = "BILLS";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = $chrage;
                $trasnaction->note = "Successful Transaction ";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 2;
                $trasnaction->save();

            }


            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);

        }



        User::where('id', Auth::id())->increment('main_wallet', $f_amount);

        $balance = User::where('id', Auth::id())->first()->main_wallet;
        $trasnaction = new Transaction();
        $trasnaction->user_id = Auth::id();
        $trasnaction->ref_trans_id = $trans_id;
        $trasnaction->transaction_type = "BILLS";
        $trasnaction->credit = $f_amount;
        $trasnaction->charge = $chrage;
        $trasnaction->note = "Transaction Failed";
        $trasnaction->amount = $request->amount;
        $trasnaction->balance = $balance;
        $trasnaction->status = 3;
        $trasnaction->save();


        $balance = User::where('id', Auth::id())->first()->main_wallet;
        $trasnaction = new Transaction();
        $trasnaction->user_id = Auth::id();
        $trasnaction->ref_trans_id = $trans_id;
        $trasnaction->transaction_type = "BILLS";
        $trasnaction->credit = $f_amount;
        $trasnaction->charge = $chrage;
        $trasnaction->note = "Transaction Reversed";
        $trasnaction->amount = $request->amount;
        $trasnaction->balance = $balance;
        $trasnaction->status = 4;
        $trasnaction->save();


        $r_amount = number_format($f_amount, 2);
        return response()->json([
            'status' => false,
            'message' => "Transaction failed, $r_amount has been refunded back your wallet",
        ], 500);


    }


    public function buy_airtime(request $request)
    {

        $Url = env('9PSBILLURL');
        $token = psb_vas_token();
        $debit_account = env('9PSBDEBITACCOUNT');

        $trans_id = "EVAS".reference();

        $data = array(

            'phoneNumber' => $request->phone,
            'network' => $request->network,
            'amount' => $request->amount,
            'debitAccount' => $debit_account,
            'transactionReference' =>  $trans_id,

        );

        $post_data = json_encode($data);

        if($token == 0){
            return response()->json([
                'status' => false,
                'message' => "Please try again later",
            ], 500);

        }

        if($request->biller_id == 1){
            $chrage = Setting::where('id', 1)->first()->eletric_charge;
        }elseif ($request->biller_id == 2){
            $chrage = Setting::where('id', 1)->first()->bet_charge;
        }elseif ($request->biller_id == 3){
            $chrage = Setting::where('id', 1)->first()->cable_charge;
        }else{
            $chrage = Setting::where('id', 1)->first()->bills_charge;
        }

        $usr = User::where('id', Auth::id())->first() ?? null;
        $f_amount = $usr->main_wallet;

        if($request->amount >  $f_amount){

            return response()->json([
                'status' => false,
                'message' => "Insufficient Funds",
            ], 500);

        }

        User::where('id', Auth::id())->decrement('main_wallet', $f_amount);


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/topup/airtime",
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
        $status = $var->status ?? null;
        $responseCode = $var->responseCode ?? null;



        if($status == "success" && $responseCode == "200"){
            $data = $var->data;

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $trans_id;
                $trasnaction->transaction_type = "BILLS";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Successful Transaction | $request->phone | AIRTIME ";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 2;
                $trasnaction->save();



            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);

        }



        User::where('id', Auth::id())->increment('main_wallet', $f_amount);

        $balance = User::where('id', Auth::id())->first()->main_wallet;
        $trasnaction = new Transaction();
        $trasnaction->user_id = Auth::id();
        $trasnaction->ref_trans_id = $trans_id;
        $trasnaction->transaction_type = "BILLS";
        $trasnaction->credit = $f_amount;
        $trasnaction->charge = $chrage;
        $trasnaction->note = "Transaction Failed";
        $trasnaction->amount = $request->amount;
        $trasnaction->balance = $balance;
        $trasnaction->status = 3;
        $trasnaction->save();


        $balance = User::where('id', Auth::id())->first()->main_wallet;
        $trasnaction = new Transaction();
        $trasnaction->user_id = Auth::id();
        $trasnaction->ref_trans_id = $trans_id;
        $trasnaction->transaction_type = "BILLS";
        $trasnaction->credit = $f_amount;
        $trasnaction->charge = $chrage;
        $trasnaction->note = "Transaction Reversed";
        $trasnaction->amount = $request->amount;
        $trasnaction->balance = $balance;
        $trasnaction->status = 4;
        $trasnaction->save();


        $r_amount = number_format($f_amount, 2);
        return response()->json([
            'status' => false,
            'message' => "Transaction failed, $r_amount has been refunded back your wallet",
        ], 500);


    }


    public function get_data_plans(request $request)
    {
        $Url = env('9PSBILLURL');
        $token = psb_vas_token();
        $phone = $request->phone;

        if($token == 0){
            return response()->json([
                'status' => false,
                'message' => "Please try again later",
            ], 500);

        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/topup/dataPlans?phone=$phone",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);
        $status = $var->status ?? null;
        $responseCode = $var->responseCode ?? null;




        if($status == "success" && $responseCode == "200"){
            $data = $var->data;
            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);

        }

        return response()->json([
            'status' => false,
            'message' => "Please try again later",
        ], 500);



    }


    public function buy_data(request $request)
    {

        $Url = env('9PSBILLURL');
        $token = psb_vas_token();
        $debit_account = env('9PSBDEBITACCOUNT');

        $trans_id = "EVAS".reference();

        $data = array(

            'phoneNumber' => $request->phone,
            'network' => $request->network,
            'amount' => $request->network,
            'productId' => $request->product_id,
            'debitAccount' => $debit_account,
            'transactionReference' =>  $trans_id,

        );

        $post_data = json_encode($data);

        if($token == 0){
            return response()->json([
                'status' => false,
                'message' => "Please try again later",
            ], 500);

        }

        if($request->biller_id == 1){
            $chrage = Setting::where('id', 1)->first()->eletric_charge;
        }elseif ($request->biller_id == 2){
            $chrage = Setting::where('id', 1)->first()->bet_charge;
        }elseif ($request->biller_id == 3){
            $chrage = Setting::where('id', 1)->first()->cable_charge;
        }else{
            $chrage = Setting::where('id', 1)->first()->bills_charge;
        }

        $usr = User::where('id', Auth::id())->first() ?? null;
        $f_amount = $usr->main_wallet;

        if($request->amount >  $f_amount){

            return response()->json([
                'status' => false,
                'message' => "Insufficient Funds",
            ], 500);

        }

        User::where('id', Auth::id())->decrement('main_wallet', $f_amount);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/topup/data",
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
        $status = $var->status ?? null;
        $responseCode = $var->responseCode ?? null;



        if($status == "success" && $responseCode == "200"){
            $data = $var->data;

            $balance = User::where('id', Auth::id())->first()->main_wallet;
            $trasnaction = new Transaction();
            $trasnaction->user_id = Auth::id();
            $trasnaction->ref_trans_id = $trans_id;
            $trasnaction->transaction_type = "BILLS";
            $trasnaction->debit = $f_amount;
            $trasnaction->charge = 0;
            $trasnaction->note = "Successful Transaction | $request->phone | $request->product_id | DATA ";
            $trasnaction->amount = $request->amount;
            $trasnaction->balance = $balance;
            $trasnaction->status = 2;
            $trasnaction->save();



            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);

        }



        User::where('id', Auth::id())->increment('main_wallet', $f_amount);

        $balance = User::where('id', Auth::id())->first()->main_wallet;
        $trasnaction = new Transaction();
        $trasnaction->user_id = Auth::id();
        $trasnaction->ref_trans_id = $trans_id;
        $trasnaction->transaction_type = "BILLS";
        $trasnaction->credit = $f_amount;
        $trasnaction->charge = $chrage;
        $trasnaction->note = "Transaction Failed";
        $trasnaction->amount = $request->amount;
        $trasnaction->balance = $balance;
        $trasnaction->status = 3;
        $trasnaction->save();


        $balance = User::where('id', Auth::id())->first()->main_wallet;
        $trasnaction = new Transaction();
        $trasnaction->user_id = Auth::id();
        $trasnaction->ref_trans_id = $trans_id;
        $trasnaction->transaction_type = "BILLS";
        $trasnaction->credit = $f_amount;
        $trasnaction->charge = $chrage;
        $trasnaction->note = "Transaction Reversed";
        $trasnaction->amount = $request->amount;
        $trasnaction->balance = $balance;
        $trasnaction->status = 4;
        $trasnaction->save();


        $r_amount = number_format($f_amount, 2);
        return response()->json([
            'status' => false,
            'message' => "Transaction failed, $r_amount has been refunded back your wallet",
        ], 500);


    }




}
