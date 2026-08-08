@csrf
<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سەرەکی - سیستەمی فرۆشتن</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
    <style>
        body { background: linear-gradient(135deg, #007bff 0%, #1611bb 100%); min-height: 100vh; }

        .dashboard-wrapper { padding: 40px 20px; }

        .welcome-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            margin-bottom: 40px;
        }

        .welcome-title {
            font-size: 48px;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .welcome-subtitle {
            font-size: 20px;
            color: #7f8c8d;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .action-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all .3s;
            box-shadow: 0 10px 30px rgba(0,0,0,.2);
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .action-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,.3);
            text-decoration: none;
            color: inherit;
        }

        .action-icon {
            font-size: 64px;
            margin-bottom: 15px;
        }

        .action-title {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .action-desc {
            font-size: 14px;
            color: #7f8c8d;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-box {
            background: rgba(255,255,255,.95);
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,.2);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: #667eea;
        }

        .stat-label {
            font-size: 14px;
            color: #7f8c8d;
            margin-top: 5px;
        }
    </style>
</head>
<body>

@include('partials.navbar')


<!-- ════════════════════════════════════════════════════════════════════
     GLOBAL SETTINGS POPUP - SIMPLIFIED (No Currency)
     ════════════════════════════════════════════════════════════════════ -->
<div class="global-popup" id="globalSettingsPanel">
    <div class="popup-header">
        <span>⚙️ <span data-i18n="settings_title">ڕێکخستنەکان</span></span>
        <button class="popup-close" id="settingsCloseBtn">✕</button>
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
            💾 <span data-i18n="settings_save">پاشەکەوت</span>
        </button>
    </div>
</div>


<!-- Backdrop for popups -->
<div class="global-popup-backdrop" id="globalBackdrop"></div>




<!-- Main Dashboard Content -->
<div class="container dashboard-wrapper">

    <!-- Welcome Card -->
    <div class="welcome-card">
        <div class="welcome-title" data-i18n="dashboard_welcome">بەخێربێیت! 👋</div>
        <div class="welcome-subtitle" data-i18n="dashboard_subtitle">سیستەمی بەڕێوەبردنی فرۆشگا</div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-value" id="statTodaySales">IQD 0</div>
            <div class="stat-label" data-i18n="dashboard_today_sales">فرۆشی ئەمڕۆ</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="statOrders">0</div>
            <div class="stat-label" data-i18n="dashboard_orders_count">ژمارەی وەسڵەکان</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="statItems">0</div>
            <div class="stat-label" data-i18n="items_total">کۆی کاڵاکان</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="statLowStock">0</div>
            <div class="stat-label" data-i18n="dashboard_low_stock">کاڵای کۆگای کەم</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="/pos-checkout" class="action-card">
            <div class="action-icon">🛒</div>
            <div class="action-title" data-i18n="dashboard_action_pos_title">فرۆشتن</div>
            <div class="action-desc" data-i18n="dashboard_action_pos_desc">دەستپێکردنی فرۆشتنی نوێ</div>
        </a>

        <a href="/manage-items" class="action-card">
            <div class="action-icon">📦</div>
            <div class="action-title" data-i18n="dashboard_action_items_title">بەڕێوەبردنی کاڵا</div>
            <div class="action-desc" data-i18n="dashboard_action_items_desc">زیادکردن و دەستکاریکردنی کاڵاکان</div>
        </a>

        <a href="/manage-employees" class="action-card">
            <div class="action-icon">👥</div>
            <div class="action-title" data-i18n="dashboard_action_employees_title">کارمەندان</div>
            <div class="action-desc" data-i18n="dashboard_action_employees_desc">بەڕێوەبردنی کارمەندان</div>
        </a>

        <a href="/search-orders" class="action-card">
            <div class="action-icon">📋</div>
            <div class="action-title" data-i18n="dashboard_action_search_title">گەڕان لە وەسڵەکان</div>
            <div class="action-desc" data-i18n="dashboard_action_search_desc">بینینی وەسڵە پێشووەکان</div>
        </a>
    </div>

</div>

<script src="{{ asset('js/languages.js') }}"></script>
<script src="{{ asset('js/navbar-global.js') }}"></script>
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script>
// Load dashboard stats
$(document).ready(function(){
    // Today's sales
    const todaySales = parseFloat(localStorage.getItem('todaySales') || 0);
    $('#statTodaySales').text('IQD ' + todaySales.toLocaleString('en-US', {minimumFractionDigits: 0}));

    // Orders count
    const orders = parseInt(localStorage.getItem('todayOrders') || 0);
    $('#statOrders').text(orders);

    // Load items count from API
    $.get('http://localhost:8080/api/items', function(items){
        $('#statItems').text(items.length);

        // Low stock count
        const lowStock = items.filter(i => i.qtyOnHand <= (i.minStockLevel || 10) && i.qtyOnHand > 0).length;
        $('#statLowStock').text(lowStock);
    });
});
</script>
@include('partials.footer')
</body>
</html>
