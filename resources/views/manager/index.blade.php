<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager View</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>
<body>
    @include('manager.partials.global-partial')

    <div class="container mt-5 text-center">
        <div class="card shadow-sm p-5">
            <h1>Manager View</h1>
            <p class="text-muted">This area is currently empty and ready for manager-specific pages.</p>
            <a href="{{ url('/home') }}" class="btn btn-primary">Go to Home</a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline-block ms-2">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
