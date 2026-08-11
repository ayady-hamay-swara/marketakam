<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چوونەژوورەوە - سیستەمی فرۆشتن</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #007bff 0%, #1611bb 100%);
            min-height: 100vh;
            color: #2c3e50;
        }

        .login-wrapper {
            min-height: calc(100vh - 200px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: white;
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        }

        .login-title {
            font-size: 28px;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 15px;
            color: #7f8c8d;
            margin-bottom: 24px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
            border: 1px solid #dfe6e9;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.2);
        }

        .btn-login {
            width: 100%;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            margin-top: 10px;
        }

        .brand-badge {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007bff 0%, #1611bb 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>



<div class="login-wrapper">
    <div class="login-card">
        <div class="text-center">
            <div class="brand-badge">🏪</div>
            <h2 class="login-title" data-i18n="login_title">چوونەژوورەوە</h2>
            <p class="login-subtitle" data-i18n="login_subtitle">بەخێربێیت بۆ سیستەمی فرۆشتنی مارکێتەکەم</p>
        </div>

        <form id="loginform" method="post" action="/login">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="username" data-i18n="login_username">ناوی بەکارهێنەر</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" class="form-control" data-i18n="login_username_placeholder" placeholder="ناوی بەکارهێنەر" autofocus>
            </div>

            <div class="form-group">
                <label for="password" data-i18n="login_password">تێپەڕەوشە</label>
                <input type="password" id="password" name="password" class="form-control" data-i18n="login_password_placeholder" placeholder="تێپەڕەوشە">
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                <label class="form-check-label" for="rememberMe" data-i18n="login_remember">منت بیربێت</label>
            </div>

            <button type="submit" class="btn btn-primary btn-login" data-i18n="login_button">چوونەژوورەوە</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/languages.js') }}"></script>
@include('partials.footer')
</body>
</html>
