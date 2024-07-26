<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogViewerController extends Controller
{
    public function index(Request $request)
    {
        $logFile = storage_path('logs/laravel.log');
        $perPage = 100; // Number of lines per page
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;

        $logContent = '';

        if (File::exists($logFile)) {
            $lines = File::lines($logFile)->skip($offset)->take($perPage);
            foreach ($lines as $line) {
                $logContent .= $line . "\n";
            }
        }

        return view('logs.index', [
            'logContent' => $logContent,
            'currentPage' => $page,
            'totalLines' => File::lines($logFile)->count(),
            'perPage' => $perPage
        ]);
    }
}
