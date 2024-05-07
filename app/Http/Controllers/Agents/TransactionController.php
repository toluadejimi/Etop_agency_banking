<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
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




}
