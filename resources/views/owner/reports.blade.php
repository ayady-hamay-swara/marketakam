@extends('owner.layouts.app')
@section('nav', 'reports')
@section('title_key', 'owner_nav_reports')
@section('title_default', 'ڕاپۆرتەکان')

@section('content')
<div class="ow-toolbar">
    <p class="sub" data-i18n="owner_reports_sub">شیکاری فرۆش و قازانج</p>
    <div class="ow-seg" id="period">
        <button type="button" data-i18n="owner_period_today">ئەمڕۆ</button>
        <button type="button" data-i18n="owner_period_week">ئەم هەفتەیە</button>
        <button type="button" class="on" data-i18n="owner_period_month">ئەم مانگە</button>
        <button type="button" data-i18n="owner_period_year">ئەمساڵ</button>
    </div>
    <button class="btn btn-secondary" type="button">@include('partials.icon', ['name' => 'download', 'size' => 16])<span data-i18n="owner_export">هەناردەکردن</span></button>
</div>

<div class="ow-band">
    <div><div class="lbl" data-i18n="owner_rep_sales">کۆی فرۆش</div><div class="val" dir="ltr">IQD 48.2M</div></div>
    <div><div class="lbl" data-i18n="owner_rep_expenses">خەرجی</div><div class="val" dir="ltr">IQD 31.7M</div></div>
    <div><div class="lbl" data-i18n="owner_stat_profit">قازانجی پاک</div><div class="val" dir="ltr">IQD 16.5M</div></div>
    <div><div class="lbl" data-i18n="owner_rep_avg">ناوەندی وەسڵ</div><div class="val" dir="ltr">IQD 37,400</div></div>
</div>

<section class="ow-section">
    <div class="ow-split">
        <div>
            <div class="ow-section-head"><h3 data-i18n="owner_rep_monthly">فرۆشی مانگانە</h3></div>
            <div class="ow-bars">
                <div class="ow-bar-col"><span class="amt">38M</span><div class="ow-bar" style="--h:62%"></div><span class="day">5/26</span></div>
                <div class="ow-bar-col"><span class="amt">41M</span><div class="ow-bar" style="--h:67%"></div><span class="day">6/26</span></div>
                <div class="ow-bar-col"><span class="amt">36M</span><div class="ow-bar" style="--h:59%"></div><span class="day">7/26</span></div>
                <div class="ow-bar-col"><span class="amt">44M</span><div class="ow-bar" style="--h:72%"></div><span class="day">8/26</span></div>
                <div class="ow-bar-col"><span class="amt">44M</span><div class="ow-bar" style="--h:72%"></div><span class="day">9/26</span></div>
                <div class="ow-bar-col today"><span class="amt">48M</span><div class="ow-bar" style="--h:80%"></div><span class="day">10/26</span></div>
            </div>
        </div>
        <div>
            <div class="ow-section-head"><h3 data-i18n="owner_rep_by_category">بەپێی جۆر</h3></div>
            <div class="ow-prog"><span data-i18n="owner_cat_drinks">خواردنەوە</span><div class="ow-track"><div class="ow-fill" style="--w:38%"></div></div><span class="pct">38%</span></div>
            <div class="ow-prog"><span data-i18n="owner_cat_snacks">خواردنی سووک</span><div class="ow-track"><div class="ow-fill" style="--w:27%"></div></div><span class="pct">27%</span></div>
            <div class="ow-prog"><span data-i18n="owner_cat_household">کەلوپەلی ماڵ</span><div class="ow-track"><div class="ow-fill" style="--w:21%"></div></div><span class="pct">21%</span></div>
            <div class="ow-prog"><span data-i18n="owner_cat_hygiene">پاکوخاوێنی</span><div class="ow-track"><div class="ow-fill" style="--w:14%"></div></div><span class="pct">14%</span></div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.querySelectorAll('#period button').forEach(b => b.addEventListener('click', () => {
    document.querySelectorAll('#period button').forEach(x => x.classList.remove('on'));
    b.classList.add('on');
}));
</script>
@endpush
