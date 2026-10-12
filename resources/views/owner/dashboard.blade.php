@extends('owner.layouts.app')
@section('nav', 'dashboard')
@section('title_key', 'owner_nav_dashboard')
@section('title_default', 'داشبۆرد')

@section('content')
<div class="ow-toolbar">
    <div>
        <p class="sub" data-i18n="owner_dash_sub">کورتەی ئەمڕۆی فرۆشگاکەت</p>
        <h2 style="margin:0; font-size:1.2rem;">{{ $store->name }}</h2>
    </div>
    <a href="{{ url('/owner/pos') }}" class="btn btn-primary">@include('owner.partials.icon', ['name' => 'cart', 'size' => 17])<span data-i18n="owner_new_sale">فرۆشتنی نوێ</span></a>
</div>

{{-- Stats --}}
<div class="ow-grid g4">
    <div class="ow-card ow-stat">
        <div class="ow-bubble tone-blue">@include('owner.partials.icon', ['name' => 'dollar', 'size' => 24])</div>
        <div>
            <div class="lbl" data-i18n="owner_stat_revenue">داهاتی ئەمڕۆ</div>
            <div class="val">IQD {{ number_format((float) $revenue, 0, '.', ',') }}</div>
            <div class="delta up">▲ 12.4% <small data-i18n="owner_vs_yesterday">بەراورد بە دوێنێ</small></div>
        </div>
    </div>
    <div class="ow-card ow-stat">
        <div class="ow-bubble tone-green">@include('owner.partials.icon', ['name' => 'file', 'size' => 24])</div>
        <div>
            <div class="lbl" data-i18n="owner_stat_orders">ژمارەی وەسڵ</div>
            <div class="val">{{ $orders }}</div>
            <div class="delta up">▲ 8 <small data-i18n="owner_vs_yesterday">بەراورد بە دوێنێ</small></div>
        </div>
    </div>
    <div class="ow-card ow-stat">
        <div class="ow-bubble tone-amber">@include('owner.partials.icon', ['name' => 'trend', 'size' => 24])</div>
        <div>
            <div class="lbl" data-i18n="owner_stat_profit">قازانجی پاک</div>
            <div class="val">IQD {{ number_format((float) max(0, $revenue * 0.22), 0, '.', ',') }}</div>
            <div class="delta down">▼ 3.1% <small data-i18n="owner_vs_yesterday">بەراورد بە دوێنێ</small></div>
        </div>
    </div>
    <div class="ow-card ow-stat">
        <div class="ow-bubble tone-red">@include('owner.partials.icon', ['name' => 'alert', 'size' => 24])</div>
        <div>
            <div class="lbl" data-i18n="owner_stat_lowstock">کاڵای کۆگای کەم</div>
            <div class="val">{{ $lowStock }}</div>
            <div class="delta"><a class="ow-link" href="#" data-i18n="owner_view_all">بینینی هەمووی</a></div>
        </div>
    </div>
</div>

{{-- Chart + Top products --}}
<div class="ow-grid g21">
    <div class="ow-card">
        <div class="ow-card-head"><h3 data-i18n="owner_chart_week">فرۆشتنی ٧ ڕۆژی ڕابردوو</h3></div>
        <div class="ow-card-body">
            <div class="ow-bars">
                @foreach ([52,70,61,92,83,72,80] as $height)
                    <div class="ow-bar-col"><span class="amt">{{ $height }}%</span><div class="ow-bar" style="--h:{{ $height }}%"></div><span class="day">{{ $loop->iteration }}</span></div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="ow-card">
        <div class="ow-card-head"><h3 data-i18n="owner_top_products">باشترین کاڵاکان</h3></div>
        <div class="ow-card-body">
            @if ($topProducts->isEmpty())
                <p style="margin:0; color: var(--text-muted);">هیچ کاڵایەک نەدۆزرایەوە.</p>
            @else
                <ul class="ow-list">
                    @foreach ($topProducts as $product)
                        <li><span class="rank">{{ $loop->iteration }}</span><span class="grow">{{ $product->name }}</span><span class="muted">{{ $product->stock }}</span></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

{{-- Recent invoices --}}
<div class="ow-card">
    <div class="ow-card-head">
        <h3 data-i18n="owner_recent_invoices">دوایین وەسڵەکان</h3>
        <a class="ow-link" href="{{ url('/owner/invoices') }}" data-i18n="owner_view_all">بینینی هەمووی</a>
    </div>
    <div class="ow-card-body" style="padding-inline:0;padding-bottom:6px">
        <div class="ow-table-wrap">
            <table class="ow-table">
                <thead><tr>
                    <th data-i18n="owner_col_invoice">ژمارە</th><th data-i18n="owner_col_customer">کڕیار</th>
                    <th data-i18n="owner_col_payment">شێوازی پارەدان</th><th data-i18n="owner_col_amount">بڕ</th><th data-i18n="owner_col_status">دۆخ</th>
                </tr></thead>
                <tbody>
                    @forelse ($recentInvoices as $invoice)
                        <tr>
                            <td class="id">{{ $invoice->order_number }}</td>
                            <td>{{ $invoice->customer_id ? optional(\App\Models\Customer::find($invoice->customer_id))->name ?? 'کڕیار' : 'کڕیاری ئاسایی' }}</td>
                            <td data-i18n="{{ strtolower($invoice->payment_method) === 'cash' ? 'pos_pay_cash' : (strtolower($invoice->payment_method) === 'card' ? 'pos_pay_card' : 'pos_pay_transfer') }}">{{ $invoice->payment_method }}</td>
                            <td class="num">IQD {{ number_format((float) $invoice->total, 0, '.', ',') }}</td>
                            <td><span class="ow-badge {{ $invoice->status === 'completed' ? 'b-paid' : ($invoice->status === 'pending' ? 'b-debt' : 'b-refund') }}" data-i18n="{{ $invoice->status === 'completed' ? 'owner_status_paid' : ($invoice->status === 'pending' ? 'owner_status_debt' : 'owner_status_refunded') }}">{{ $invoice->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center; color: var(--text-muted);">هیچ وەسڵێک نەدۆزرایەوە.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
