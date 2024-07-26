<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Viewer</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="container">
    <h1>Log Viewer</h1>
    <pre>{{ $logContent }}</pre>

    <div class="pagination">
        @if ($currentPage > 1)
            <a href="{{ route('logs.index', ['page' => $currentPage - 1]) }}">Previous</a>
        @endif

        @if ($totalLines > ($currentPage * $perPage))
            <a href="{{ route('logs.index', ['page' => $currentPage + 1]) }}">Next</a>
        @endif
    </div>
</div>
</body>
</html>
