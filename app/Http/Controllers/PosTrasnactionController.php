<?php

namespace App\Http\Controllers;

use App\Models\PosLog;
use App\Models\SuperAgent;
use App\Models\Terminal;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PosTrasnactionController extends Controller
{

    public function get_all_transaction(request $request)
    {

        date_default_timezone_set('UTC');

        if ($request->rrn != null) {

            $SerialNo = $request->header('serialnumber');
            $data = PosLog::where('RRN', $request->rrn)->get() ?? null;
            unset($data->created_at);
            unset($data->updated_at);

            $totalSuccessAmount = PosLog::where('RRN', $request->rrn)->where('respCode', "00")->sum('amount');
            $totalFailedAmount = PosLog::where('RRN', $request->rrn)->where('respCode', "2934")->sum('amount');
            $totalTransactionAmount = $totalSuccessAmount + $totalFailedAmount;


            $mer = Terminal::where('serialNumber', $SerialNo)->first() ?? null;
            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'transaction' => [],
                    'allTransaction' => null,
                    'totalSuccessAmount' => null,
                    'totalFailedAmount' => null,
                    'totalTransactionAmount' => null,
                    'message' => "No Record Found",
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantName,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);

            } else {

                return response()->json([
                    'success' => true,
                    'transaction' => [],
                    'allTransaction' => $data,
                    'totalSuccessAmount' => $totalSuccessAmount,
                    'totalFailedAmount' => $totalFailedAmount,
                    'totalTransactionAmount' => $totalTransactionAmount,
                    'message' => null,
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantName,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);
            }


        }

        if ($request->startofday == null && $request->endofday == null) {

            $SerialNo = $request->header('serialnumber');
            $data = PosLog::latest()->where('SerialNo', $SerialNo)->take('50')->get() ?? null;
            unset($data->created_at);
            unset($data->updated_at);

            $totalSuccessAmount = PosLog::where('SerialNo', $SerialNo)->where('respCode', "00")->sum('amount');
            $totalFailedAmount = PosLog::where('SerialNo', $SerialNo)->where('respCode', "2934")->sum('amount');
            $totalTransactionAmount = $totalSuccessAmount + $totalFailedAmount;


            $mer = Terminal::where('serialNumber', $SerialNo)->first() ?? null;
            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'transaction' => [],
                    'allTransaction' => null,
                    'totalSuccessAmount' => null,
                    'totalFailedAmount' => null,
                    'totalTransactionAmount' => null,
                    'message' => "No Record Found",
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantName,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);

            } else {

                return response()->json([
                    'success' => true,
                    'transaction' => [],
                    'allTransaction' => $data,
                    'totalSuccessAmount' => $totalSuccessAmount,
                    'totalFailedAmount' => $totalFailedAmount,
                    'totalTransactionAmount' => $totalTransactionAmount,
                    'message' => null,
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantName,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);
            }


        }


        if ($request->startofday != null && $request->endofday == null) {
            $SerialNo = $request->header('serialnumber');
            $data = PosLog::latest()->where('SerialNo', $SerialNo)->whereDate('createdAt', $request->startofday)->get() ?? null;
            unset($data->created_at);
            unset($data->updated_at);

            $totalSuccessAmount = PosLog::where(['SerialNo' => $SerialNo, 'respCode' => "00"])->whereDate('createdAt', $request->startofday)->sum('amount');
            $totalFailedAmount = PosLog::where(['SerialNo' => $SerialNo, 'respCode' => "007890"])->whereDate('createdAt', $request->startofday)->sum('amount');
            $totalTransactionAmount = $totalSuccessAmount + $totalFailedAmount;

            $mer = Terminal::where('serialNumber', $SerialNo)->first() ?? null;
            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'transaction' => [],
                    'allTransaction' => null,
                    'totalSuccessAmount' => null,
                    'totalFailedAmount' => null,
                    'totalTransactionAmount' => null,
                    'message' => "No Record Found",
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantName,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);

            } else {

                return response()->json([
                    'success' => true,
                    'transaction' => [],
                    'allTransaction' => $data,
                    'totalSuccessAmount' => $totalSuccessAmount,
                    'totalFailedAmount' => $totalFailedAmount,
                    'totalTransactionAmount' => $totalTransactionAmount,
                    'message' => null,
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantName,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);
            }

        }


        if ($request->startofday == null && $request->endofday != null) {
            $SerialNo = $request->header('serialnumber');
            $data = PosLog::latest()->where('SerialNo', $SerialNo)->whereDate('createdAt', $request->endofday)->get() ?? null;
            unset($data->created_at);
            unset($data->updated_at);

            $totalSuccessAmount = PosLog::where(['SerialNo' => $SerialNo, 'respCode' => "00"])->whereDate('createdAt', $request->endofday)->sum('amount');
            $totalFailedAmount = PosLog::where(['SerialNo' => $SerialNo, 'respCode' => "007890"])->whereDate('createdAt', $request->endofday)->sum('amount');
            $totalTransactionAmount = $totalSuccessAmount + $totalFailedAmount;

            $mer = Terminal::where('serialNumber', $SerialNo)->first() ?? null;
            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'transaction' => [],
                    'allTransaction' => null,
                    'totalSuccessAmount' => null,
                    'totalFailedAmount' => null,
                    'totalTransactionAmount' => null,
                    'message' => "No Record Found",
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantName,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);

            } else {

                return response()->json([
                    'success' => true,
                    'transaction' => [],
                    'allTransaction' => $data,
                    'totalSuccessAmount' => $totalSuccessAmount,
                    'totalFailedAmount' => $totalFailedAmount,
                    'totalTransactionAmount' => $totalTransactionAmount,
                    'message' => null,
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantName,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);
            }

        }


        if ($request->startofday != null && $request->endofday != null) {
            $SerialNo = $request->header('serialnumber');
            $data = PosLog::where('SerialNo', $SerialNo)->whereBetween('createdAt', [$request->startofday . ' 00:00:00', $request->endofday . ' 23:59:59'])->get() ?? null;
            unset($data->created_at);
            unset($data->updated_at);

            $totalSuccessAmount = PosLog::where(['SerialNo' => $SerialNo, 'respCode' => "00"])->whereBetween('createdAt', [$request->startofday . ' 00:00:00', $request->endofday . ' 23:59:59'])->sum('amount');
            $totalFailedAmount = PosLog::where(['SerialNo' => $SerialNo, 'respCode' => "007890"])->whereBetween('createdAt', [$request->startofday, $request->endofday])->sum('amount');
            $totalTransactionAmount = $totalSuccessAmount + $totalFailedAmount;
            $mer = Terminal::where('serialNumber', $SerialNo)->first() ?? null;
            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'transaction' => [],
                    'allTransaction' => null,
                    'totalSuccessAmount' => null,
                    'totalFailedAmount' => null,
                    'totalTransactionAmount' => null,
                    'message' => "No Record Found",
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantName,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);

            } else {

                return response()->json([
                    'success' => true,
                    'transaction' => [],
                    'allTransaction' => $data,
                    'totalSuccessAmount' => $totalSuccessAmount,
                    'totalFailedAmount' => $totalFailedAmount,
                    'totalTransactionAmount' => $totalTransactionAmount,
                    'message' => null,
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantName,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);
            }


        }

        return response()->json([
            'success' => false,
            'transaction' => [],
            'allTransaction' => null,
            'totalSuccessAmount' => null,
            'totalFailedAmount' => null,
            'totalTransactionAmount' => null,
            'message' => "Sommthing Went Wrong",
            'mid' => null,
            'merchantDetails' => [
                'merchantName' => null,
                'serialnumber' => null,
                'mid' => null,
                'tid' => null,
                'merchantaddress' => null
            ],
            'error' => true,
        ], 200);


    }


    public function EtopPosLogs(request $request)
    {

        $SerialNo = $request->header('serialnumber');
        $account_balance = user_balance($SerialNo);
        $RRN = $request->RRN;
        $STAN = $request->STAN;
        $accountBalance = $account_balance;
        $acquiringInstitutionIdCode = $request->acquiringInstitutionIdCode;
        $authCode = $request->authCode;
        $cardCardSequenceNum = $request->cardCardSequenceNum;
        $cardExpireData = $request->cardExpireData;
        $forwardingInstCode = $request->forwardingInstCode;
        $merchantNo = $request->institutionData['merchantNo'];
        $amount = $request->institutionData['amount'];
        $accountType = $request->institutionData['accountType'];
        $merchantName = $request->institutionData['merchantName'];
        $tid = $request->institutionData['tid'];
        $pan = $request->pan;
        $pinBlock = $request->pinBlock;
        $receiptNumber = $request->receiptNumber;
        $respCode = $request->respCode;
        $responseMessage = $request->responseMessage;
        $status = $request->status;
        $successResponse = $request->successResponse;
        $systemTraceAuditNo = $request->systemTraceAuditNo;
        $terminalId = $request->terminalId;
        $transactionDate = $request->transactionDate;
        $transactionDateTime = $request->transactionDateTime;
        $transactionTime = $request->transactionTime;
        $transactionType = $request->transactionType;
        $cardName = $request->cardName;
        $userID = $request->UserID;


        if ($SerialNo == null) {
            $message = "Serial Number can not be empty";
            return error_response($message);
        }


        $SerialNo = Terminal::where('serialNumber', $SerialNo)->first()->serialNumber ?? null;
        if ($SerialNo == null) {
            $message = "No user attached to the serial number | $SerialNo";
            return error_response($message);
        }

        $rrn = PosLog::where('RRN', $request->RRN)->first()->log_status ?? null;
        if ($rrn == 1) {
            return response()->json([
                'status' => true,
                'message' => 'Transaction already successful',
            ], 422);

        }

        if ($transactionType == "PURCHASE" && $respCode == "00") {
            Terminal::where('serialNumber', $SerialNo)->increment('accountBalance', $amount);
            $Balance = $accountBalance + $amount;
        }


        // Get the current time
        $current_time = time();
        $one_hour_later = $current_time + 3600; // 3600 seconds = 1 hour
        $created_at = date('Y-m-d H:i:s'); //date('Y-m-d H:i:s', $one_hour_later);

        $user_id = Terminal::where('serialNumber', $SerialNo)->first()->user_id ?? null;
        $bank_id = Terminal::where('serialNumber', $SerialNo)->first()->bank_id ?? null;


        $trasnaction = new PosLog();
        $trasnaction->RRN = $RRN;
        $trasnaction->STAN = $STAN;
        $trasnaction->accountBalance = $Balance ?? $accountBalance;
        $trasnaction->acquiringInstitutionIdCode = $acquiringInstitutionIdCode;
        $trasnaction->authCode = $authCode;
        $trasnaction->cardCardSequenceNum = $cardCardSequenceNum;
        $trasnaction->cardExpireData = $cardExpireData;
        $trasnaction->forwardingInstCode = $forwardingInstCode;
        $trasnaction->merchantNo = $merchantNo;
        $trasnaction->amount = $amount;
        $trasnaction->accountType = $accountType;
        $trasnaction->tid = $tid;
        $trasnaction->merchantName = $merchantName;
        $trasnaction->pan = $pan;
        $trasnaction->pinBlock = $pinBlock;
        $trasnaction->receiptNumber = $receiptNumber;
        $trasnaction->respCode = $respCode;
        $trasnaction->responseMessage = $responseMessage;
        $trasnaction->status = $status;
        $trasnaction->successResponse = $successResponse;
        $trasnaction->systemTraceAuditNo = $systemTraceAuditNo;
        $trasnaction->terminalId = $terminalId;
        $trasnaction->transactionDate = $transactionDate;
        $trasnaction->transactionDateTime = $transactionDateTime;
        $trasnaction->transactionTime = $transactionTime;
        $trasnaction->transactionType = $transactionType;
        $trasnaction->cardName = $cardName;
        $trasnaction->SerialNo = $SerialNo;
        $trasnaction->createdAt= $created_at;
        $trasnaction->updatedAt= $created_at;
        $trasnaction->user_id= $user_id;
        $trasnaction->bank_id= $bank_id;

        $trasnaction->save();


        try {

            $curl = curl_init();
            $data = array(

                'RRN' => $RRN,
                'STAN' => $STAN,
                'accountBalance' => $Balance ?? $accountBalance,
                'acquiringInstitutionIdCode' => $acquiringInstitutionIdCode,
                'authCode' => $authCode,
                'cardCardSequenceNum' => $cardCardSequenceNum,
                'cardExpireData' => $cardExpireData,
                'forwardingInstCode' => $forwardingInstCode,
                'merchantNo' => $merchantNo,
                'amount' => $amount,
                'accountType' => $accountType,
                'tid' => $tid,
                'merchantName' => $merchantName,
                'pan' => $pan,
                'pinBlock' => $pinBlock,
                'receiptNumber' => $receiptNumber,
                'respCode' => $respCode,
                'responseMessage' => $responseMessage,
                'status' => $status,
                'successResponse' => $successResponse,
                'systemTraceAuditNo' => $systemTraceAuditNo,
                'terminalId' => $terminalId,
                'transactionDate' => $transactionDate,
                'transactionDateTime' => $transactionDateTime,
                'transactionTime' => $transactionTime,
                'transactionType' => $transactionType,
                'cardName' => $cardName,
                'SerialNo' => $SerialNo,
                'createdAt' => $created_at,
                'updatedAt' => $created_at,
                'user_id' => $user_id,
                'bank__id' => $bank_id,

            );

            $post_data = json_encode($data);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://etopmerchant.com/api/store-transaction',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $var = curl_exec($curl);
            curl_close($curl);


        } catch (QueryException $e) {
            echo "$e";
        }


        $mer = Terminal::where('serialNumber', $SerialNo)->first() ?? null;


        return response()->json([
            'status' => true,
            'message' => "Transaction initiated successfully",
        ], 200);
    }

    public function EtopPos(request $request)
    {

        $SerialNo = $request->header('serialnumber');
        $account_balance = user_balance($SerialNo);
        $RRN = $request->RRN;
        $STAN = $request->STAN;
        $accountBalance = $account_balance;
        $acquiringInstitutionIdCode = $request->acquiringInstitutionIdCode;
        $authCode = $request->authCode;
        $cardCardSequenceNum = $request->cardCardSequenceNum;
        $cardExpireData = $request->cardExpireData;
        $forwardingInstCode = $request->forwardingInstCode;
        $merchantNo = $request->institutionData['merchantNo'];
        $amount = $request->institutionData['amount'];
        $accountType = $request->institutionData['accountType'];
        $merchantName = $request->institutionData['merchantName'];
        $tid = $request->institutionData['tid'];
        $pan = $request->pan;
        $pinBlock = $request->pinBlock;
        $receiptNumber = $request->receiptNumber;
        $respCode = $request->respCode;
        $responseMessage = $request->responseMessage;
        $status = $request->status;
        $successResponse = $request->successResponse;
        $systemTraceAuditNo = $request->systemTraceAuditNo;
        $terminalId = $request->terminalId;
        $transactionDate = $request->transactionDate;
        $transactionDateTime = $request->transactionDateTime;
        $transactionTime = $request->transactionTime;
        $transactionType = $request->transactionType;
        $cardName = $request->cardName;
        $userID = $request->UserID;


        if ($SerialNo == null) {
            $message = "Serial Number can not be empty";
            return error_response($message);
        }

        $rrn = PosLog::where('RRN', $request->RRN)->first()->log_status ?? null;
        if ($rrn == 1) {
            return response()->json([
                'status' => true,
                'message' => 'Transaction already successful',
            ], 422);

        }


        $SerialNo = Terminal::where('serialNumber', $SerialNo)->first()->serialNumber ?? null;
        if ($SerialNo == null) {
            $message = "No user attached to the serial number | $SerialNo";
            return error_response($message);
        }


        $trx = PosLog::where('RRN', $request->RRN)->where('log_status', 0)->update([
            'log_status' => 1,
        ]) ?? null;

        $user_id = Terminal::where('serialNumber', $SerialNo)->first()->user_id ?? null;
        $bank_id = Terminal::where('serialNumber', $SerialNo)->first()->bank_id ?? null;


        // Get the current time
        $current_time = time();
        $one_hour_later = $current_time + 3600; // 3600 seconds = 1 hour
        $created_at = date('Y-m-d H:i:s', $one_hour_later);


        $trasnaction = new PosLog();
        $trasnaction->RRN = $RRN;
        $trasnaction->STAN = $STAN;
        $trasnaction->accountBalance = $accountBalance;
        $trasnaction->acquiringInstitutionIdCode = $acquiringInstitutionIdCode;
        $trasnaction->authCode = $authCode;
        $trasnaction->cardCardSequenceNum = $cardCardSequenceNum;
        $trasnaction->cardExpireData = $cardExpireData;
        $trasnaction->forwardingInstCode = $forwardingInstCode;
        $trasnaction->merchantNo = $merchantNo;
        $trasnaction->amount = $amount;
        $trasnaction->accountType = $accountType;
        $trasnaction->tid = $tid;
        $trasnaction->merchantName = $merchantName;
        $trasnaction->pan = $pan;
        $trasnaction->pinBlock = $pinBlock;
        $trasnaction->receiptNumber = $receiptNumber;
        $trasnaction->respCode = $respCode;
        $trasnaction->responseMessage = $responseMessage;
        $trasnaction->status = $status;
        $trasnaction->successResponse = $successResponse;
        $trasnaction->systemTraceAuditNo = $systemTraceAuditNo;
        $trasnaction->terminalId = $terminalId;
        $trasnaction->transactionDate = $transactionDate;
        $trasnaction->transactionDateTime = $transactionDateTime;
        $trasnaction->transactionTime = $transactionTime;
        $trasnaction->transactionType = $transactionType;
        $trasnaction->cardName = $cardName;
        $trasnaction->SerialNo = $SerialNo;
        $trasnaction->createdAt= $created_at;
        $trasnaction->updatedAt= $created_at;
        $trasnaction->user_id= $user_id;
        $trasnaction->bank_id= $bank_id;
        $trasnaction->save();


//        try {
//
//            $curl = curl_init();
//            $data = array(
//
//                'RRN' => $RRN,
//                'STAN' => $STAN,
//                'accountBalance' => $Balance ?? $accountBalance,
//                'acquiringInstitutionIdCode' => $acquiringInstitutionIdCode,
//                'authCode' => $authCode,
//                'cardCardSequenceNum' => $cardCardSequenceNum,
//                'cardExpireData' => $cardExpireData,
//                'forwardingInstCode' => $forwardingInstCode,
//                'merchantNo' => $merchantNo,
//                'amount' => $amount,
//                'accountType' => $accountType,
//                'tid' => $tid,
//                'merchantName' => $merchantName,
//                'pan' => $pan,
//                'pinBlock' => $pinBlock,
//                'receiptNumber' => $receiptNumber,
//                'respCode' => $respCode,
//                'responseMessage' => $responseMessage,
//                'status' => $status,
//                'successResponse' => $successResponse,
//                'systemTraceAuditNo' => $systemTraceAuditNo,
//                'terminalId' => $terminalId,
//                'transactionDate' => $transactionDate,
//                'transactionDateTime' => $transactionDateTime,
//                'transactionTime' => $transactionTime,
//                'transactionType' => $transactionType,
//                'cardName' => $cardName,
//                'SerialNo' => $SerialNo,
//                'createdAt' => $created_at,
//                'updatedAt' => $created_at,
//                'user_id' => $user_id,
//                'bank_id' => $bank_id,

//            );
//
//            $post_data = json_encode($data);
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => 'https://etopmerchant.com/api/store-transaction',
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => '',
//                CURLOPT_MAXREDIRS => 10,
//                CURLOPT_TIMEOUT => 0,
//                CURLOPT_FOLLOWLOCATION => true,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => 'POST',
//                CURLOPT_POSTFIELDS => $post_data,
//                CURLOPT_HTTPHEADER => array(
//                    'Content-Type: application/json'
//                ),
//            ));
//
//            $var = curl_exec($curl);
//            curl_close($curl);
//
//
//        } catch (QueryException $e) {
//            echo "$e";
//        }

        $mer = Terminal::where('serialNumber', $SerialNo)->first() ?? null;


        return response()->json([
            'newTransaction' => [
                'success' => true,
                'transaction' => $trasnaction,
            ],
            'merchantName' => $mer->merchantName,
            'mid' => $mer->mid,
            'allTransaction' => null,
            'message' => "Transaction initiated successfully",
            'merchantDetails' => [
                'merchantName' => $mer->merchantName,
                'serialnumber' => $mer->serialNumber,
                'mid' => $mer->mid,
                'tid' => $mer->tid,
                'merchantaddress' => $mer->merchantaddress
            ],
        ], 200);
    }

    public function get_logged_data(request $request)
    {

        $SerialNo = $request->header('serialnumber');
        $account_balance = user_balance($SerialNo);
        $RRN = $request->RRN;

        if ($SerialNo == null) {
            $message = "Serial Number can not be empty";
            return error_response($message);
        }

        $data = PosLog::select('terminalID', 'amount', 'transactionType', 'RRN', 'pan', 'cardName', 'serialNO', 'respCode', 'respCode', 'createdAt', 'updatedAt')
            ->where('RRN', $RRN)->first() ?? null;

        if ($data == null) {
            $message = 'Transaction not found';
            error_response($message);
        }


        return response()->json([
            'status' => true,
            'loggedData' => $data,
            'allLoggedData' => null,
            'error' => null
        ], 200);


    }

    public function get_all_by_serial_logged_data(request $request)
    {

        $SerialNo = $request->header('serialnumber');
        $account_balance = user_balance($SerialNo);


        $data = PosLog::select('Id', 'terminalID', 'amount', 'transactionType', 'RRN', 'pan', 'cardName', 'serialNO', 'responseCode',
            'respCode')->where('SerialNo', $SerialNo)->get() ?? null;

        if ($data == null) {
            $message = 'Transaction not found';
            error_response($message);
        }

        return response()->json([
            'success' => true,
            'loggedData' => null,
            'allLoggedData' => $data,
            'error' => null,
        ], 200);


    }


    public function get_all_transaction_by_filter(request $request)
    {

        $SerialNo = $request->header('serialnumber');
        $rrn = $request->rrn;
        $amount = $request->amount;
        $startofday = $request->startofday;
        $endofday = $request->endofday;
        $page = $request->page;
        $limit = $request->limit;
        if ($limit == null) {
            $limit = 200;
        } else {
            $limit = $request->limit;
        }


        $transactionType = $request->transactionType;
        $mer = Terminal::where('serialNumber', $SerialNo)->first() ?? null;
        if ($rrn != null && $amount != null && $startofday != null && $endofday != null) {

            $data = PosLog::whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                ->where([
                    'RRN' => $rrn,
                    'amount' => $amount,
                ])->take($limit)->get() ?? null;


            if ($data->isEmpty()) {

                return response()->json([
                    'success' => false,
                    'transaction' => [],
                    'allTransaction' => null,
                    'message' => null,
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantName,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);


            }

            return response()->json([
                'success' => false,
                'transaction' => $data,
                'allTransaction' => null,
                'message' => null,
                'mid' => $mer->mid,
                'merchantDetails' => [
                    'merchantName' => $mer->merchantName,
                    'serialnumber' => $SerialNo,
                    'mid' => $mer->mid,
                    'tid' => $mer->tid,
                    'merchantaddress' => $mer->merchantaddress
                ],
                'error' => null,
            ], 200);


        }

        if ($startofday != null && $endofday != null) {


            $data = Transaction::whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                ->where('SerialNo', $SerialNo)->get() ?? null;

            if ($data->isEmpty()) {

                return response()->json([
                    'success' => false,
                    'transaction' => [],
                    'allTransaction' => null,
                    'message' => null,
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantName,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);


            }

            if ($data == null) {

                return response()->json([
                    'success' => false,
                    'transaction' => [],
                    'allTransaction' => null,
                    'message' => null,
                    'mid' => $mer->mid,
                    'merchantDetails' => [
                        'merchantName' => $mer->merchantDetails,
                        'serialnumber' => $SerialNo,
                        'mid' => $mer->mid,
                        'tid' => $mer->tid,
                        'merchantaddress' => $mer->merchantaddress
                    ],
                    'error' => null,
                ], 200);


            }


            return response()->json([
                'success' => true,
                'transaction' => $data,
                'allTransaction' => null,
                'message' => null,
                'mid' => $mer->mid,
                'merchantDetails' => [
                    'merchantName' => $mer->merchantName,
                    'serialnumber' => $SerialNo,
                    'mid' => $mer->mid,
                    'tid' => $mer->tid,
                    'merchantaddress' => $mer->merchantaddress
                ],
                'error' => null,
            ], 200);

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
        $transaction = Transaction::select('e_ref', 'amount', 'sender_name', 'createdAt', 'status')->where('user_id', $request->user_id)->whereDate('created_at', $today)->get();
        $terminalNo = Terminal::where('user_id', $request->user_id)->first()->serial_no;
        $merchantName = Terminal::where('user_id', $request->user_id)->first()->merchantName;
        $merchantNo = Terminal::where('user_id', $request->user_id)->first()->merchantNo;
        $totalTransaction = Transaction::where('user_id', $request->user_id)->whereDate('createdAt', $today)->count();
        $totalSuccess = Transaction::whereDate('createdAt', $today)
            ->where([
                'user_id' => $request->user_id,
                'status' => 1
            ])->count();


        $totalFail = Transaction::whereDate('createdAt', $today)
            ->where([
                'user_id' => $request->user_id,
                'status' => 4
            ])->count();

        $totalPurchaseAmount = Transaction::whereDate('createdAt', $today)
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
