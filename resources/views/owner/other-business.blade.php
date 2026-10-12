@extends('owner.layouts.app')
@section('nav', 'business')
@section('title_key', 'owner_nav_other')
@section('title_default', 'بزنسەکانی تر')

@section('content')
<div class="ow-toolbar">
    <p class="sub" data-i18n="owner_other_sub">فرۆشگا و کارەکانی تری تۆ</p>
</div>

<div class="ow-cards">
    <div class="ow-card ow-store current">
        <div class="ow-bubble tone-blue">@include('owner.partials.icon', ['name' => 'bag', 'size' => 26])</div>
        <h4>مارکێتی ئاراس</h4>
        <span class="ow-badge b-info" data-i18n="owner_current">ئێستا</span>
        <span class="ow-badge b-paid" data-i18n="owner_active">چالاک</span>
        <div class="rev">IQD 1,845,000</div>
        <div class="ow-meta" style="margin:0 0 14px"><span data-i18n="owner_plan">پلان</span>: <b data-i18n="owner_plan_pro">پرۆ</b></div>
        <a href="{{ url('/owner/dashboard') }}" class="btn btn-primary btn-block" data-i18n="owner_open">کردنەوە</a>
    </div>

    <div class="ow-card ow-store">
        <div class="ow-bubble tone-green">@include('owner.partials.icon', ['name' => 'bag', 'size' => 26])</div>
        <h4>فرۆشگای ئاسمان</h4>
        <span class="ow-badge b-paid" data-i18n="owner_active">چالاک</span>
        <div class="rev">IQD 920,500</div>
        <div class="ow-meta" style="margin:0 0 14px"><span data-i18n="owner_plan">پلان</span>: <b data-i18n="owner_plan_basic">بنەڕەتی</b></div>
        <a href="{{ url('/owner/dashboard') }}" class="btn btn-ghost btn-block" data-i18n="owner_switch">گۆڕین بۆ ئەمە</a>
    </div>

    <div class="ow-card ow-store">
        <div class="ow-bubble tone-amber">@include('owner.partials.icon', ['name' => 'bag', 'size' => 26])</div>
        <h4>نان و شیرینی کانی</h4>
        <span class="ow-badge b-debt" data-i18n="owner_status_suspended">ڕاگیراو</span>
        <div class="rev">IQD 0</div>
        <div class="ow-meta" style="margin:0 0 14px"><span data-i18n="owner_plan">پلان</span>: <b data-i18n="owner_plan_basic">بنەڕەتی</b></div>
        <a href="{{ url('/owner/dashboard') }}" class="btn btn-ghost btn-block" data-i18n="owner_switch">گۆڕین بۆ ئەمە</a>
    </div>

    <a href="{{ url('/owner/store-select') }}" class="ow-card ow-store add" style="text-decoration:none">
        <span class="plus">@include('owner.partials.icon', ['name' => 'plus', 'size' => 26])</span>
        <span data-i18n="owner_add_business">زیادکردنی بزنسی نوێ</span>
    </a>
</div>
@endsection
