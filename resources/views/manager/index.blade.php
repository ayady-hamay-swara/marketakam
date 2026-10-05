<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="manager_title">بەڕێوەبەر</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <style>
        body { background: #f4f6fb; }
        .placeholder-wrap {
            min-height: calc(100vh - 56px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .placeholder-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,.1);
            padding: 48px 40px;
            max-width: 480px;
            width: 100%;
            text-align: center;
        }
        .placeholder-card h1 {
            font-size: 24px;
            font-weight: 800;
            color: #1a2b4a;
            margin-bottom: 8px;
        }
        .placeholder-card p {
            color: #7f8c8d;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>

@include('manager.partials.navbar')

<div class="placeholder-wrap">
    <div class="placeholder-card">
        <h1>Manager View</h1>
        <p class="text-muted">This area is currently empty and ready for manager-specific pages.</p>
        <a href="{{ url('/home') }}" class="btn btn-primary">Go to Home</a>
        <form method="POST" action="{{ route('logout') }}" class="d-inline-block ms-2">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/languages.js') }}"></script>
<script src="{{ asset('js/navbar-global.js') }}"></script>
</body>
</html>
