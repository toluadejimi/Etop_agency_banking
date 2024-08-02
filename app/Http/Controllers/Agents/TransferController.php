<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\Paccount;
use App\Models\Profit;
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

    public function profict_tracker_view(request $request)
    {


        if (Auth::user()->role != 1) {
            return back()->with('error', 'You dont have permission to view this page');
        }

        $main_wallet = User::where('status', 1)->orwhere('status', 2)->sum('main_wallet');
        $psb_bal = wallet_balance();
        $prr = $psb_bal - $main_wallet;
        if($prr > 0){
            $data['total_profit'] = $psb_bal - $main_wallet;
        }else{
            $data['total_profit'] = 0;
        }




        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        //$data['total_profit'] = Transaction::where('status', 2)->whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('charge');
        $data['profit'] = Profit::latest()->paginate(10);
        $data['total_trx'] = Profit::where('status', 2)->sum('amount');

        return view('profit', $data);

    }


    public function add_profit(request $request)
    {

        if (Auth::user()->role != 1) {
            return back()->with('error', 'You can not make transfer at the moment, Please contact Admin');

        }

        $main_wallet = User::where('status', 1)->orwhere('status', 2)->sum('main_wallet');
        $psb_bal = wallet_balance();
        $prr = $psb_bal - $main_wallet;

        if($request->amount < $prr){
            return back()->with('error', 'insufficient profit');
        }

        $pacc = Paccount::where('id', 1)->first();
        $Url = env('9PSTRANSFERURL');
        $token = psb_token();
        $mar = $request->narration;

        if($mar == null){
            $narra = "Profit Transfer";
        }else{
            $narra = $mar;
        }

        $ref = "TRF" . reference();
        $wallet = $request->wallet;
        $amount = number_format($request->amount,2, '.', '');
        $destinationAccountNumber = $pacc->account_number;
        $destinationBankCode = $request->code;
        $destinationAccountName = $pacc->customer_name;
        $get_description = $narra;
        $pin = $request->pin;
        $beneficiary = $pacc->beneficiary;


        $main_wallet = User::where('status', 1)->orwhere('status', 2)->sum('main_wallet');
        $psb_bal = wallet_balance();
        $profit = $psb_bal - $main_wallet;
        $settlement_bal = settlement();

        if($psb_bal > $request->amount){

            $charge_account = env('DEBITACCOUNT');

            $string = env('9PSBPRIKEY').$charge_account.$destinationAccountNumber.$destinationBankCode.$amount.$ref;
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
                        'senderaccountnumber' => $charge_account,
                        'sendername' => "ETOP-MANAGMENT PROF",
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
            curl_close($curl);
            $var = json_decode($var);
            $code = $var->code ?? null;


            if($code == "09"){
                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);
                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }


            if($code == "68"){
                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "97"){
                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "96"){
                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "98"){

                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "99"){
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "77"){

                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if ($code == "00") {

                $trasnaction = new Profit();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->e_ref = $ref;
                $trasnaction->transaction_type = "TRANSFEROUTPROFIT";
                $trasnaction->debit = $amount;
                $trasnaction->charge = 10;
                $trasnaction->note = "Transaction Successful";
                $trasnaction->amount = $request->amount;
                $trasnaction->receiver_name = $destinationAccountName;
                $trasnaction->receiver_account_no = $destinationAccountNumber;
                $trasnaction->receiver_bank = $destinationBankCode;
                $trasnaction->balance = 0;
                $trasnaction->status = 2;
                $trasnaction->save();


                $prof = new Profit();
                $prof->trx_id = $ref;
                $prof->status = 2;
                $prof->amount = $amount;
                $prof->save();

                $amount = number_format($request->amount, 2);
                return response()->json([
                    'status' => true,
                    'message' => "Transaction Successful \n NGN$amount has been sent to $destinationAccountName",
                ], 200);


            } else {
                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Failed"
                ], 500);

            }


        }elseif($settlement_bal > $amount){


            $charge_account = env('INSTANTACCOUNT');
            $string = env('9PSBPRIKEY').$charge_account.$destinationAccountNumber.$destinationBankCode.$amount.$ref;
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
                        'senderaccountnumber' => $charge_account,
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
            curl_close($curl);
            $var = json_decode($var);
            $code = $var->code ?? null;


            if ($code == "00") {

                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->e_ref = $ref;
                $trasnaction->transaction_type = "TRANSFEROUTPROFIT";
                $trasnaction->debit = $amount;
                $trasnaction->charge = 10;
                $trasnaction->note = "Transaction Successful";
                $trasnaction->amount = $request->amount;
                $trasnaction->receiver_name = $destinationAccountName;
                $trasnaction->receiver_account_no = $destinationAccountNumber;
                $trasnaction->receiver_bank = $destinationBankCode;
                $trasnaction->balance = 0;
                $trasnaction->status = 2;
                $trasnaction->save();


                $prof = new Profit();
                $prof->trx_id = $ref;
                $prof->status = 2;
                $prof->amount = $amount;
                $prof->save();

                $amount = number_format($request->amount, 2);
                return response()->json([
                    'status' => true,
                    'message' => "Transaction Successful \n NGN$amount has been sent to $destinationAccountName",
                ], 200);


            } else {

                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Failed"
                ], 500);

            }


        }else{

            $message = "ETOP ERROR ===>>> Settlement Balance Insufficient $settlement_bal | main Balance Insufficient $psb_bal ";
            send_notification($message);
            return response()->json([

                'status' => false,
                'message' => "Transaction not processed \n Please try again later",

            ], 500);

        }


//
//        $prof = new Profit();
//        $prof->trx_id = "TRF"

    }



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

            return response()->json([
                'status' => false,
                'message' => "Invalid Account, \n Check information again",
            ], 500);
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


        $mar = $request->narration;

        if($mar == null){

            $narra = "Transfer from $request->customer_name";

        }else{
            $narra = $mar;
        }


        $ref = "TRF" . reference();
        $wallet = $request->wallet;
        $amount = number_format($request->amount,2, '.', '');
        $destinationAccountNumber = $request->account_number;
        $destinationBankCode = $request->code;
        $destinationAccountName = $request->customer_name;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $get_description = $narra;
        $pin = $request->pin;
        $beneficiary = $request->beneficiary;
        $user_pin = Auth()->user()->pin;


        if (Hash::check($pin, $user_pin) == false) {

            return response()->json([

                'status' => false,
                'message' => 'Invalid Pin, Please try again',

            ], 500);
        }




        $transfer_charge = Setting::where('id', 1)->first()->transfer_out_charge;
        $f_amount = $request->amount + $transfer_charge;








        $usr = User::where('id', Auth::id())->first();
        if($f_amount > $usr->main_wallet){

            return response()->json([

                'status' => false,
                'message' => 'Insufficient Funds',

            ], 500);

        }


        $psb_bal = wallet_balance();
        $settlement_bal = settlement();



        if($psb_bal > $f_amount ){

            $charge_account = env('DEBITACCOUNT');

            User::where('id', Auth::id())->decrement('main_wallet', $f_amount);

            $string = env('9PSBPRIKEY').$charge_account.$destinationAccountNumber.$destinationBankCode.$amount.$ref;
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
                        'senderaccountnumber' => $charge_account,
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
            curl_close($curl);
            $var = json_decode($var);
            $code = $var->code ?? null;


            if($code == "09"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Request Processing In Progress";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }


            if($code == "68"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Response was received too late";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "97"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Transaction Time out";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "96"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "System malfunction";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "98"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Failed No Response";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "99"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Request processing error";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "77"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Empty Response";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if ($code == "00") {

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->e_ref = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = $transfer_charge;
                $trasnaction->note = "Transaction Successful";
                $trasnaction->amount = $request->amount;
                $trasnaction->receiver_name = $destinationAccountName;
                $trasnaction->receiver_account_no = $destinationAccountNumber;
                $trasnaction->receiver_bank = $destinationBankCode;
                $trasnaction->balance = $balance;
                $trasnaction->status = 2;
                $trasnaction->save();


                $amount = number_format($request->amount, 2);
                return response()->json([
                    'status' => true,
                    'message' => "Transaction Successful \n NGN$amount has been sent to $destinationAccountName",
                ], 200);


            } else {

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                User::where('id', Auth::id())->increment('main_wallet', $f_amount);
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Transaction Failed";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 3;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Failed"
                ], 500);

            }


        }elseif($settlement_bal > $f_amount){


            $charge_account = env('INSTANTACCOUNT');

            User::where('id', Auth::id())->decrement('main_wallet', $f_amount);

            $string = env('9PSBPRIKEY').$charge_account.$destinationAccountNumber.$destinationBankCode.$amount.$ref;
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
                        'senderaccountnumber' => $charge_account,
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
            curl_close($curl);
            $var = json_decode($var);
            $code = $var->code ?? null;


            if($code == "09"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Request Processing In Progress";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }


            if($code == "68"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Response was received too late";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "97"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Transaction Time out";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "96"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "System malfunction";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "98"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Failed No Response";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "99"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Request processing error";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if($code == "77"){

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Empty Response";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Processing"
                ], 500);

            }

            if ($code == "00") {

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->e_ref = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = $transfer_charge;
                $trasnaction->note = "Transaction Successful";
                $trasnaction->amount = $request->amount;
                $trasnaction->receiver_name = $destinationAccountName;
                $trasnaction->receiver_account_no = $destinationAccountNumber;
                $trasnaction->receiver_bank = $destinationBankCode;
                $trasnaction->balance = $balance;
                $trasnaction->status = 2;
                $trasnaction->save();


                $amount = number_format($request->amount, 2);
                return response()->json([
                    'status' => true,
                    'message' => "Transaction Successful \n NGN$amount has been sent to $destinationAccountName",
                ], 200);


            } else {

                $balance = User::where('id', Auth::id())->first()->main_wallet;
                User::where('id', Auth::id())->increment('main_wallet', $f_amount);
                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->e_ref = $ref;
                $trasnaction->ref_trans_id = $ref;
                $trasnaction->transaction_type = "TRANSFEROUT";
                $trasnaction->debit = $f_amount;
                $trasnaction->charge = 0;
                $trasnaction->note = "Transaction Failed";
                $trasnaction->amount = $request->amount;
                $trasnaction->balance = $balance;
                $trasnaction->status = 3;
                $trasnaction->save();

                $r_amount = number_format($request->amount, 2);
                $message = "ERROR FROM ETOP AGENCY ======>".json_encode($var)."\n\n REQUEST ======> $post_data"."\n\n URL=====> $url"."\n\n STRING ====> $string";
                send_notification($message);

                return response()->json([
                    'status' => false,
                    'message' => "Transaction Failed \n Transaction Failed"
                ], 500);

            }


        }else{

                $message = "ETOP ERROR ===>>> Settlement Balance Insufficient $settlement_bal | main Balance Insufficient $psb_bal ";
                send_notification($message);
                return response()->json([

                    'status' => false,
                    'message' => "Transaction not processed \n Please try again later",

                ], 500);

        }



    }








    public function reverse(request $request){

        $trx = Transaction::where('ref_trans_id', $request->ref)->first() ?? null;

        if($trx != null){

            if($trx->status == 0){
                User::where('id', $trx->user_id)->increment('main_wallet', $trx->debit);
                    Transaction::where('ref_trans_id', $request->ref)->update(['status'=> 3]) ?? null;
                return back()->with('message', 'User has been successfully added');

            }else{
                return back()->with('error', 'Transaction has already been reversed');
            }
        }

        return back()->with('error', 'Transaction not found');


    }


}
