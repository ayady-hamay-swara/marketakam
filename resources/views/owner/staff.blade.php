@extends('owner.layouts.app')
@section('nav', 'staff')
@section('title_key', 'owner_nav_staff')
@section('title_default', 'کارمەندان')

@section('content')
<div class="ow-toolbar">
    <p class="sub" data-i18n="owner_staff_sub">تیمی فرۆشگاکەت</p>
    <a href="{{ url('/manage-employees') }}" class="btn btn-ghost" data-i18n="owner_manage_full">بەڕێوەبردنی تەواو</a>
    <button class="btn btn-primary" type="button">@include('owner.partials.icon', ['name' => 'plus', 'size' => 17])<span data-i18n="owner_add_staff">زیادکردنی کارمەند</span></button>
</div>

@php
$staff = [
    ['EMP001','ئاراس محەمەد','profile_role_owner','خاوەن','0750 111 2233','2023-04-12',true,''],
    ['EMP002','دیاکۆ ڕەشید','profile_role_manager','بەڕێوەبەر','0750 222 3344','2023-06-01',true,'av2'],
    ['EMP003','هێمن سەلام','owner_pos_assistant','یاریدەدەری بەڕێوەبەر','0770 333 4455','2024-01-15',true,'av3'],
    ['EMP004','ژیلا ئەمین','profile_role_cashier','کاشێر','0770 444 5566','2024-08-20',true,'av4'],
    ['EMP005','کارزان ئیبراهیم','profile_role_cashier','کاشێر','0750 555 6677','2025-02-03',false,'av5'],
];
@endphp
<div class="ow-cards">
    @foreach($staff as $s)
    <div class="ow-card ow-person">
        <div class="ow-avatar {{ $s[7] }}">{{ mb_substr($s[1], 0, 1) }}</div>
        <h4>{{ $s[1] }}</h4>
        <div class="code">{{ $s[0] }}</div>
        <span class="ow-badge b-info" data-i18n="{{ $s[2] }}">{{ $s[3] }}</span>
        <span class="ow-badge {{ $s[6] ? 'b-paid' : 'b-muted' }}" data-i18n="{{ $s[6] ? 'owner_active' : 'owner_inactive' }}">{{ $s[6] ? 'چالاک' : 'ناچالاک' }}</span>
        <div class="ow-meta">@include('owner.partials.icon', ['name' => 'phone', 'size' => 15])<span dir="ltr">{{ $s[4] }}</span></div>
        <div class="ow-meta">@include('owner.partials.icon', ['name' => 'calendar', 'size' => 15])<span data-i18n="owner_hired">دامەزرا</span> <span dir="ltr">{{ $s[5] }}</span></div>
    </div>
    @endforeach
</div>
@endsection
