<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="store_select_title">هەڵبژاردنی فرۆشگا</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <style>
        body { background: linear-gradient(135deg, var(--navy) 0%, var(--navy-deep) 100%); min-height: 100vh; }
        .ss-top { display: flex; justify-content: space-between; align-items: center; padding: 18px 28px; color: #fff; }
        .ss-brand { font-weight: 800; font-size: 18px; display: flex; align-items: center; gap: 10px; }
        .ss-brand i { width: 10px; height: 10px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 10px rgba(77,159,255,.8); }
        .ss-lang button { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.18); color: #fff; padding: 6px 12px; border-radius: 8px; font-size: 12.5px; font-weight: 700; cursor: pointer; margin-inline-start: 6px; }
        .ss-lang button:hover, .ss-lang button.on { background: var(--accent); border-color: var(--accent); }

        .ss-wrap { max-width: 760px; margin: 0 auto; padding: 10px 18px 50px; }
        .ss-head { text-align: center; color: #fff; margin: 18px 0 28px; }
        .ss-head h1 { color: #fff; font-size: 30px; margin-bottom: 8px; }
        .ss-head p { color: rgba(255,255,255,.7); margin: 0; }

        .ss-card { background: #fff; border-radius: 20px; box-shadow: var(--shadow-lg); padding: 26px; margin-bottom: 20px; }
        .ss-card h2 { font-size: 15px; text-transform: uppercase; letter-spacing: .5px; color: var(--text-muted); margin-bottom: 16px; }

        .ss-store { display: flex; align-items: center; gap: 16px; padding: 14px 16px; border: 1.5px solid var(--line); border-radius: 14px; margin-bottom: 10px; text-decoration: none; color: var(--navy); transition: all .15s; }
        .ss-store:hover { border-color: var(--accent); background: var(--accent-soft); transform: translateY(-1px); text-decoration: none; color: var(--navy); }
        .ss-ico { width: 46px; height: 46px; border-radius: 12px; background: var(--accent-soft); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
        .ss-store .nm { font-weight: 800; font-size: 15px; }
        .ss-store .meta { font-size: 12.5px; color: var(--text-muted); font-weight: 600; }
        .ss-store .go { margin-inline-start: auto; font-weight: 800; font-size: 13px; color: #1d6fe0; }

        .plans { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 6px 0 18px; }
        .plan { position: relative; border: 1.5px solid var(--line); border-radius: 14px; padding: 14px 16px; cursor: pointer; transition: all .15s; }
        .plan input { position: absolute; opacity: 0; }
        .plan b { display: block; color: var(--navy); font-size: 15px; }
        .plan small { color: var(--text-muted); font-weight: 600; }
        .plan:hover { border-color: var(--accent); }
        .plan:has(input:checked) { border-color: var(--accent); background: var(--accent-soft); box-shadow: 0 0 0 3px var(--accent-soft); }

        .ss-foot { text-align: center; }
        .ss-foot button { background: none; border: none; color: rgba(255,255,255,.7); font-weight: 700; cursor: pointer; }
        .ss-foot button:hover { color: #fff; }
        @media (max-width: 520px) { .plans { grid-template-columns: 1fr; } .ss-head h1 { font-size: 24px; } }
    </style>
</head>
<body>

<div class="ss-top">
    <div class="ss-brand"><i></i>Marketakam</div>
    <div class="ss-lang" id="ssLang">
        <button type="button" data-lang="ku">کوردی</button>
        <button type="button" data-lang="en">EN</button>
        <button type="button" data-lang="ar">عربي</button>
    </div>
</div>

<div class="ss-wrap">
    <div class="ss-head">
        <h1 data-i18n="owner_ss_welcome">بەخێربێیت! فرۆشگایەک هەڵبژێرە</h1>
        <p data-i18n="owner_ss_sub">فرۆشگایەکی هەبوو هەڵبژێرە یان یەکێکی نوێ دروست بکە.</p>
    </div>

    @if (session('success'))
        <div class="ss-card" style="background:#eafaf1;border:1px solid #bfe8cf; color:#0d6b3f; padding:14px 18px; margin-bottom:16px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="ss-card">
        <h2 data-i18n="owner_ss_existing">فرۆشگاکانت</h2>

        @if ($stores->isEmpty())
            <div style="padding:12px 0; color: var(--text-muted); font-weight: 600;">
                هیچ فرۆشگایەک نەدۆزرایەوە. فرۆشگایەکی نوێ دروست بکە.
            </div>
        @else
            @foreach ($stores as $store)
                <a href="{{ url('/dashboard') }}?store_id={{ $store->id }}" class="ss-store">
                    <span class="ss-ico">{{ $store->subscription_plan === 'pro' ? '🏪' : '🛒' }}</span>
                    <div>
                        <div class="nm">{{ $store->name }}</div>
                        <div class="meta">
                            <span data-i18n="owner_plan">پلان</span>:
                            <span data-i18n="{{ $store->subscription_plan === 'pro' ? 'owner_plan_pro' : 'owner_plan_basic' }}">
                                {{ $store->subscription_plan === 'pro' ? 'پرۆ' : 'بنەڕەتی' }}
                            </span>
                            ·
                            <span data-i18n="owner_active">چالاک</span>
                        </div>
                    </div>
                    <span class="go" data-i18n="owner_open">کردنەوە</span>
                </a>
            @endforeach
        @endif
    </div>

    <div class="ss-card">
        <h2 data-i18n="owner_ss_create">دروستکردنی فرۆشگای نوێ</h2>
        <form method="POST" action="{{ route('store.create') }}">
            @csrf
            <div class="form-group">
                <input type="text" name="name" class="form-control form-control-lg" required data-i18n="owner_ss_name_ph" placeholder="ناوی فرۆشگا...">
            </div>
            <label data-i18n="owner_ss_plan">پلانی بەشداریکردن</label>
            <div class="plans">
                <label class="plan"><input type="radio" name="subscription_plan" value="basic" checked>
                    <b data-i18n="owner_plan_basic">بنەڕەتی</b><small data-i18n="owner_plan_basic_desc">بۆ فرۆشگایەکی بچووک</small></label>
                <label class="plan"><input type="radio" name="subscription_plan" value="pro">
                    <b data-i18n="owner_plan_pro">پرۆ</b><small data-i18n="owner_plan_pro_desc">چەند لق و ڕاپۆرتی ورد</small></label>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block" data-i18n="owner_ss_create_btn">دروستکردن و چوونە ژوورەوە</button>
        </form>
    </div>

    <div class="ss-foot">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" data-i18n="profile_logout">چوونەدەرەوە</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/languages.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bar = document.getElementById('ssLang');
    const sync = () => { const c = localStorage.getItem('posLang') || 'ku';
        bar.querySelectorAll('button').forEach(b => b.classList.toggle('on', b.dataset.lang === c)); };
    bar.querySelectorAll('button').forEach(b => b.addEventListener('click', () => { setLanguage(b.dataset.lang); sync(); }));
    sync();
});
</script>
</body>
</html>
