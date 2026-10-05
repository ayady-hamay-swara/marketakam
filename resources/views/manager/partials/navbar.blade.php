<nav class="navbar navbar-expand-lg navbar-dark global-navbar">

    {{-- Brand --}}
    <a class="navbar-brand" href="/home">
        <span class="navbar-brand-dot"></span>
        <span>Marketakam</span>
    </a>

    {{-- Hamburger --}}
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#globalNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="globalNavbar">

        {{-- Divider between brand zone and nav links --}}
        <span class="navbar-divider d-none d-lg-block"></span>

        {{-- Main nav (manager-specific) --}}
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/home" data-i18n="nav_home">سەرەکی</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/manager" data-i18n="nav_manager">بەڕێوەبەر</a>
            </li>
        </ul>

        {{-- Right side: language + user --}}
        <ul class="navbar-nav align-items-center" style="gap:6px;">

            {{-- Language dropdown --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="langDropdown"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    🌐 <span id="currentLangLabel">کوردی</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="langDropdown">
                    <a class="dropdown-item" href="#" onclick="setLanguage('ku'); return false;">
                        🟥🟩⚪ کوردی
                    </a>
                    <a class="dropdown-item" href="#" onclick="setLanguage('en'); return false;">
                        🇬🇧 English
                    </a>
                    <a class="dropdown-item" href="#" onclick="setLanguage('ar'); return false;">
                        🇸🇦 العربية
                    </a>
                </div>
            </li>

            {{-- User chip --}}
            <li class="nav-item">
                <a class="navbar-user-chip" href="/profile" id="navbarUsernameDisplay">
                    <span class="navbar-user-avatar" id="navbarUserInitial">م</span>
                    <span id="navbarUsername" data-i18n="navbar_username" data-default-name="{{ auth()->user()->name ?? auth()->user()->username ?? 'بەڕێوەبەر' }}">{{ auth()->user()->name ?? auth()->user()->username ?? 'بەڕێوەبەر' }}</span>
                </a>
            </li>

        </ul>
    </div>
</nav>
