<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Marketakam</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
</head>
<body>

@include('manager.partials.navbar')

<div class="empty-state" style="min-height: calc(100vh - 56px)">
    @include('partials.illustration', ['name' => 'people'])
    <b>Manager View</b>
    <span>This area is ready for manager-specific pages.</span>
    <div class="mt-3 d-flex" style="gap: var(--sp-3)">
        <a href="{{ url('/home') }}" class="btn btn-primary">Go to Home</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-secondary">Logout</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/icons.js') }}"></script>
<script src="{{ asset('js/languages.js') }}"></script>
<script src="{{ asset('js/navbar-global.js') }}"></script>
</body>
</html>
