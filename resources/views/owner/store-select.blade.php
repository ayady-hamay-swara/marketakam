<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="store_select_title">هەڵبژاردنی فرۆشگا</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #1a2b4a 0%, #0d1526 100%);
            min-height: 100vh;
            color: #2c3e50;
        }
        .wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            padding: 40px;
            max-width: 700px;
            width: 100%;
            text-align: center;
        }
        .title {
            font-size: 34px;
            font-weight: 800;
            margin-bottom: 12px;
        }
        .subtitle {
            color: #7f8c8d;
            margin-bottom: 30px;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .btn {
            min-width: 180px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <h1 class="title">هەڵبژاردنی فرۆشگا</h1>
            <p class="subtitle">ئێوە خاوەن فرۆشگایەک هەڵبژاردووە، دەتوانن بچنە ژوورەوەی سیستەم یان بگەڕێنەوە بۆ ماڵەوە.</p>

            <div class="actions">
                <a href="{{ url('/home') }}" class="btn btn-primary btn-lg">چوونە ژوورەوە بۆ ماڵەوە</a>

                <form method="POST" action="{{ route('logout') }}" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-lg">چوونەدەرەوە</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
