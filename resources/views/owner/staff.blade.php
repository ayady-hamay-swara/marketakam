@extends('owner.layouts.app')
@section('nav', 'staff')
@section('title_key', 'owner_nav_staff')
@section('title_default', 'کارمەندان')

@section('content')
<div class="ow-toolbar">
    <p class="sub" data-i18n="owner_staff_sub">تیمی فرۆشگاکەت</p>
    <a href="{{ url('/manage-employees') }}" class="btn btn-secondary" data-i18n="owner_manage_full">بەڕێوەبردنی تەواو</a>
    <button class="btn btn-primary" type="button">@include('partials.icon', ['name' => 'plus', 'size' => 16])<span data-i18n="owner_add_staff">زیادکردنی کارمەند</span></button>
</div>

@php
$staff = [
    ['EMP001','ئاراس محەمەد','profile_role_owner','خاوەن','0750 111 2233','2023-04-12',true],
    ['EMP002','دیاکۆ ڕەشید','profile_role_manager','بەڕێوەبەر','0750 222 3344','2023-06-01',true],
    ['EMP003','هێمن سەلام','owner_pos_assistant','یاریدەدەری بەڕێوەبەر','0770 333 4455','2024-01-15',true],
    ['EMP004','ژیلا ئەمین','profile_role_cashier','کاشێر','0770 444 5566','2024-08-20',true],
    ['EMP005','کارزان ئیبراهیم','profile_role_cashier','کاشێر','0750 555 6677','2025-02-03',false],
];
@endphp

<div class="ow-table-wrap">
    <table class="ow-table">
        <thead><tr>
            <th data-i18n="owner_nav_staff">کارمەندان</th>
            <th data-i18n="owner_col_position">پلە</th>
            <th data-i18n="owner_col_phone">ژمارەی تەلەفون</th>
            <th data-i18n="owner_hired">دامەزرا</th>
            <th data-i18n="owner_col_status">دۆخ</th>
        </tr></thead>
        <tbody>
        @foreach($staff as $i => $s)
            <tr>
                <td>
                    <div class="who">
                        <span class="ow-avatar {{ ['', 'av2', 'av3', 'av4'][$i % 4] }}">{{ mb_substr($s[1], 0, 1) }}</span>
                        <div><b>{{ $s[1] }}</b><small>{{ $s[0] }}</small></div>
                    </div>
                </td>
                <td><span class="ow-badge b-info" data-i18n="{{ $s[2] }}">{{ $s[3] }}</span></td>
                <td class="num" dir="ltr">{{ $s[4] }}</td>
                <td class="num" dir="ltr">{{ $s[5] }}</td>
                <td>
                    <span class="dot {{ $s[6] ? 'on' : 'off' }}"></span><span data-i18n="{{ $s[6] ? 'owner_active' : 'owner_inactive' }}">{{ $s[6] ? 'چالاک' : 'ناچالاک' }}</span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
