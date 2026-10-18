@extends('owner.layouts.app')

@section('title_default', 'Profile')
@section('title_key', 'profile_title')

@section('content')
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
                    @include('partials.icon', ['name' => 'user', 'size' => 20])<span data-i18n="profile_my_profile">پرۆفایلەکەم</span><span class="chev">›</span></a></li>
                <li><button type="button" class="pf-menu-item" id="pfSettingsToggle">
                    @include('partials.icon', ['name' => 'sliders', 'size' => 20])<span data-i18n="profile_settings">ڕێکخستنەکان</span><span class="chev">›</span></button></li>
                <li><a href="{{ url('/store-select') }}" class="pf-menu-item">
                    @include('partials.icon', ['name' => 'bag', 'size' => 20])<span data-i18n="profile_switch_store">گۆڕینی فرۆشگا</span><span class="chev">›</span></a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="danger">
                            @include('partials.icon', ['name' => 'logout', 'size' => 20])<span data-i18n="profile_logout">چوونەدەرەوە</span></button>
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
                    <button type="button" class="pf-edit-fab" id="pfEditToggle" title="Edit">@include('partials.icon', ['name' => 'edit', 'size' => 14])</button>
                </div>
                <div class="grow">
                    <div class="pf-detail-name pf-js-name">{{ $username ?: '—' }}</div>
                    <div class="pf-detail-sub">{{ $email ?: '—' }}</div>
                </div>
                <a href="{{ url('/owner/dashboard') }}" class="pf-x" aria-label="close" style="text-decoration:none">✕</a>
            </div>

            <div class="pf-row">
                <label for="pfUsername" data-i18n="profile_username">ناوی بەکارهێنەر</label>
                <input class="pf-field" id="pfUsername" name="username" value="{{ old('username', $username) }}" placeholder="{{ $username }}" required>
            </div>

            <div class="pf-row">
                <label for="pfEmail" data-i18n="profile_email">ئیمەیڵ</label>
                <input class="pf-field" id="pfEmail" name="email" type="email" value="{{ old('email', $email) }}" placeholder="{{ $email ?: 'name@email.com' }}">
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
                <input class="pf-field" id="pfPassword" name="password" type="password" data-i18n="profile_password_hint" placeholder="بۆ گۆڕین بنووسە" autocomplete="new-password">
            </div>

            <button type="submit" class="pf-save" id="pfSave" data-i18n="profile_save">پاشەکەوتکردن</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/icons.js') }}"></script>
<script src="{{ asset('js/languages.js') }}"></script>
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
@endpush
