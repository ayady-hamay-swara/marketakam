<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="@yield('title_key')">@yield('title_default')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gate.css') }}">
</head>
<body class="gate">
<div class="gate-shell">

    <aside class="gate-brand">
        <div class="gate-logo"><span class="mark">@include('partials.icon', ['name' => 'bag', 'size' => 18])</span>Marketakam</div>
        <div class="gate-pitch">
            <h1 data-i18n="gate_tagline">فرۆشتن، کۆگا و قەرزەکانت، لە یەک شوێن</h1>
            <p data-i18n="gate_tagline_sub">سیستەمی فرۆشتن بۆ مارکێت و دەرمانخانەکان</p>
        </div>
        <div class="gate-foot">&copy; {{ date('Y') }} Marketakam</div>
    </aside>

    <main class="gate-main">
        <div class="gate-top">
            <div class="gate-lang" id="gateLang">
                <button type="button" data-lang="ku">کوردی</button>
                <button type="button" data-lang="en">English</button>
                <button type="button" data-lang="ar">العربية</button>
            </div>
        </div>
        <div class="gate-body @yield('body_class')">
            @yield('content')
        </div>
    </main>

</div>

<script src="{{ asset('js/languages.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bar = document.getElementById('gateLang');
    const sync = () => { const c = localStorage.getItem('posLang') || 'ku';
        bar.querySelectorAll('button').forEach(b => b.classList.toggle('on', b.dataset.lang === c)); };
    bar.querySelectorAll('button').forEach(b => b.addEventListener('click', () => { setLanguage(b.dataset.lang); sync(); }));
    sync();
});
</script>
@stack('scripts')
</body>
</html>
