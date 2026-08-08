<nav class="navbar navbar-expand-lg navbar-dark bg-primary global-navbar">
    <a class="navbar-brand font-weight-bold" href="/home">
        🏪 <span data-i18n="brand_name">سیستەمی فرۆشتن</span>
    </a>

    <div class="navbar-left-actions">
        <button class="nav-icon-btn" id="btnGlobalSettings" title="ڕێکخستنەکان">
            ⚙️
        </button>

    </div>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#globalNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="globalNavbar">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="/home" data-i18n="nav_home">سەرەکی</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/manage-items" data-i18n="nav_items">کاڵاکان</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/manage-employees" data-i18n="nav_employees">کارمەندان</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/pos-checkout" data-i18n="nav_pos">🛒 فرۆشتن</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/search-orders" data-i18n="nav_search">وەسڵەکان</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">


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

            <li class="nav-item">
                <a class="nav-link text-warning" href="/profile" id="navbarUsernameDisplay" data-i18n="profile_nav">
                    👤 <strong id="navbarUsername">بەڕێوەبەر</strong>
                </a>
            </li>
        </ul>
    </div>
</nav>
