@extends('layouts.gate')
@section('title_key', 'store_select_title')
@section('title_default', 'هەڵبژاردنی فرۆشگا')
@section('body_class', 'wide')

@section('content')
    <h2 class="gate-title" data-i18n="owner_ss_welcome">بەخێربێیت! فرۆشگایەک هەڵبژێرە</h2>
    <p class="gate-sub" data-i18n="owner_ss_sub">فرۆشگایەکی هەبوو هەڵبژێرە یان یەکێکی نوێ دروست بکە.</p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="gate-section" style="margin-top:0;padding-top:0;border-top:none">
        <h2 data-i18n="owner_ss_existing">فرۆشگاکانت</h2>

        @if ($stores->isEmpty())
            <div class="text-muted" style="padding:var(--sp-3) 0">
                هیچ فرۆشگایەک نەدۆزرایەوە. فرۆشگایەکی نوێ دروست بکە.
            </div>
        @else
            @foreach ($stores as $store)
                <a href="{{ url('/dashboard') }}?store_id={{ $store->id }}" class="gate-row">
                    <span class="mark">@include('partials.icon', ['name' => $store->subscription_plan === 'pro' ? 'bag' : 'cart', 'size' => 20])</span>
                    <span>
                        <div class="nm">{{ $store->name }}</div>
                        <div class="meta">
                            <span data-i18n="owner_plan">پلان</span>:
                            <span data-i18n="{{ $store->subscription_plan === 'pro' ? 'owner_plan_pro' : 'owner_plan_basic' }}">
                                {{ $store->subscription_plan === 'pro' ? 'پرۆ' : 'بنەڕەتی' }}
                            </span>
                            ·
                            <span data-i18n="owner_active">چالاک</span>
                        </div>
                    </span>
                    <span class="go"><span data-i18n="owner_open">کردنەوە</span>@include('partials.icon', ['name' => 'chev-right', 'size' => 18, 'class' => 'flip-rtl'])</span>
                </a>
            @endforeach
        @endif
    </div>

    <div class="gate-section">
        <h2 data-i18n="owner_ss_create">دروستکردنی فرۆشگای نوێ</h2>
        <form method="POST" action="{{ route('store.create') }}">
            @csrf
            <div class="form-group">
                <input type="text" name="name" class="form-control" required data-i18n="owner_ss_name_ph" placeholder="ناوی فرۆشگا...">
            </div>
            <label data-i18n="owner_ss_plan">پلانی بەشداریکردن</label>
            <div class="plans">
                <label class="plan"><input type="radio" name="subscription_plan" value="basic" checked>
                    <b data-i18n="owner_plan_basic">بنەڕەتی</b><small data-i18n="owner_plan_basic_desc">بۆ فرۆشگایەکی بچووک</small></label>
                <label class="plan"><input type="radio" name="subscription_plan" value="pro">
                    <b data-i18n="owner_plan_pro">پرۆ</b><small data-i18n="owner_plan_pro_desc">چەند لق و ڕاپۆرتی ورد</small></label>
            </div>
            <button type="submit" class="btn btn-primary btn-block" data-i18n="owner_ss_create_btn">دروستکردن و چوونە ژوورەوە</button>
        </form>
    </div>

    <div class="gate-section">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="gate-quiet" data-i18n="profile_logout">چوونەدەرەوە</button>
        </form>
    </div>
@endsection
