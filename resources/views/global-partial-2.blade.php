<div class="ms-auto d-flex align-items-center gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                🌐 <span id="currentLangLabel">کوردی</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#" onclick="setLanguage('ku'); return false;">🟥🟩⚪ کوردی</a>
                <a class="dropdown-item" href="#" onclick="setLanguage('en'); return false;">🇬🇧 English</a>
                <a class="dropdown-item" href="#" onclick="setLanguage('ar'); return false;">🇸🇦 العربية</a>
            </div>
        </div>

        <a class="nav-link text-warning" href="{{ url('/profile') }}" id="navbarUsernameDisplay">
            👤 <strong id="navbarUsername">بەڕێوەبەر</strong>
        </a>
    </div>

