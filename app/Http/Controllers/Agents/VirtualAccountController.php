<?php

namespace App\Http\Controllers\Agents;

use App\Models\User;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\VirtualAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VirtualAccountController extends Controller
{

    public function create_account_dymamic(request $request)
    {
        $user_id = $request->user_id;
        $description = $request->description;
        $name = $request->name;
        $amount = $request->amount;


        $result = create_9psb_v_account_dymamic($user_id, $description, $name, $amount);

        return response()->json([
            'result' => $result
        ]);


    }



    public function create_virtual_account(request $request)
    {

        $create =  create_9psb_v_account();

        if ($create == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong please try again later',
            ], 500);
        }

        if ($create == 2) {
            return response()->json([
                'status' => true,
                'message' => 'Account successfuly created',
            ], 200);
        }
    }


    public function virtual_notification(request $request)
    {


        $parametersJson = json_encode($request->all());

        $result = $parametersJson;
        send_notification($result);



        $n_username = env('NUSERNAME');
        $n_password = env('NPASS');

        if($n_password == null || $n_password == null){
            return response()->json([
                'status' => false,
                'message' => "Credentials can not be null"
            ], 500);
        }


        if($request->username != $n_username){

            $result = "Incorrect Invalid";
            send_notification($result);

            return response()->json([
                'status' => false,
                'message' => "Invalid Username"

            ], 500);

        }


        if($request->password != $n_password){

            $result = "Incorrect Password";
            send_notification($result);


            return response()->json([
                'status' => false,
                'message' => "Invalid Password"
            ], 500);



        }









        $refrence = $request->transaction['reference'];
        $sessionid = $request->transaction['sessionid'];
        $date = $request->transaction['date'];
        $receiver_name = $request->customer['account']['name'];
        $receiver_account_number = $request->customer['account']['number'];
        $receiver_bank = $request->customer['account']['bank'];
        $sender_bankcode = $request->customer['account']['senderbankcode'];
        $sender_bankname = $request->customer['account']['senderbankname'];
        $sender_accountnumber = $request->customer['account']['senderaccountnumber'];
        $sender_name = $request->customer['account']['sendername'];
        $amount = $request['order']['amount'];
        $currency = $request['order']['currency'];
        $description = $request['order']['description'];


        $ck_va = VirtualAccount::where('v_account_no', $receiver_account_number)->first() ?? null;
        if($ck_va == null){

            $message = 'ETOP AGENCY User Not found';
            $ip = $request->ip();
            $reault = $message . "\n\nIP========> " . $ip;
            send_notification($reault);

            return response()->json([
                'message' => "Virtual Account not found",
                'code' => "00",
            ], 200);

        }





        $ck_tx = Transaction::where('e_ref', $refrence)->where('status', 2)->first() ?? null;
        if ($ck_tx != null) {

            $message = 'ETOP AGENCY Duplicate Payment Notification';
            $ip = $request->ip();
            $reault = $message . "\n\nIP========> " . $ip;
            send_notification($reault);

            return response()->json([
                'message' => "Duplicate Transaction",
                'code' => "00",
            ], 200);
        }



        $virtal_account_charge = Setting::where('id', 1)->first()->virtual_account_charge;
        $final_amount = $amount - $virtal_account_charge;
        $user_id = VirtualAccount::where('v_account_no', $receiver_account_number)->first()->user_id;
        User::where('id', $user_id)->increment('main_wallet', $final_amount);
        $user = User::where('id', $user_id)->first();


        //Save Account

        $trx = new Transaction();
        $trx->ref_trans_id = "ETOP" . reference();
        $trx->e_ref = $refrence;
        $trx->user_id = $user->id;
        $trx->amount = $amount;
        $trx->credit = $final_amount;
        $trx->charge = $virtal_account_charge;
        $trx->balance = $user->main_wallet;
        $trx->sender_name = $sender_name;
        $trx->sender_bank = $sender_bankname;
        $trx->sender_account_no = $sender_accountnumber;
        $trx->receiver_name = $receiver_name;
        $trx->receiver_bank = $receiver_bank;
        $trx->receiver_account_no = $receiver_account_number;
        $trx->sessionId = $sessionid;
        $trx->note = $description;
        $trx->status = 2;
        $trx->transaction_type = "VIRTUAL ACCOUNT";
        $trx->save();



        return response()->json([
            'status'=>true,
            'code'=>"00",
            "message"=> "Successful"
        ]);




        $parametersJson = json_encode($request->all());
        $headers = json_encode($request->headers->all());
        $message = 'Payment Notification';
        $ip = $request->ip();

        $result = " Header========> " . $headers . "\n\n Body========> " . $parametersJson . "\n\n Message========> " . $message . "\n\nIP========> " . $ip;
        send_notification($result);
    }
}
