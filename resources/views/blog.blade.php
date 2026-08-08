<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بلۆگ - Marketakam</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
    <style>
        body { background: linear-gradient(135deg, #007bff 0%, #1611bb 100%); min-height: 100vh; }

        .blog-wrapper { padding: 40px 20px; max-width: 900px; margin: 0 auto; }

        .blog-header {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            margin-bottom: 30px;
        }

        .blog-title {
            font-size: 40px;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .blog-subtitle {
            font-size: 18px;
            color: #7f8c8d;
        }

        .blog-post {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,.15);
        }

        .blog-post-title {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .blog-post-date {
            font-size: 13px;
            color: #95a5a6;
            margin-bottom: 15px;
        }

        .blog-post-body {
            font-size: 15px;
            color: #34495e;
            line-height: 1.8;
        }

        .blog-empty {
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

<div class="blog-wrapper">

    <div class="blog-header">
        <div class="blog-title" data-i18n="blog_title">بلۆگی کەسی</div>
        <div class="blog-subtitle" data-i18n="blog_subtitle">بیر و بۆچوون، پرۆژەکان و ئەزموونەکانم</div>
    </div>

    {{-- Replace this block with your real posts (from a database, markdown files, etc.) --}}
    <div class="blog-empty" data-i18n="blog_empty">هیچ بابەتێک هێشتا بڵاو نەکراوەتەوە. بەم زووانە دێتەوە!</div>

</div>

<script src="{{ asset('js/languages.js') }}"></script>
<script src="{{ asset('js/navbar-global.js') }}"></script>
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@include('partials.footer')
</body>
</html>
