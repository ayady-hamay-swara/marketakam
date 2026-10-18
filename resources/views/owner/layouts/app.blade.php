<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $store = \App\Models\Store::find(auth()->user()->current_store_id ?? 1);
        $storeName = $store ? $store->name : 'Marketakam';
    @endphp
    <title>@yield('title_default', 'Owner') - {{ $storeName }}</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owner-layout.css') }}">
</head>
<body class="ow">

@php
    $nav = trim($__env->yieldContent('nav'));
    $items = [
        ['dashboard', '/owner/dashboard',      'grid',      'owner_nav_dashboard', 'داشبۆرد'],
        ['reports',   '/owner/reports',        'bar',       'owner_nav_reports',   'ڕاپۆرتەکان'],
        ['invoices',  '/owner/invoices',       'file',      'owner_nav_invoices',  'وەسڵەکان'],
        ['staff',     '/owner/staff',          'users',     'owner_nav_staff',     'کارمەندان'],
        ['business',  '/owner/other-business', 'briefcase', 'owner_nav_other',     'بزنسەکانی تر'],
    ];
@endphp

{{-- ═════════ SIDEBAR ═════════ --}}
<aside class="ow-sidebar" id="owSidebar">
    <a href="{{ url('/owner/dashboard') }}" class="ow-brand">
        <span class="ow-brand-mark">@include('partials.icon', ['name' => 'bag', 'size' => 18])</span>
        <span class="ow-brand-text">{{ $storeName }}</span>
    </a>

    <ul class="ow-nav">
        @foreach($items as [$key, $href, $icon, $i18n, $label])
            <li>
                <a href="{{ url($href) }}" class="side-link {{ $nav === $key ? 'active' : '' }}" title="{{ $label }}">
                    @include('partials.icon', ['name' => $icon])
                    <span class="side-label" data-i18n="{{ $i18n }}">{{ $label }}</span>
                </a>
            </li>
        @endforeach

        <li class="ow-nav-sep"></li>

        <li>
            <a href="{{ url('/owner/pos') }}" class="side-link cta" title="POS">
                @include('partials.icon', ['name' => 'cart'])
                <span class="side-label" data-i18n="owner_nav_pos">فرۆشتن (POS)</span>
            </a>
        </li>
    </ul>

    <div class="ow-side-foot">
        <a href="{{ url('/owner/store-select') }}" class="ow-store-chip" title="Store">
            @include('partials.icon', ['name' => 'swap'])
            <div>
                <small data-i18n="owner_current_store">فرۆشگای ئێستا</small>
                <b id="owStoreLabel" >{{ $storeName }}</b>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="side-link" title="Log out">
                @include('partials.icon', ['name' => 'logout'])
                <span class="side-label" data-i18n="profile_logout">چوونەدەرەوە</span>
            </button>
        </form>
    </div>
</aside>
<div class="ow-backdrop" id="owBackdrop"></div>

{{-- ═════════ MAIN ═════════ --}}
<div class="ow-main">
    <header class="ow-topbar">
        <button class="ow-icon-btn ow-menu-btn" id="owMenuBtn" aria-label="Menu">@include('partials.icon', ['name' => 'menu'])</button>
        <button class="ow-icon-btn ow-collapse-btn" id="owCollapseBtn" aria-label="Collapse">@include('partials.icon', ['name' => 'chevrons'])</button>
        <h1 class="ow-title" data-i18n="@yield('title_key')">@yield('title_default')</h1>

        <div class="ow-top-actions">
            {{-- Language --}}
            <div class="ow-dd">
                <button class="ow-lang-btn" id="owLangBtn" type="button">
                    @include('partials.icon', ['name' => 'globe', 'size' => 17])
                    <span class="cur" id="currentLangLabel">کوردی</span>
                    @include('partials.icon', ['name' => 'chev-down', 'size' => 14])
                </button>
                <div class="ow-menu" id="owLangMenu">
                    <button class="opt" type="button" data-lang="ku">کوردی</button>
                    <button class="opt" type="button" data-lang="en">English</button>
                    <button class="opt" type="button" data-lang="ar">العربية</button>
                </div>
            </div>

            {{-- Settings --}}
            <div class="ow-dd">
                <button class="ow-icon-btn" id="owSettingsBtn" type="button" aria-label="Settings">@include('partials.icon', ['name' => 'sliders'])</button>
                <div class="ow-menu ow-pop" id="owSettingsMenu">
                    <h6 data-i18n="profile_settings">ڕێکخستنەکان</h6>
                    <div class="form-group">
                        <label for="owStoreName" data-i18n="owner_store_name">ناوی فرۆشگا</label>
                        <input class="form-control" id="owStoreName" type="text">
                    </div>
                    <div class="form-group">
                        <label for="owDisplayName" data-i18n="settings_cashier_name">ناوی بەکارهێنەر</label>
                        <input class="form-control" id="owDisplayName" type="text">
                    </div>
                    <button class="btn btn-primary btn-block" id="owSettingsSave" type="button" data-i18n="profile_save">پاشەکەوتکردن</button>
                </div>
            </div>

            {{-- Profile --}}
            <a href="{{ url('/profile') }}" class="ow-chip">
                <span class="ow-avatar" id="owAvatar">خ</span>
                <span class="nm" id="owName">خاوەن</span>
            </a>
        </div>
    </header>

    <main class="ow-content">
        @yield('content')
    </main>
</div>

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/languages.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const $ = (id) => document.getElementById(id);
    const body = document.body, sidebar = $('owSidebar'), backdrop = $('owBackdrop');

    /* Sidebar: desktop collapse (remembered) + mobile drawer */
    if (localStorage.getItem('owSidebar') === 'min') body.classList.add('sb-collapsed');
    $('owCollapseBtn').addEventListener('click', () => {
        body.classList.toggle('sb-collapsed');
        localStorage.setItem('owSidebar', body.classList.contains('sb-collapsed') ? 'min' : 'full');
    });
    const drawer = (open) => { sidebar.classList.toggle('open', open); backdrop.classList.toggle('show', open); };
    $('owMenuBtn').addEventListener('click', () => drawer(true));
    backdrop.addEventListener('click', () => drawer(false));

    /* Dropdowns */
    const menus = [['owLangBtn', 'owLangMenu'], ['owSettingsBtn', 'owSettingsMenu']];
    const closeAll = () => menus.forEach(([, m]) => $(m).classList.remove('show'));
    menus.forEach(([b, m]) => $(b).addEventListener('click', (e) => {
        e.stopPropagation();
        const open = $(m).classList.contains('show');
        closeAll();
        $(m).classList.toggle('show', !open);
    }));
    document.addEventListener('click', (e) => { if (!e.target.closest('.ow-pop')) closeAll(); });

    /* Language */
    const syncLang = () => {
        const cur = localStorage.getItem('posLang') || 'ku';
        document.querySelectorAll('#owLangMenu .opt').forEach(o => o.classList.toggle('sel', o.dataset.lang === cur));
    };
    document.querySelectorAll('#owLangMenu .opt').forEach(o => o.addEventListener('click', () => {
        setLanguage(o.dataset.lang); closeAll(); syncLang();
    }));
    syncLang();

    /* Settings + profile chip (same localStorage key the rest of the app uses) */
    const read = () => { try { return JSON.parse(localStorage.getItem('posSettings') || '{}'); } catch (e) { return {}; } };
    const paint = () => {
        const s = read();
        if (s.storeName) $('owStoreLabel').textContent = s.storeName;
        if (s.cashier) { $('owName').textContent = s.cashier; $('owAvatar').textContent = [...s.cashier][0].toUpperCase(); }
        $('owStoreName').value = s.storeName || '';
        $('owDisplayName').value = s.cashier || '';
    };
    paint();
    $('owSettingsSave').addEventListener('click', () => {
        const s = read();
        s.storeName = $('owStoreName').value.trim();
        s.cashier = $('owDisplayName').value.trim();
        localStorage.setItem('posSettings', JSON.stringify(s));
        paint(); closeAll();
    });
});
</script>
@stack('scripts')
</body>
</html>
