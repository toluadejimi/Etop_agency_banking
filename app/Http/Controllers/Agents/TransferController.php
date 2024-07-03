<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\Setting;
use App\Models\User;
use App\Models\Transaction;
use App\Models\VirtualAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TransferController extends Controller
{


    public function transfer_properties(request $request)
    {

        $Url = env('9PSTRANSFERURL');
        $token = psb_token();
        $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->format('Y-m-d\TH:i:s.uO');


        $data = array(
            'RequestDateTime' => $formattedDateTime,
        );

        $post_data = json_encode($data);

        if ($token == 0) {
            return response()->json([
                'status' => false,
                'message' => "Please try again later",
            ], 500);

        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/merchant/transfer/getbanks",
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
        $history = $var->BankList ?? null;


        if ($history != null) {

            $history = [];
            foreach ($var->BankList as $key => $value) {
                $history[] = array(
                    "bankName" => $value->BankName,
                    "code" => $value->BankCode,
                );
            }


            $account = select_account();
            $transfer_charge = Setting::where('id', 1)->first()->transfer_charge;
            $bens = Beneficiary::select('id', 'name', 'bank_code', 'acct_no')->where('user_id', Auth::id())->get() ?? [];


            return response()->json([
                'account' => $account,
                'transfer_charge' => $transfer_charge,
                'banks' => $history,
                'beneficariy' => $bens,
            ], 200);


        }


    }


    public function validate_account(request $request)
    {


        $Url = env('9PSTRANSFERURL');
        $token = psb_token();


        $data = array(
            'customer' => [
                'account' => [
                    'number' => $request->account_number,
                    'bank' => $request->bank_code
                ],
            ],
        );

        $post_data = json_encode($data);

        if ($token == 0) {
            return response()->json([
                'status' => false,
                'message' => "Please try again later",
            ], 500);

        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/merchant/account/enquiry",
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
        $code = $var->code ?? null;


        if ($code == 00) {
            $name = $var->customer->account->name;

            return response()->json([
                'status' => true,
                'customer_name' => $name,
            ], 200);


        } else {

            $name = "Invalid Account, Check information again";

            return response()->json([
                'status' => true,
                'customer_name' => $name,
            ], 200);
        }


    }


    public function transfer(request $request)
    {


        if (Auth::user()->status == 7) {


            return response()->json([

                'status' => false,
                'message' => 'You can not make transfer at the moment, Please contact support',

            ], 500);
        }


        $Url = env('9PSTRANSFERURL');
        $token = psb_token();




        $ref = "TRF" . reference();
        $wallet = $request->wallet;
        $amount = number_format($request->amount,2, '.', '');
        $destinationAccountNumber = $request->account_number;
        $destinationBankCode = $request->code;
        $destinationAccountName = $request->customer_name;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $get_description = $request->narration;
        $pin = $request->pin;
        $beneficiary = $request->beneficiary;
        $user_pin = Auth()->user()->pin;







        if (Hash::check($pin, $user_pin) == false) {

            return response()->json([

                'status' => false,
                'message' => 'Invalid Pin, Please try again',

            ], 500);
        }

        $charge = Setting::where('id', 1)->first()->transfer_charge;
        $f_amount = $request->amount + $charge;

        $usr = User::where('id', Auth::id())->first();
        if($f_amount > $usr->main_wallet){

            return response()->json([

                'status' => false,
                'message' => 'Insufficient Funds',

            ], 500);

        }

        User::where('id', Auth::id())->decrement('main_wallet', $f_amount);

        $string = env('9PSBPRIKEY').env('DEBITACCOUNT').$destinationAccountNumber.$destinationBankCode.$amount.$ref;
        $hash = hash('sha512',  $string);

        $data = array(
            'transaction' => [
                'reference' => $ref
            ],

            'order' => [
                'amount' => $amount,
                'description' => $get_description,
                'currency' => "NGN",
                'country' => "NGA"
            ],

            "customer" => [

                'account' => [
                    'number' =>  $destinationAccountNumber,
                    'bank' =>  $destinationBankCode,
                    'name' => $destinationAccountName,
                    'senderaccountnumber' => env('DEBITACCOUNT'),
                    'sendername' => "ETOP-".Auth::user()->first_name. " ".Auth::user()->last_name,
                ],





            ],

            "hash" => strtoupper($hash)


        );



        $post_data = json_encode($data);


        if ($token == 0) {
            return response()->json([
                'status' => false,
                'message' => "Please try again later",
            ], 500);

        }

        $url = "$Url/merchant/account/transfer";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,//"https://baastest.9psb.com.ng/ipaymw-api/v1/merchant/account/enquiry",
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
        $code = $var->code ?? null;


        if ($code == 00) {

            $balance = User::where('id', Auth::id())->first()->main_wallet;
            $trasnaction = new Transaction();
            $trasnaction->user_id = Auth::id();
            $trasnaction->ref_trans_id = $ref;
            $trasnaction->transaction_type = "TRANSFEROUT";
            $trasnaction->debit = $f_amount;
            $trasnaction->charge = $charge;
            $trasnaction->note = "Transaction Successful";
            $trasnaction->amount = $request->amount;
            $trasnaction->balance = $balance;
            $trasnaction->status = 2;
            $trasnaction->save();

            return response()->json([
                'status' => true,
                'message' => "Transaction Successful",
            ], 200);


        } else {

            User::where('id', Auth::id())->increment('main_wallet', $f_amount);
            $balance = User::where('id', Auth::id())->first()->main_wallet;
            $trasnaction = new Transaction();
            $trasnaction->user_id = Auth::id();
            $trasnaction->ref_trans_id = $ref;
            $trasnaction->transaction_type = "BILLS";
            $trasnaction->credit = $f_amount;
            $trasnaction->charge = 0;
            $trasnaction->note = "Transaction Failed";
            $trasnaction->amount = $request->amount;
            $trasnaction->balance = $balance;
            $trasnaction->status = 3;
            $trasnaction->save();

        }


        $r_amount = number_format($request->amount, 2);
        $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
        send_notification($message);

        return response()->json([
            'status' => false,
            'message' => "Transaction failed",
        ], 500);


    }


}
