@extends('owner.layouts.app')
@section('nav', 'business')
@section('title_key', 'owner_nav_other')
@section('title_default', 'بزنسەکانی تر')

@section('content')
<div class="ow-toolbar">
    <p class="sub" data-i18n="owner_other_sub">فرۆشگا و کارەکانی تری تۆ</p>
    <a href="{{ url('/owner/store-select') }}" class="btn btn-primary">@include('partials.icon', ['name' => 'plus', 'size' => 16])<span data-i18n="owner_add_business">زیادکردنی بزنسی نوێ</span></a>
</div>

@php
$stores = [
    ['مارکێتی ئاراس',        'owner_plan_pro',   'پرۆ',      'owner_active',           'چالاک',   'on',   'IQD 1,845,000', true],
    ['فرۆشگای ئاسمان',       'owner_plan_basic', 'بنەڕەتی',  'owner_active',           'چالاک',   'on',   'IQD 920,500',   false],
    ['نان و شیرینی کانی',    'owner_plan_basic', 'بنەڕەتی',  'owner_status_suspended', 'ڕاگیراو', 'warn', 'IQD 0',         false],
];
@endphp

<div>
    @foreach($stores as $st)
    <div class="ow-row {{ $st[7] ? 'current' : '' }}">
        <span class="mark">@include('partials.icon', ['name' => 'bag', 'size' => 18])</span>
        <div>
            <div class="nm">{{ $st[0] }} @if($st[7])<span class="ow-badge b-info" data-i18n="owner_current">ئێستا</span>@endif</div>
            <div class="sub"><span data-i18n="owner_plan">پلان</span>: <span data-i18n="{{ $st[1] }}">{{ $st[2] }}</span></div>
        </div>
        <div><span class="dot {{ $st[5] }}"></span><span data-i18n="{{ $st[3] }}">{{ $st[4] }}</span></div>
        <div class="money" dir="ltr">{{ $st[6] }}</div>
        @if($st[7])
            <a href="{{ url('/owner/dashboard') }}" class="btn btn-primary btn-sm" data-i18n="owner_open">کردنەوە</a>
        @else
            <a href="{{ url('/owner/dashboard') }}" class="btn btn-secondary btn-sm" data-i18n="owner_switch">گۆڕین بۆ ئەمە</a>
        @endif
    </div>
    @endforeach
</div>
@endsection
