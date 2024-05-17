<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Models\Oldtransaction;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{


    public function all_transaction(request $request)
    {

        $all_transactions = Transaction::latest()->where('user_id', Auth::id())
            ->take('50')->get();

        return response()->json([

            'status' => true,
            'data' => $all_transactions,

        ], 200);

    }



    public function status_transaction(Request $request)
    {


        // try {

        $ref_no = $request->ref_no;

        if ($ref_no == null) {

            return response()->json([

                'status' => false,
                'message' => 'Transaction Not Found',

            ], 500);
        }


        $trx = Transaction::where('ref_trans_id', $ref_no)->first();
        $rrn = Transaction::where('ref_trans_id', $ref_no)->first()->e_ref ?? null;
        $card_pan = Transaction::where('ref_trans_id', $ref_no)->first()->sender_account_no ?? null;




        return response()->json([

            'e_ref' => $trx->p_sessionId,
            'amount' => $trx->amount,
            'receiver_bank' => $trx->receiver_bank,
            'receiver_name' => $trx->receiver_name,
            'receiver_account_no' => $trx->receiver_account_no,
            'date' => $trx->created_at,
            'note' => "$trx->ref_trans_id | $trx->note",
            'rrn' => $rrn ?? null,
            'card_pan' => $card_pan ?? null,
            'status' => $trx->status ?? null,
            'response_code' => $trx->status ?? null,
            'message' => "If receiver is not credited within 10mins, Please contact us with the EREF",
        ], 200);


    }


    public function transaction_history(request $request)
    {

        $end_date = $request->startDate;
        $start_date = $request->endDate;
        $transaction_type = $request->type;


        if ($transaction_type != null) {

            $transactions = Transaction::where([
                'user_id' => Auth::id(),
                'transaction_type ' => $transaction_type,

            ])->whereBetween('created_at', [$start_date, $end_date])->get();

            $transaction_count = Transaction::where([
                'user_id' => Auth::id(),
                'transaction_type ' => $transaction_type,

            ])->whereBetween('created_at', [$start_date, $end_date])->count();

            if ($transaction_count > 50) {

                $databody = array(
                    'from' => $request->date_from,
                    'to' => $request->date_to,
                    'id' => Auth::id()

                );

                $site_url = "https://enkpay.com/api/email-report";

                $post_data = json_encode($databody);


                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $site_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_POSTFIELDS => $post_data,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                    ),
                ));

                $var = curl_exec($curl);
                dd($var);


                curl_close($curl);


                $var = json_decode($var);
                $status = $var->status ?? null;


            } else {

                return response()->json([

                    'status' => true,
                    'data' => $transactions,

                ], 200);

            }

        }


        $from = Carbon::createFromFormat('Y-m-d', $request->startDate)->format('m');
        $transaction_ck = Carbon::now()->format('m');
        if ($transaction_ck != $from) {

            $transactions = Oldtransaction::latest()->where('user_id', Auth::id())->whereBetween('created_at', [$request->startDate . ' 00:00:00', $request->endDate . ' 23:59:59'])->get();
            $transaction_count = Oldtransaction::latest()->where('user_id', Auth::id())->whereBetween('created_at', [$request->startDate . ' 00:00:00', $request->endDate . ' 23:59:59'])->count();

            if ($transaction_count > 50) {

                $databody = array(
                    'from' => $request->startDate,
                    'to' => $request->endDate,
                    'id' => Auth::id()

                );

                $site_url = "https://enkpay.com/api/email-report";

                $post_data = json_encode($databody);


                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $site_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_POSTFIELDS => $post_data,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                    ),
                ));

                $var = curl_exec($curl);
                curl_close($curl);
                $var = json_decode($var);


                $status = $var->status ?? null;

                if ($status == true) {

                    return response()->json([

                        'status' => true,
                        'message' => $var->message,

                    ], 200);

                } else {

                    return response()->json([

                        'status' => false,
                        'message' => "Error getting report, Please try again after later.",

                    ], 500);


                }


            } else {

                return response()->json([

                    'status' => true,
                    'data' => $transactions,

                ], 200);

            }

        } else {


            $transactions = Transaction::latest()->where('user_id', Auth::id())->whereBetween('created_at', [$request->startDate . ' 00:00:00', $request->endDate . ' 23:59:59'])->get();
            $transaction_count = Transaction::latest()->where('user_id', Auth::id())->whereBetween('created_at', [$request->startDate . ' 00:00:00', $request->endDate . ' 23:59:59'])->count();

            if ($transaction_count > 50) {

                $databody = array(
                    'from' => $request->startDate,
                    'to' => $request->endDate,
                    'id' => Auth::id()

                );

                $site_url = "https://enkpay.com/api/email-report";

                $post_data = json_encode($databody);


                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $site_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_POSTFIELDS => $post_data,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                    ),
                ));

                $var = curl_exec($curl);
                curl_close($curl);
                $var = json_decode($var);


                $status = $var->status ?? null;

                if ($status == true) {

                    return response()->json([

                        'status' => true,
                        'message' => $var->message,

                    ], 200);

                } else {

                    return response()->json([

                        'status' => false,
                        'message' => "Error getting report, Please try again after later.",

                    ], 500);


                }


            }

            else{
                return response()->json([

                    'status' => true,
                    'data' => $transactions,

                ], 200);

            }

        }
    }






}
