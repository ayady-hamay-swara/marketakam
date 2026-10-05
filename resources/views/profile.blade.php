<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title data-i18n="profile_title">پرۆفایل - Marketakam</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <style>
        body { background: linear-gradient(135deg, var(--navy) 0%, var(--navy-deep) 100%); min-height: 100vh; }

        .pf-wrap {
            max-width: 980px;
            margin: 0 auto;
            padding: 36px 18px 60px;
            display: grid;
            grid-template-columns: 290px 1fr;
            gap: 22px;
            align-items: start;
        }
        .pf-side { display: flex; flex-direction: column; gap: 22px; }

        .pf-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 20px;
        }

        /* ── Avatar ── */
        .pf-avatar {
            width: 52px; height: 52px; flex-shrink: 0;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--navy-soft));
            color: #fff; font-weight: 800; font-size: 20px;
            display: flex; align-items: center; justify-content: center;
            position: relative;
        }
        .pf-avatar.lg { width: 68px; height: 68px; font-size: 26px; }
        .pf-edit-fab {
            position: absolute; bottom: -2px; inset-inline-end: -2px;
            width: 26px; height: 26px; border-radius: 50%;
            background: #fff; border: 1px solid var(--line);
            box-shadow: var(--shadow-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; cursor: pointer; padding: 0;
            transition: background .15s, transform .15s;
        }
        .pf-edit-fab:hover { background: var(--accent-soft); transform: scale(1.08); }
        .pf-edit-fab.on { background: var(--accent); border-color: var(--accent); }

        .pf-head { display: flex; align-items: center; gap: 14px; padding-bottom: 16px; border-bottom: 1px solid var(--line); }
        .pf-head-name { font-size: 15px; font-weight: 800; color: var(--navy); line-height: 1.3; }
        .pf-head-sub  { font-size: 12.5px; color: var(--text-muted); word-break: break-all; }

        /* ── Menu rows ── */
        .pf-menu { list-style: none; margin: 14px 0 0; padding: 0; }
        .pf-menu-item, .pf-menu form button {
            display: flex; align-items: center; gap: 12px; width: 100%;
            padding: 11px 12px; border: none; background: transparent;
            border-radius: 10px; color: var(--navy);
            font-size: 14px; font-weight: 600; text-align: start;
            text-decoration: none; cursor: pointer;
            transition: background .15s;
        }
        .pf-menu-item:hover, .pf-menu form button:hover { background: var(--page-bg); color: var(--navy); text-decoration: none; }
        .pf-menu-item.active { background: var(--accent-soft); }
        .pf-menu-item .ico { width: 22px; text-align: center; font-size: 17px; }
        .pf-menu-item .chev { margin-inline-start: auto; color: #9aa8bd; transition: transform .2s; }
        html[dir="rtl"] .pf-menu-item .chev { transform: scaleX(-1); }
        .pf-menu-item.open .chev { transform: rotate(90deg) !important; }
        .pf-menu .danger, .pf-menu form button.danger { color: var(--danger); }

        /* ── Settings card ── */
        .pf-settings { display: none; animation: pfIn .2s ease; }
        .pf-settings.show { display: block; }
        @keyframes pfIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: none; } }

        .pf-card-title { display: flex; justify-content: space-between; align-items: center;
            font-size: 16px; font-weight: 800; color: var(--navy);
            padding-bottom: 12px; border-bottom: 1px solid var(--line); margin-bottom: 6px; }
        .pf-x { background: none; border: none; font-size: 18px; color: var(--navy); cursor: pointer; line-height: 1; padding: 4px 8px; border-radius: 8px; }
        .pf-x:hover { background: var(--page-bg); }

        .pf-set-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 2px; position: relative; font-size: 14px; font-weight: 600; color: var(--navy); }
        .pf-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 10px; border: 1px solid var(--line); border-radius: 8px;
            background: #fff; font-size: 13px; font-weight: 600; color: var(--text-muted); cursor: pointer;
        }
        .pf-pill:hover { border-color: var(--accent); }
        .pf-pop {
            display: none; position: absolute; top: 100%; inset-inline-end: 0; z-index: 5;
            background: #fff; border-radius: 12px; box-shadow: var(--shadow-lg);
            padding: 6px; min-width: 130px;
        }
        .pf-pop.show { display: block; }
        .pf-pop button {
            display: block; width: 100%; text-align: start; background: none; border: none;
            padding: 8px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; color: var(--navy); cursor: pointer;
        }
        .pf-pop button:hover, .pf-pop button.sel { background: var(--accent-soft); }

        /* ── Detail card ── */
        .pf-detail-head { display: flex; align-items: center; gap: 16px; padding-bottom: 18px; border-bottom: 1px solid var(--line); }
        .pf-detail-head .grow { flex: 1; min-width: 0; }
        .pf-detail-name { font-size: 18px; font-weight: 800; color: var(--navy); }
        .pf-detail-sub  { font-size: 13px; color: var(--text-muted); word-break: break-all; }

        .pf-row { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 16px 0; border-bottom: 1px solid #eef2f8; }
        .pf-row:last-of-type { border-bottom: none; }
        .pf-row label { margin: 0; font-size: 14.5px; font-weight: 600; color: var(--navy); flex-shrink: 0; }
        .pf-field {
            border: none; background: transparent; text-align: end; width: 100%; max-width: 260px;
            font-size: 14px; color: var(--text-muted); padding: 6px 8px; border-radius: 8px; outline: none;
            transition: background .15s, box-shadow .15s, color .15s;
        }
        .pf-field[readonly] { cursor: default; }
        .pf-field::placeholder { color: #b3bfd1; }
        .editing .pf-field:not([readonly]) { background: var(--page-bg); color: var(--navy); box-shadow: inset 0 0 0 1.5px var(--line); }
        .editing .pf-field:not([readonly]):focus { box-shadow: inset 0 0 0 1.5px var(--accent), 0 0 0 .2rem var(--accent-soft); }

        .pf-role-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; background: var(--accent-soft); color: var(--navy); font-size: 12.5px; font-weight: 800; }

        .pf-save { margin-top: 10px; padding: 10px 22px; border: none; border-radius: 8px; background: var(--accent); color: #fff; font-weight: 800; font-size: 14px; cursor: pointer; transition: background .15s, opacity .15s; }
        .pf-save:hover:not(:disabled) { background: #2f86f0; }
        .pf-save:disabled { opacity: .4; cursor: not-allowed; }

        .pf-alert { padding: 10px 14px; border-radius: 10px; font-size: 13.5px; font-weight: 600; margin-bottom: 14px; }
        .pf-alert.ok  { background: #dcfce7; color: #166534; }
        .pf-alert.err { background: #fee2e2; color: #991b1b; }

        @media (max-width: 800px) {
            .pf-wrap { grid-template-columns: 1fr; padding-top: 22px; }
            .pf-main { order: -1; }
            .pf-field { max-width: 180px; }
        }
    </style>
</head>
<body>

@include('partials.navbar')

@php
    $user     = auth()->user();
    $username = $user->username ?? $user->name ?? 'User';
    $email    = $user->email ?? '';
    $role     = $user->role ?? 'cashier';
    $roleKeys = ['owner'=>'profile_role_owner','manager'=>'profile_role_manager','cashier'=>'profile_role_cashier','super-dev'=>'profile_role_super_dev','super_dev'=>'profile_role_super_dev'];
    $roleKey  = $roleKeys[$role] ?? null;
@endphp

<div class="pf-wrap">

    {{-- ═════════ LEFT: menu + settings ═════════ --}}
    <div class="pf-side">

        <div class="pf-card">
            <div class="pf-head">
                <div class="pf-avatar" id="pfAvatarSm">{{ mb_strtoupper(mb_substr($username ?: '?', 0, 1)) }}</div>
                <div style="min-width:0">
                    <div class="pf-head-name pf-js-name">{{ $username ?: '—' }}</div>
                    <div class="pf-head-sub">{{ $email ?: '—' }}</div>
                </div>
            </div>

            <ul class="pf-menu">
                <li><a href="#pfDetail" class="pf-menu-item active">
                    <span class="ico">👤</span><span data-i18n="profile_my_profile">پرۆفایلەکەم</span><span class="chev">›</span></a></li>
                <li><button type="button" class="pf-menu-item" id="pfSettingsToggle">
                    <span class="ico">⚙️</span><span data-i18n="profile_settings">ڕێکخستنەکان</span><span class="chev">›</span></button></li>
                @if($role === 'owner')
                <li><a href="{{ url('/store-select') }}" class="pf-menu-item">
                    <span class="ico">🏪</span><span data-i18n="profile_switch_store">گۆڕینی فرۆشگا</span><span class="chev">›</span></a></li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="danger">
                            <span class="ico">🚪</span><span data-i18n="profile_logout">چوونەدەرەوە</span></button>
                    </form>
                </li>
            </ul>
        </div>

        <div class="pf-card pf-settings" id="pfSettings">
            <div class="pf-card-title">
                <span data-i18n="profile_settings">ڕێکخستنەکان</span>
                <button type="button" class="pf-x" id="pfSettingsClose" aria-label="close">✕</button>
            </div>
            <div class="pf-set-row">
                <span data-i18n="profile_language">زمان</span>
                <button type="button" class="pf-pill" id="pfLangBtn"><span id="pfLangLabel">کوردی</span> <span>⌄</span></button>
                <div class="pf-pop" id="pfLangPop">
                    <button type="button" data-lang="ku">کوردی</button>
                    <button type="button" data-lang="en">English</button>
                    <button type="button" data-lang="ar">العربية</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═════════ RIGHT: profile details ═════════ --}}
    <div class="pf-card pf-main" id="pfDetail">

        @if(session('status'))<div class="pf-alert ok">{{ session('status') }}</div>@endif
        @if($errors->any())<div class="pf-alert err">{{ $errors->first() }}</div>@endif

        <form method="POST" action="{{ url('/profile') }}" id="pfForm" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="pf-detail-head">
                <div class="pf-avatar lg">
                    <span>{{ mb_strtoupper(mb_substr($username ?: '?', 0, 1)) }}</span>
                    <button type="button" class="pf-edit-fab" id="pfEditToggle" data-i18n-title="profile_edit" title="Edit">✏️</button>
                </div>
                <div class="grow">
                    <div class="pf-detail-name pf-js-name">{{ $username ?: '—' }}</div>
                    <div class="pf-detail-sub">{{ $email ?: '—' }}</div>
                </div>
                <a href="{{ url('/home') }}" class="pf-x" aria-label="close" style="text-decoration:none">✕</a>
            </div>

            <div class="pf-row">
                <label for="pfUsername" data-i18n="profile_username">ناوی بەکارهێنەر</label>
                <input class="pf-field" id="pfUsername" name="username" value="{{ old('username', $username) }}" placeholder="{{ $username }}" readonly required>
            </div>

            <div class="pf-row">
                <label for="pfEmail" data-i18n="profile_email">ئیمەیڵ</label>
                <input class="pf-field" id="pfEmail" name="email" type="email" value="{{ old('email', $email) }}" placeholder="{{ $email ?: 'name@email.com' }}" readonly>
            </div>

            <div class="pf-row">
                <label data-i18n="profile_role_label">ڕۆڵ</label>
                @if($roleKey)
                    <span class="pf-role-badge" data-i18n="{{ $roleKey }}">{{ $role }}</span>
                @else
                    <span class="pf-role-badge">{{ $role }}</span>
                @endif
            </div>

            <div class="pf-row">
                <label for="pfPassword" data-i18n="profile_password">وشەی نهێنی</label>
                <input class="pf-field" id="pfPassword" name="password" type="password" data-i18n="profile_password_hint" placeholder="بۆ گۆڕین بنووسە" autocomplete="new-password" readonly>
            </div>

            <button type="submit" class="pf-save" id="pfSave" disabled data-i18n="profile_save">پاشەکەوتکردن</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/languages.js') }}"></script>
<script src="{{ asset('js/navbar-global.js') }}"></script>
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script>
window.CURRENT_USER_NAME = @json($username);
window.CURRENT_USER_EMAIL = @json($email ?: 'name@email.com');
window.CURRENT_USER_ROLE = @json($role);

document.addEventListener('DOMContentLoaded', function () {
    const $ = (id) => document.getElementById(id);

    const syncCurrentUserProfile = () => {
        const name = window.CURRENT_USER_NAME || 'User';
        const email = window.CURRENT_USER_EMAIL || 'name@email.com';

        document.querySelectorAll('.pf-js-name').forEach(el => el.textContent = name);
        const avatarEls = document.querySelectorAll('.pf-avatar');
        avatarEls.forEach(el => {
            const target = el.querySelector('span') || el;
            const initial = (name || 'U').charAt(0).toUpperCase();
            if (target && target.tagName === 'SPAN') {
                target.textContent = initial;
            } else {
                el.textContent = initial;
            }
        });

        const usernameInput = $('pfUsername');
        if (usernameInput && !usernameInput.value.trim()) usernameInput.value = name;

        const emailInput = $('pfEmail');
        if (emailInput && !emailInput.value.trim()) emailInput.value = email;
    };

    syncCurrentUserProfile();

    /* Fallback display name when no authenticated user (mock login) */
    <?php if (!$user) { ?>
    try {
        const s = JSON.parse(localStorage.getItem('posSettings') || '{}');
        if (s.cashier) {
            document.querySelectorAll('.pf-js-name').forEach(e => e.textContent = s.cashier);
            const ch = [...s.cashier][0].toUpperCase();
            $('pfAvatarSm').textContent = ch;
        }
    } catch (e) {}
    <?php } ?>

    /* Settings card toggle */
    const settings = $('pfSettings'), toggle = $('pfSettingsToggle');
    const setOpen = (open) => { settings.classList.toggle('show', open); toggle.classList.toggle('open', open); };
    toggle.addEventListener('click', () => setOpen(!settings.classList.contains('show')));
    $('pfSettingsClose').addEventListener('click', () => setOpen(false));

    /* Language dropdown */
    const names = { ku: 'کوردی', en: 'English', ar: 'العربية' };
    const pop = $('pfLangPop'), label = $('pfLangLabel');
    const syncLang = () => {
        const cur = localStorage.getItem('posLang') || 'ku';
        label.textContent = names[cur] || names.ku;
        pop.querySelectorAll('button').forEach(b => b.classList.toggle('sel', b.dataset.lang === cur));
    };
    $('pfLangBtn').addEventListener('click', (e) => { e.stopPropagation(); pop.classList.toggle('show'); });
    pop.querySelectorAll('button').forEach(b => b.addEventListener('click', () => {
        setLanguage(b.dataset.lang);
        pop.classList.remove('show');
        syncLang();
    }));
    document.addEventListener('click', () => pop.classList.remove('show'));
    syncLang();

    /* Edit mode (pencil on the avatar) */
    const form = $('pfForm'), save = $('pfSave'), edit = $('pfEditToggle');
    const fields = form.querySelectorAll('.pf-field');
    edit.addEventListener('click', () => {
        const on = !form.classList.contains('editing');
        form.classList.toggle('editing', on);
        edit.classList.toggle('on', on);
        fields.forEach(f => f.readOnly = !on);
        if (on) $('pfUsername').focus(); else save.disabled = true;
    });
    form.addEventListener('input', () => { save.disabled = false; });
});
</script>
@include('partials.footer')
</body>
</html>
