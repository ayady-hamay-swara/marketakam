<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پڕۆفایل - Marketakam</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
    <style>
        body { background: linear-gradient(135deg, #007bff 0%, #1611bb 100%); min-height: 100vh; }

        .profile-wrapper { padding: 40px 20px; max-width: 900px; margin: 0 auto; }

        .profile-header {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            margin-bottom: 30px;
        }

        .profile-title {
            font-size: 40px;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .profile-subtitle {
            font-size: 18px;
            color: #7f8c8d;
        }

        .profile-empty {
            background: white;
            border-radius: 16px;
            padding: 60px 30px;
            text-align: center;
            color: #7f8c8d;
            box-shadow: 0 10px 30px rgba(0,0,0,.15);
        }
    </style>
</head>
<body>

@include('partials.navbar')

<div class="profile-wrapper">

    <div class="profile-header">
        <div class="profile-title" data-i18n="profile_title">پڕۆفایلی کەسی</div>
        <div class="profile-subtitle" data-i18n="profile_subtitle">زانیاری و دۆخی کەسی</div>
    </div>

    <div class="profile-empty" data-i18n="profile_empty">هێشتا هیچ زانیارییەک لەسەر پڕۆفایلەکە دانەدرابوو.</div>

</div>

<script src="{{ asset('js/languages.js') }}"></script>
<script src="{{ asset('js/navbar-global.js') }}"></script>
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@include('partials.footer')
</body>
</html>
