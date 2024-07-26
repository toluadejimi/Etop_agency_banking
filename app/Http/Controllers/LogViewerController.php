<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogViewerController extends Controller
{
    public function index(Request $request)
    {
        // Get the path to the log file
        $logFile = storage_path('logs/laravel.log');

        // Read the log file
        $logContent = File::exists($logFile) ? File::get($logFile) : '';

        return view('logs.index', ['logContent' => $logContent]);
    }
}
