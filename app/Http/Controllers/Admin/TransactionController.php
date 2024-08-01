<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosLog;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    public function search_transactions(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $rrn = $request->rrn;
            $startofday = $request->from;
            $endofday = $request->to;
            $transaction_type = $request->transaction_type;
            $status = $request->status;

            if($startofday != null && $endofday == null &&  $rrn == null && $transaction_type == null && $status == null){
                $all_transactions = Transaction::latest()->take(50000)->where('created_at', $startofday)->paginate('50') ?? null;
                $total = Transaction::where('created_at', $startofday)->sum('credit') ?? 0;
                return view('all-transactions', compact('all_transactions', 'total'));

            }


            if($startofday != null && $endofday != null &&  $rrn == null && $transaction_type == null && $status == null){
                $all_transactions = Transaction::latest()->take(50000)->whereBetween('created_at', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])->paginate('50') ?? null;
                $total = Transaction::whereBetween('created_at', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])->sum('credit') ?? 0;
                return view('all-transactions', compact('all_transactions', 'total'));

            }

            if($startofday != null && $endofday != null &&  $rrn == null && $transaction_type != null && $status == null){
                $all_transactions = Transaction::latest()->take(50000)->whereBetween('created_at', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])->
                    where('transaction_type', $transaction_type)->paginate('50') ?? null;

                $total = Transaction::whereBetween('created_at', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])->
                where('transaction_type', $transaction_type)->sum('credit') ?? 0;
                return view('all-transactions', compact('all_transactions', 'total'));

            }

            if($startofday != null && $endofday != null &&  $rrn == null && $transaction_type == null && $status != null){
                $all_transactions = Transaction::latest()->take(50000)->whereBetween('created_at', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])->
                where('status', $status)->paginate('50') ?? null;

                $total = Transaction::whereBetween('created_at', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])->
                where('status', $status)->sum('credit') ?? 0;
                return view('all-transactions', compact('all_transactions', 'total'));

            }


            if($startofday == null && $endofday == null &&  $rrn != null && $transaction_type == null && $status == null){
                $all_transactions = Transaction::where('rrn', $rrn)->paginate('50') ?? null;
                $total = Transaction::where('rrn', $rrn)->sum('credit') ?? 0;
                return view('all-transactions', compact('all_transactions', 'total'));
            }

            if($startofday == null && $endofday == null &&  $rrn == null && $transaction_type == null && $status != null){
                $all_transactions = Transaction::latest()->where('status', $status)->take(50000)->paginate('50') ?? null;

                $total = Transaction::where('status', $status)->sum('credit') ?? 0;
                return view('all-transactions', compact('all_transactions', 'total'));
            }

            if($startofday == null && $endofday == null &&  $rrn == null && $transaction_type != null && $status == null){
                $all_transactions = Transaction::latest()->take(50000)->where('transaction_type', $transaction_type)->paginate('50') ?? null;
                $total = Transaction::where('transaction_type', $transaction_type)->sum('credit') ?? 0;
                return view('all-transactions', compact('all_transactions', 'total'));
            }


            if($startofday != null && $endofday != null &&  $rrn == null && $transaction_type != null && $status != null){
                $all_transactions = Transaction::latest()->take(50000)->whereBetween('created_at', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])->
                where([
                    'status' => $status,
                    'transaction_type' => $transaction_type,
                ])->paginate('50') ?? null;


                $total = Transaction::whereBetween('created_at', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])->
                where([
                    'status' => $status,
                    'transaction_type' => $transaction_type,
                ])->sum('credit') ?? 0;
                return view('all-transactions', compact('all_transactions', 'total'));
            }


            if($startofday == null && $endofday == null &&  $rrn == null && $transaction_type != null && $status != null){
                $all_transactions = Transaction::latest()->take(50000)->where([
                    'status' => $status,
                    'transaction_type' => $transaction_type,
                ])->paginate('50') ?? null;


                $total = Transaction::where([
                    'status' => $status,
                    'transaction_type' => $transaction_type,
                ])->sum('credit') ?? 0;
                return view('all-transactions', compact('all_transactions', 'total'));
            }

            return back()->with('error', 'Select a field');



        }






    }





    public function export_transactions(request $request)
    {


        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $rrn = $request->rrn;
            $startofday = $request->from;
            $endofday = $request->to;

            $type = $request->type;






            if ($startofday != null && $endofday != null) {

                $data = PosLog::latest()->whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday == null && $rrn != null) {

                $data = PosLog::where('RRN', $rrn)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }


            if ($startofday != null && $endofday == null) {

                $data = PosLog::latest()->whereDate('createdAt', $startofday)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday != null) {

                $data = PosLog::latest()->wheredate('createdAt', $endofday)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }




            if ($rrn != null && $startofday != null && $endofday != null) {

                $data = PosLog::whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->where([
                        'RRN' => $rrn,
                    ])->get() ?? null;


                if ($data->isEmpty()) {

                    return response()->json([
                        'success' => true,
                        'data' => [],
                    ], 200);


                }

                return response()->json([
                    'success' => false,
                    'transaction' => "No data Found",

                ], 200);


            }

        }


        if (Auth::user()->role == 3) {

            $rrn = $request->rrn;
            $startofday = $request->from;
            $endofday = $request->to;



            $type = $request->type;



            if ($startofday != null && $endofday != null) {

                $data = PosLog::latest()->whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->where('bank_id', Auth::user()->bank_id)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday == null && $rrn != null) {

                $data = PosLog::where('RRN', $rrn)->where('bank_id', Auth::user()->bank_id)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }


            if ($startofday != null && $endofday == null) {

                $data = PosLog::latest()->whereDate('createdAt', $startofday)->where('bank_id', Auth::user()->bank_id)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday != null) {

                $data = PosLog::latest()->wheredate('createdAt', $endofday)->where('bank_id', Auth::user()->bank_id)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }




            if ($rrn != null && $startofday != null && $endofday != null) {

                $data = PosLog::whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->where([
                        'RRN' => $rrn,
                        'bank_id' => Auth::user()->bank_id
                    ])->get() ?? null;


                if ($data->isEmpty()) {

                    return response()->json([
                        'success' => true,
                        'data' => [],
                    ], 200);


                }

                return response()->json([
                    'success' => false,
                    'transaction' => "No data Found",

                ], 200);


            }

        }



        return response()->json([
            'success' => true,
            'transaction' => [],

        ], 200);

    }

    public function get_all_transactions()
    {


        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $data['all_transactions'] = Transaction::latest()->take(500)->paginate(10);
            return view('all-transactions', $data);


        }else{
            return back()->with('error', 'You do not have permission');
        }






    }


    public function get_transactions_by_filter(request $request, $limit)
    {


        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $rrn = $request->rrn;
            $startofday = $request->from;
            $endofday = $request->to;



            if ($limit == null) {
                $limit1 = 50;
            } else {
                $limit1 = $limit;
            }
            $type = $request->type;






            if ($startofday != null && $endofday != null) {

                $data = PosLog::latest()->whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->take($limit1)->paginate(10) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday == null && $rrn != null) {

                $data = PosLog::where('RRN', $rrn)->take($limit)->paginate(10) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }


            if ($startofday != null && $endofday == null) {

                $data = PosLog::latest()->whereDate('createdAt', $startofday)->take($limit)->paginate(10) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday != null) {

                $data = PosLog::latest()->wheredate('createdAt', $endofday)->take($limit)->paginate(10) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }




            if ($rrn != null && $startofday != null && $endofday != null) {

                $data = PosLog::whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->where([
                        'RRN' => $rrn,
                    ])->take($limit)->paginate(10) ?? null;


                if ($data->isEmpty()) {

                    return response()->json([
                        'success' => true,
                        'data' => [],
                    ], 200);


                }

                return response()->json([
                    'success' => false,
                    'transaction' => "No data Found",

                ], 200);


            }

        }


        if (Auth::user()->role == 3) {

            $rrn = $request->rrn;
            $startofday = $request->from;
            $endofday = $request->to;


            if ($limit == null) {
                $limit1 = 50;
            } else {
                $limit1 = $limit;
            }
            $type = $request->type;



            if ($startofday != null && $endofday != null) {

                $data = PosLog::latest()->whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->take($limit1)->where('bank_id', Auth::user()->bank_id)->paginate(10) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday == null && $rrn != null) {

                $data = PosLog::where('RRN', $rrn)->where('bank_id', Auth::user()->bank_id)->take($limit)->paginate(10) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }


            if ($startofday != null && $endofday == null) {

                $data = PosLog::latest()->whereDate('createdAt', $startofday)->where('bank_id', Auth::user()->bank_id)->take($limit)->paginate(10) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday != null) {

                $data = PosLog::latest()->wheredate('createdAt', $endofday)->where('bank_id', Auth::user()->bank_id)->take($limit)->paginate(10) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }




            if ($rrn != null && $startofday != null && $endofday != null) {

                $data = PosLog::whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->where([
                        'RRN' => $rrn,
                         'bank_id' => Auth::user()->bank_id
                    ])->take($limit)->paginate(10) ?? null;


                if ($data->isEmpty()) {

                    return response()->json([
                        'success' => true,
                        'data' => [],
                    ], 200);


                }

                return response()->json([
                    'success' => false,
                    'transaction' => "No data Found",

                ], 200);


            }

        }



        return response()->json([
            'success' => true,
            'transaction' => [],

        ], 200);


    }


    public function session_check(request $request)
    {
        $session = Transaction::where('sessionId', $request->session_id)->first()->amount ?? null;

        if($session != null){
            return 1;
        }else{
            return 0;
        }


    }

}
