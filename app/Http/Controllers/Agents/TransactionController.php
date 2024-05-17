<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
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





}
