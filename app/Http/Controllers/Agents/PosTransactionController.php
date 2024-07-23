<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Models\PosLog;
use App\Models\Setting;
use App\Models\Terminal;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class PosTransactionController extends Controller
{


    public function PosLogs(request $request)
    {

        $key = $request->header('dataKey');
        $RRN = $request->RRN;
        $STAN = $request->STAN;
        $serialNO = $request->serialNO;
        $amount = $request->amount;
        $expireDate = $request->expireDate;
        $message = $request->message;
        $pan = $request->pan;
        $responseCode = $request->respCode;
        $terminalID = $request->terminalID;
        $transactionType = $request->transactionType;
        $cardName = $request->cardName;
        $userID = $request->UserID;
        $DataKey = env('DATAKEY');


        if ($key == null) {

            $result = "No Key Passed";
            send_notification($result);

            return response()->json([
                'status' => false,
                'message' => 'Empty Key',
            ], 500);
        }


        if ($key != $DataKey) {

            $result = "Invalid Key | $key";
            send_notification($result);

            return response()->json([
                'status' => false,
                'message' => 'Invalid Request',
            ], 500);
        }


        $userID = Terminal::where('serial_no', $serialNO)->first()->user_id ?? null;
        if ($userID == null) {

            $result = "No user found | for this serial $serialNO";
            send_notification($result);

            return response()->json([
                'status' => false,
                'message' => "No user found with this serial | $serialNO",
            ], 500);
        }


        $rrn = PosLog::where('e_ref', $RRN)->first()->e_ref ?? null;

        if ($rrn == $RRN) {

            return response()->json([
                'status' => false,
                'message' => "Transaction already exist",
            ], 500);
        }


        $key = $request->header('dataKey');
        $RRN = $request->RRN;
        $STAN = $request->STAN;
        $serialNO = $request->serialNO;
        $amount = $request->amount;
        $expireDate = $request->expireDate;
        $message = $request->message;
        $pan = $request->pan;
        $responseCode = $request->respCode;
        $terminalID = $request->terminalID;
        $transactionType = $request->transactionType;
        $cardName = $request->cardName;
        $userID = $request->UserID;
        $DataKey = env('DATAKEY');


        //update Transactions
        $trasnaction = new PosLog();
        $trasnaction->user_id = $userID;
        $trasnaction->e_ref = $RRN;
        $trasnaction->cardName = $cardName;
        $trasnaction->STAN = $STAN;
        $trasnaction->serialNO = $serialNO;
        $trasnaction->expireDate = $expireDate;
        $trasnaction->responseCode = $responseCode;
        $trasnaction->transactionType = $transactionType;
        $trasnaction->note = $message;
        $trasnaction->pan = $pan;
        $trasnaction->terminalID = $terminalID;
        $trasnaction->amount = $amount;
        $trasnaction->status = 1;

        $trasnaction->save();

        return response()->json([
            'status' => true,
            'message' => 'Log saved Successfully',
        ], 200);
    }

    public function Pos(request $request)
    {


        $key = $request->header('dataKey');
        $RRN = $request->RRN;
        $userID = $request->UserID;
        $serialNO = $request->serialNO;
        $STAN = $request->STAN;
        $amount = $request->amount;
        $expireDate = $request->expireDate;
        $message = $request->responseMessage;
        $pan = $request->pan;
        $responseCode = $request->respCode;
        $terminalID = $request->terminalID;
        $transactionType = $request->transactionType;
        $cardName = $request->cardName;
        $DataKey = env('DATAKEY');
        $amount = PosLog::where('e_ref', $RRN)->first()->amount ?? null;


        if ($key == null) {

            $result = "No Key Passed";
            send_notification($result);

            return response()->json([
                'status' => false,
                'message' => 'Empty Key',
            ], 500);
        }


        if ($key != $DataKey) {

            $result = "Invalid Key | $key";
            send_notification($result);

            return response()->json([
                'status' => false,
                'message' => 'Invalid Request',
            ], 500);
        }

       $ck_trx =  PosLog::where('e_ref', $RRN)->first() ?? null;

        if($ck_trx->status == 2){
            return response()->json([
                'status' => false,
                'message' => "Duplicate Transaction",
            ], 500);

        }


        $userID = Terminal::where('serial_no', $serialNO)->first()->user_id ?? null;
        if ($userID == null) {

            $result = "No user found | for this serial $serialNO";
            send_notification($result);

            return response()->json([
                'status' => false,
                'message' => "No user found with this serial | $serialNO",
            ], 500);
        }


        $trans_id = "EPOS" . reference();
        $pos_charge = Setting::where('id', 1)->first()->pos_charge;
        $cap = Setting::where('id', 1)->first()->cap;
        $user_id = $userID;
        $main_wallet = User::where('id', $user_id)
            ->first()->main_wallet ?? null;

        $type = User::where('id', $user_id)
            ->first()->type ?? null;


        if ($main_wallet == null && $user_id == null) {

            return response()->json([
                'status' => false,
                'message' => 'Customer not registered on Enkpay',
            ], 500);
        }

        //Both Commission
        $amount1 = $pos_charge / 100;
        $amount2 = $amount1 * $amount;
        $charge = round($amount2, 2);

        if ($charge >= $cap) {
            $w_amount = $amount - $cap;
            $echarge = $cap;

        } else {
            $w_amount = $amount - $charge;
            $echarge = $charge;
        }


        if ($responseCode == 00 && $transactionType == "PURCHASE") {
            User::where('id', $user_id)->increment('main_wallet', $w_amount);
            PosLog::where('e_ref', $RRN)->update([
                'status' => 1,
                'note' => "Successful | $pan | $amount"
            ]);

            $balance = User::where('id', $user_id)->first()->main_wallet;

            //update Transactions
            $trasnaction = new Transaction();
            $trasnaction->user_id = $user_id;
            $trasnaction->ref_trans_id = $trans_id;
            $trasnaction->e_ref = $RRN;
            $trasnaction->transaction_type = $transactionType;
            $trasnaction->credit = round($w_amount, 2);
            $trasnaction->charge = $echarge;
            $trasnaction->note = "Successful | $cardName | $pan | $STAN";
            $trasnaction->amount = $amount;
            $trasnaction->balance = $balance;
            $trasnaction->serial_no = $terminalID;
            $trasnaction->status = 2;
            $trasnaction->save();


            $f_name = User::where('id', $user_id)->first()->first_name ?? null;
            $l_name = User::where('id', $user_id)->first()->last_name ?? null;

            $ip = $request->ip();
            $amount4 = number_format($w_amount, 2);
            $result = "ETOP POS FUNDED " . $f_name . " " . $l_name . "| fund NGN " . $amount4 . " | using ETOP POS" . "\n\nIP========> " . $ip;
            send_notification($result);


            try {

                $Url = env('9PSTRANSFERURL');
                $token = psb_token();
                $string = env('9PSBPRIKEY') . $RRN . $serialNO . "00" . number_format($amount, 2) . number_format($w_amount, 2);
                $hash = hash('sha512', $string);

                $data = array(
                    'referenceno' => $RRN,
                    'terminalid' => $serialNO,
                    'transactionamount' => number_format($request->amount,2, '.', ''),
                    'merchantservicechargepercent' => "0.00",
                    'merchantservicechargeamount' => number_format($echarge, 2, '.', ''),
                    'transactionamountlessmsc' => number_format($w_amount, 2, '.', ''),
                    'responsecode' => "00",
                    'hash' => strtoupper($hash)
                );
                $post_data = json_encode($data);

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "$Url/merchant/pssp/instantsettlement",
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
                $status = $var->code ?? null;


                if($status != 00){
                    $parametersJson = "E-TOP ERROR ===> ". json_encode($var);
                    send_notification($parametersJson);
                }


            } catch (\Exception $th) {
                $parametersJson = $th->getMessage();
                send_notification($parametersJson);
            }




            return response()->json([
                'status' => true,
                'message' => 'Transaction Successful',
            ], 200);



        } else {

            //update Transactions

            PosLog::where('e_ref', $RRN)->update([

                'status' => 4,
                'note' => "Failed | $message"

            ]);


            $balance = User::where('id', $user_id)->first()->main_wallet;

            $trasnaction = new Transaction();
            $trasnaction->user_id = $user_id;
            $trasnaction->ref_trans_id = $trans_id;
            $trasnaction->e_ref = $RRN;
            $trasnaction->transaction_type = $transactionType;
            $trasnaction->credit = 0;
            $trasnaction->charge = 0;
            $trasnaction->note = "$message | $cardName | $pan | $message";
            $trasnaction->amount = $amount;
            $trasnaction->balance = $balance;
            $trasnaction->serial_no = $terminalID;
            $trasnaction->status = 4;
            $trasnaction->save();

            $f_name = User::where('id', $user_id)->first()->first_name ?? null;
            $l_name = User::where('id', $user_id)->first()->last_name ?? null;

            $ip = $request->ip();
            $amount4 = number_format($w_amount, 2);
            $message = $f_name . " " . $l_name . "| fund NGN " . $amount . " | Failed on ENKPAY POS" . "\n\nIP========> " . $ip;
            $parametersJson = json_encode($request->all());
            $result = "Body========> " . $parametersJson . "\n\n Message========> " . $message . "\n\nIP========> " . $ip;
            send_notification($result);


            return response()->json([
                'status' => false,
                'message' => 'Transaction Failed',
            ], 500);
        }

    }


    public function eod_transactions(request $request)
    {


        if ($request->date == null || $request->user_id == null) {


            return response()->json([
                'status' => false,
                'message' => "Date or User_id Can not be null"

            ], 500);
        }


        $today = $request->date;
        $transaction = Transaction::select('e_ref', 'note', 'transaction_type', 'amount', 'created_at', 'status')->where('user_id', $request->user_id)->whereDate('created_at', $today)->get();
        $terminalNo = Terminal::where('user_id', $request->user_id)->first()->serial_no;
        $merchantName = Terminal::where('user_id', $request->user_id)->first()->merchantName;
        $merchantNo = Terminal::where('user_id', $request->user_id)->first()->merchantNo;
        $totalTransaction = Transaction::where('user_id', $request->user_id)->whereDate('created_at', $today)->count();
        $totalSuccess = Transaction::whereDate('created_at', $today)
            ->where([
                'user_id' => $request->user_id,
                'status' => 1
            ])->count();


        $totalFail = Transaction::whereDate('created_at', $today)
            ->where([
                'user_id' => $request->user_id,
                'status' => 4
            ])->count();

        $totalPurchaseAmount = Transaction::whereDate('created_at', $today)
            ->where([
                'user_id' => $request->user_id,
                'status' => 1
            ])->sum('amount');


        return response()->json([
            'status' => true,
            'reportDatetime' => date('Y-m-d h:i:s'),
            'terminalNo' => $terminalNo,
            'merchantName' => $merchantName,
            'merchantNo' => $merchantNo,
            'totalTransaction' => (int)$totalTransaction,
            'totalSuccess' => $totalSuccess,
            'totalFail' => $totalFail,
            'totalPurchaseAmount' => $totalPurchaseAmount,
            'transaction' => $transaction

        ], 200);
    }


}
