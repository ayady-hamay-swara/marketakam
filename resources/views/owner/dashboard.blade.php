@extends('owner.layouts.app')
@section('nav', 'dashboard')
@section('title_key', 'owner_nav_dashboard')
@section('title_default', 'داشبۆرد')

@section('content')
<div class="ow-toolbar">
    <p class="sub" data-i18n="owner_dash_sub">کورتەی ئەمڕۆی فرۆشگاکەت</p>
    <a href="{{ url('/owner/pos') }}" class="btn btn-primary">@include('partials.icon', ['name' => 'cart', 'size' => 16])<span data-i18n="owner_new_sale">فرۆشتنی نوێ</span></a>
</div>

{{-- Today: one dominant figure, the week behind it, three supporting facts --}}
<section class="ow-section first">
    <div class="ow-split">
        <div>
            <div class="ow-figure" style="margin-bottom:32px">
                <div class="lbl" data-i18n="owner_stat_revenue">داهاتی ئەمڕۆ</div>
                <div class="num" dir="ltr"><small>IQD</small>1,845,000</div>
            </div>

            <div class="ow-section-head"><h3 data-i18n="owner_chart_week">فرۆشتنی ٧ ڕۆژی ڕابردوو</h3></div>
            <div class="ow-bars">
                <div class="ow-bar-col"><span class="amt">1.2M</span><div class="ow-bar" style="--h:52%"></div><span class="day">30/9</span></div>
                <div class="ow-bar-col"><span class="amt">1.6M</span><div class="ow-bar" style="--h:70%"></div><span class="day">1/10</span></div>
                <div class="ow-bar-col"><span class="amt">1.4M</span><div class="ow-bar" style="--h:61%"></div><span class="day">2/10</span></div>
                <div class="ow-bar-col"><span class="amt">2.1M</span><div class="ow-bar" style="--h:92%"></div><span class="day">3/10</span></div>
                <div class="ow-bar-col"><span class="amt">1.9M</span><div class="ow-bar" style="--h:83%"></div><span class="day">4/10</span></div>
                <div class="ow-bar-col"><span class="amt">1.65M</span><div class="ow-bar" style="--h:72%"></div><span class="day">5/10</span></div>
                <div class="ow-bar-col today"><span class="amt">1.85M</span><div class="ow-bar" style="--h:80%"></div><span class="day">6/10</span></div>
            </div>
        </div>

        <ul class="ow-facts">
            <li><span class="k" data-i18n="owner_stat_orders">ژمارەی وەسڵ</span><span class="v">64</span></li>
            <li><span class="k" data-i18n="owner_stat_profit">قازانجی پاک</span><span class="v" dir="ltr">IQD 412,500</span></li>
            <li>
                <span class="k"><span data-i18n="owner_stat_lowstock">کاڵای کۆگای کەم</span> · <a class="ow-link" href="#" data-i18n="owner_view_all">بینینی هەمووی</a></span>
                <span class="v bad">7</span>
            </li>
        </ul>
    </div>
</section>

<section class="ow-section">
    <div class="ow-split">
        <div>
            <div class="ow-section-head">
                <h3 data-i18n="owner_recent_invoices">دوایین وەسڵەکان</h3>
                <a class="ow-link" href="{{ url('/owner/invoices') }}" data-i18n="owner_view_all">بینینی هەمووی</a>
            </div>
            <div class="ow-table-wrap">
                <table class="ow-table">
                    <thead><tr>
                        <th data-i18n="owner_col_invoice">ژمارە</th><th data-i18n="owner_col_customer">کڕیار</th>
                        <th data-i18n="owner_col_amount">بڕ</th><th data-i18n="owner_col_status">دۆخ</th>
                    </tr></thead>
                    <tbody>
                        <tr><td class="id">INV-1048</td><td data-i18n="owner_walkin">کڕیاری ئاسایی</td><td class="num" dir="ltr">IQD 24,500</td><td><span class="ow-badge b-paid" data-i18n="owner_status_paid">دراوە</span></td></tr>
                        <tr><td class="id">INV-1047</td><td>ئارام ئەحمەد</td><td class="num" dir="ltr">IQD 86,000</td><td><span class="ow-badge b-debt" data-i18n="owner_status_debt">قەرز</span></td></tr>
                        <tr><td class="id">INV-1046</td><td data-i18n="owner_walkin">کڕیاری ئاسایی</td><td class="num" dir="ltr">IQD 12,750</td><td><span class="ow-badge b-paid" data-i18n="owner_status_paid">دراوە</span></td></tr>
                        <tr><td class="id">INV-1045</td><td>شیلان کەریم</td><td class="num" dir="ltr">IQD 41,000</td><td><span class="ow-badge b-refund" data-i18n="owner_status_refunded">گەڕاوە</span></td></tr>
                        <tr><td class="id">INV-1044</td><td data-i18n="owner_walkin">کڕیاری ئاسایی</td><td class="num" dir="ltr">IQD 59,250</td><td><span class="ow-badge b-paid" data-i18n="owner_status_paid">دراوە</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <div class="ow-section-head"><h3 data-i18n="owner_top_products">باشترین کاڵاکان</h3></div>
            <ul class="ow-list">
                <li><span class="grow">ئاوی خواردنەوە 0.5L</span><span class="muted">142</span></li>
                <li><span class="grow">نانی ڕۆژانە</span><span class="muted">118</span></li>
                <li><span class="grow">شیری تازە 1L</span><span class="muted">96</span></li>
                <li><span class="grow">پەتاتە چیپس</span><span class="muted">81</span></li>
                <li><span class="grow">سابوونی دەست</span><span class="muted">57</span></li>
            </ul>
        </div>
    </div>
</section>
@endsection
