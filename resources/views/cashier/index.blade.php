@csrf
<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title data-i18n="dashboard_title">سەرەکی - سیستەمی فرۆشتن</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <style>
        /* White page. Structure comes from hairlines and type, not from floating cards. */
        body { background: #fff; min-height: 100vh; }
        .dashboard-wrapper { padding: 48px 20px 64px; max-width: 960px; }

        .welcome-card { text-align: start; padding: 0 0 24px; margin-bottom: 24px; border-bottom: 1px solid var(--line, #e1e8f2); }
        .welcome-title { font-size: 32px; font-weight: 700; color: var(--navy, #1a2b4a); margin-bottom: 4px; }
        .welcome-subtitle { font-size: 16px; color: var(--text-muted, #5f6f86); }

        /* four figures in one band */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); border-block: 1px solid var(--line, #e1e8f2); margin-bottom: 32px; }
        .stat-box { padding: 16px 24px; text-align: start; border-inline-start: 1px solid var(--line, #e1e8f2); }
        .stat-box:first-child { border-inline-start: none; padding-inline-start: 0; }
        .stat-value { font-size: 24px; font-weight: 700; color: var(--navy, #1a2b4a); font-variant-numeric: tabular-nums; }
        .stat-label { font-size: 14px; color: var(--text-muted, #5f6f86); margin-top: 4px; }
        .stat-label i { margin-inline-end: 6px; color: var(--accent-ink, #1d6fe0); }

        /* destinations as a list */
        .quick-actions { display: block; }
        .action-card {
            display: grid; grid-template-columns: 40px 1fr 18px; grid-template-areas: "icon title go" "icon desc go";
            column-gap: 16px; align-items: center; padding: 16px 8px;
            border-bottom: 1px solid var(--line, #e1e8f2); color: var(--navy, #1a2b4a); text-decoration: none;
        }
        .action-card:hover { background: var(--tint, #f6f8fc); color: var(--navy, #1a2b4a); text-decoration: none; }
        .action-card::after {
            content: ''; grid-area: go; width: 18px; height: 18px; background: var(--text-muted, #5f6f86);
            -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='9 18 15 12 9 6'/%3E%3C/svg%3E") center / contain no-repeat;
                    mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='9 18 15 12 9 6'/%3E%3C/svg%3E") center / contain no-repeat;
        }
        html[dir="rtl"] .action-card::after { transform: scaleX(-1); }
        .action-icon { grid-area: icon; width: 40px; height: 40px; margin: 0; border-radius: 8px; background: var(--tint, #f6f8fc); border: 1px solid var(--line, #e1e8f2); display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--navy, #1a2b4a); }
        .action-title { grid-area: title; font-size: 16px; font-weight: 600; color: var(--navy, #1a2b4a); }
        .action-desc { grid-area: desc; font-size: 14px; color: var(--text-muted, #5f6f86); }
    </style>
    <script src="https://kit.fontawesome.com/dee4ca4318.js" crossorigin="anonymous"></script>
</head>
<body>

@include('cashier.partials.navbar')


<!-- ════════════════════════════════════════════════════════════════════
     GLOBAL SETTINGS POPUP - SIMPLIFIED (No Currency)
     ════════════════════════════════════════════════════════════════════ -->
<div class="global-popup" id="globalSettingsPanel">
    <div class="popup-header">
        <span><span data-i18n="settings_title">ڕێکخستنەکان</span></span>
        <button class="popup-close" id="settingsCloseBtn">×</button>
    </div>
    <div class="settings-body">

        <div class="settings-section">
            <div class="settings-label" data-i18n="settings_store">ناوی فرۆشگا</div>
            <input type="text" id="globalStoreName" class="settings-input" data-i18n="settings_store_placeholder" placeholder="فرۆشگای من">
        </div>

        <div class="settings-section">
            <div class="settings-label" data-i18n="settings_cashier_name">ناوی بەکارهێنەر</div>
            <input type="text" id="globalCashierName" class="settings-input" data-i18n="settings_cashier_placeholder" placeholder="بەڕێوەبەر">
        </div>

        <button class="settings-save-btn" id="btnGlobalSaveSettings">
            <span data-i18n="settings_save">پاشەکەوت</span>
        </button>
    </div>
</div>


<!-- Backdrop for popups -->
<div class="global-popup-backdrop" id="globalBackdrop"></div>




<!-- Main Dashboard Content -->
<div class="container dashboard-wrapper">

    <!-- Welcome Card -->
    <div class="welcome-card">
        <div class="welcome-title" data-i18n="dashboard_welcome">بەخێربێیت!</div>
        <div class="welcome-subtitle" data-i18n="dashboard_subtitle">سیستەمی بەڕێوەبردنی فرۆشگا</div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-value" id="statTodaySales">IQD {{ number_format((float) ($today_sales ?? 0), 0, '.', ',') }}</div>
            <div class="stat-label"><i class="fas fa-money-bill-wave" aria-hidden="true"></i> <span data-i18n="dashboard_today_sales">فرۆشی ئەمڕۆ</span></div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="statOrders">{{ (int) ($orders_count ?? 0) }}</div>
            <div class="stat-label"><i class="fas fa-receipt" aria-hidden="true"></i> <span data-i18n="dashboard_orders_count">ژمارەی وەسڵەکان</span></div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="statItems">{{ (int) ($items_count ?? 0) }}</div>
            <div class="stat-label"><i class="fas fa-box" aria-hidden="true"></i> <span data-i18n="items_total">کۆی کاڵاکان</span></div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="statLowStock">{{ (int) ($low_stock_count ?? 0) }}</div>
            <div class="stat-label"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> <span data-i18n="dashboard_low_stock">کاڵای کۆگای کەم</span></div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="/pos-checkout" class="action-card">
            <div class="action-icon"><i class="fas fa-cash-register" aria-hidden="true"></i></div>
            <div class="action-title" data-i18n="dashboard_action_pos_title">فرۆشتن</div>
            <div class="action-desc" data-i18n="dashboard_action_pos_desc">دەستپێکردنی فرۆشتنی نوێ</div>
        </a>

        <a href="/manage-items" class="action-card">
            <div class="action-icon"><i class="fas fa-boxes" aria-hidden="true"></i></div>
            <div class="action-title" data-i18n="dashboard_action_items_title">بەڕێوەبردنی کاڵا</div>
            <div class="action-desc" data-i18n="dashboard_action_items_desc">زیادکردن و دەستکاریکردنی کاڵاکان</div>
        </a>

        <a href="/manage-employees" class="action-card">
            <div class="action-icon"><i class="fas fa-users" aria-hidden="true"></i></div>
            <div class="action-title" data-i18n="dashboard_action_employees_title">کارمەندان</div>
            <div class="action-desc" data-i18n="dashboard_action_employees_desc">بەڕێوەبردنی کارمەندان</div>
        </a>

        <a href="/search-orders" class="action-card">
            <div class="action-icon"><i class="fas fa-file-invoice" aria-hidden="true"></i></div>
            <div class="action-title" data-i18n="dashboard_action_search_title">گەڕان لە وەسڵەکان</div>
            <div class="action-desc" data-i18n="dashboard_action_search_desc">بینینی وەسڵە پێشووەکان</div>
        </a>
    </div>

</div>

<script src="{{ asset('js/languages.js') }}"></script>
<script src="{{ asset('js/navbar-global.js') }}"></script>
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@include('partials.footer')
</body>
</html>
