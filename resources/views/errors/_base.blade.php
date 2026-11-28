<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — TaskHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f7f6f3; color: #2b2b2b; }
        .code { font-size: 5rem; font-weight: 700; color: #6d28d9; }
        .btn-purple { background-color: #6d28d9; color: #fff; border: none; }
        .btn-purple:hover { background-color: #563098; color: #fff; }
    </style>
</head>
<body>
    <div class="container text-center py-5 mt-5">
        <div class="code">{{ $code }}</div>
        <h2 class="fw-bold">{{ $title }}</h2>
        <p class="text-muted mb-4">{{ $message }}</p>
        {{-- /dashboard sends each user to their own dashboard, or to login if logged out --}}
        <a href="{{ url('/dashboard') }}" class="btn btn-purple">Back to dashboard</a>
    </div>
</body>
</html>