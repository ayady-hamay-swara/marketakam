@extends('owner.layouts.app')
@section('nav', 'invoices')
@section('title_key', 'owner_nav_invoices')
@section('title_default', 'وەسڵەکان')

@section('content')
<div class="ow-toolbar">
    <p class="sub" data-i18n="owner_inv_sub">هەموو وەسڵەکانی فرۆشگا</p>
    <a href="{{ url('/owner/pos') }}" class="btn btn-primary">@include('owner.partials.icon', ['name' => 'plus', 'size' => 17])<span data-i18n="owner_new_sale">فرۆشتنی نوێ</span></a>
</div>

<div class="ow-filters">
    <div class="ow-search">
        @include('owner.partials.icon', ['name' => 'search', 'size' => 18])
        <input type="text" class="form-control" data-i18n="owner_search_ph" placeholder="گەڕان بە ژمارە یان ناو...">
    </div>
    <select class="form-control">
        <option data-i18n="owner_filter_all_status">هەموو دۆخەکان</option>
        <option data-i18n="owner_status_paid">دراوە</option>
        <option data-i18n="owner_status_debt">قەرز</option>
        <option data-i18n="owner_status_refunded">گەڕاوە</option>
    </select>
    <select class="form-control">
        <option data-i18n="owner_filter_all_pay">هەموو شێوازەکان</option>
        <option data-i18n="pos_pay_cash">کاش</option>
        <option data-i18n="pos_pay_card">کارت</option>
        <option data-i18n="pos_pay_transfer">گواستنەوە</option>
    </select>
</div>

<div class="ow-card">
    <div class="ow-table-wrap">
        <table class="ow-table">
            <thead><tr>
                <th data-i18n="owner_col_invoice">ژمارە</th><th data-i18n="owner_col_date">بەروار</th><th data-i18n="owner_col_customer">کڕیار</th>
                <th data-i18n="owner_items_count">کاڵا</th><th data-i18n="owner_col_payment">شێوازی پارەدان</th>
                <th data-i18n="owner_col_amount">بڕ</th><th data-i18n="owner_col_status">دۆخ</th><th></th>
            </tr></thead>
            <tbody>
                @php
                $rows = [
                    ['INV-1048','2026-10-06 14:32','owner_walkin','کڕیاری ئاسایی',4,'pos_pay_cash','کاش','24,500','paid'],
                    ['INV-1047','2026-10-06 13:50',null,'ئارام ئەحمەد',9,'owner_status_debt','قەرز','86,000','debt'],
                    ['INV-1046','2026-10-06 12:18','owner_walkin','کڕیاری ئاسایی',2,'pos_pay_card','کارت','12,750','paid'],
                    ['INV-1045','2026-10-06 11:05',null,'شیلان کەریم',6,'pos_pay_cash','کاش','41,000','refund'],
                    ['INV-1044','2026-10-06 10:41','owner_walkin','کڕیاری ئاسایی',7,'pos_pay_transfer','گواستنەوە','59,250','paid'],
                    ['INV-1043','2026-10-05 19:22',null,'هەڵمەت ڕەسوڵ',12,'owner_status_debt','قەرز','132,400','debt'],
                    ['INV-1042','2026-10-05 18:07','owner_walkin','کڕیاری ئاسایی',3,'pos_pay_cash','کاش','18,000','paid'],
                    ['INV-1041','2026-10-05 16:44','owner_walkin','کڕیاری ئاسایی',5,'pos_pay_card','کارت','33,500','paid'],
                ];
                $badge = ['paid'=>['b-paid','owner_status_paid','دراوە'],'debt'=>['b-debt','owner_status_debt','قەرز'],'refund'=>['b-refund','owner_status_refunded','گەڕاوە']];
                @endphp
                @foreach($rows as $r)
                <tr>
                    <td class="id">{{ $r[0] }}</td>
                    <td class="num">{{ $r[1] }}</td>
                    <td @if($r[2]) data-i18n="{{ $r[2] }}" @endif>{{ $r[3] }}</td>
                    <td class="num">{{ $r[4] }}</td>
                    <td data-i18n="{{ $r[5] }}">{{ $r[6] }}</td>
                    <td class="num">IQD {{ $r[7] }}</td>
                    <td><span class="ow-badge {{ $badge[$r[8]][0] }}" data-i18n="{{ $badge[$r[8]][1] }}">{{ $badge[$r[8]][2] }}</span></td>
                    <td><button class="ow-icon-btn" style="width:34px;height:34px" type="button" aria-label="View">@include('owner.partials.icon', ['name' => 'eye', 'size' => 16])</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="ow-pager"><button class="on">1</button><button>2</button><button>3</button><button>8</button></div>
</div>
@endsection
