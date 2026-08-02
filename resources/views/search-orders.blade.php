<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="debts_title">بەڕێوەبردنی قەرز</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
    <style>
        body { background: #f5f7fa; }
        .page-title { color: #e74c3c; font-weight: 800; font-size: 32px; margin-bottom: 20px; }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,.1); display: flex; align-items: center; gap: 20px; }
        .stat-icon { font-size: 48px; }
        .stat-info h3 { font-size: 28px; font-weight: 800; margin: 0; }
        .stat-info p { font-size: 14px; color: #7f8c8d; margin: 5px 0 0 0; }
        .stat-card.red .stat-info h3 { color: #e74c3c; }
        .stat-card.green .stat-info h3 { color: #27ae60; }
        .stat-card.blue .stat-info h3 { color: #3498db; }

        /* Form Card */
        .card { border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,.1); border: none; margin-bottom: 20px; }
        .card-header { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; border-radius: 16px 16px 0 0 !important; padding: 15px 20px; font-weight: 700; }
        .form-label { font-weight: 700; font-size: 14px; color: #2c3e50; margin-bottom: 5px; }
        .form-control, .form-select { border-radius: 8px; border: 2px solid #d0d9e8; padding: 10px; }
        .form-control:focus, .form-select:focus { border-color: #e74c3c; box-shadow: 0 0 0 3px rgba(231,76,60,.1); }

        /* Buttons */
        .btn { border-radius: 10px; font-weight: 700; padding: 10px 20px; }
        .btn-danger { background: linear-gradient(135deg, #e74c3c, #c0392b); border: none; }
        .btn-success { background: linear-gradient(135deg, #27ae60, #229954); border: none; }
        .btn-warning { background: linear-gradient(135deg, #f39c12, #e67e22); border: none; }

        /* Table */
        .table { margin: 0; }
        .table thead th { background: #2c3e50; color: white; font-weight: 700; font-size: 13px; border: none; padding: 12px; white-space: nowrap; }
        .table tbody td { padding: 12px; vertical-align: middle; border-color: #ecf0f1; }
        .table tbody tr { transition: background .15s; }
        .table tbody tr:hover { background: #fff5f5; }

        /* Status Badges */
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .badge-unpaid { background: #e74c3c; color: white; }
        .badge-partial { background: #f39c12; color: white; }
        .badge-paid { background: #27ae60; color: white; }

        /* Amount Display */
        .amount-unpaid { color: #e74c3c; font-weight: 800; font-size: 16px; }
        .amount-paid { color: #27ae60; font-weight: 800; }

        /* Action Buttons */
        .btn-sm { padding: 5px 10px; font-size: 12px; }

        /* Search Section */
        .search-card { background: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,.08); margin-bottom: 20px; }
    </style>
</head>
<body>

<!-- Navbar -->
@include('partials.navbar')

<!-- Settings Popup -->
<div class="global-popup" id="globalSettingsPanel">
    <div class="popup-header">
        <span>⚙️ <span data-i18n="settings_title">ڕێکخستنەکان</span></span>
        <button class="popup-close" id="settingsCloseBtn">✕</button>
    </div>
    <div class="settings-body">
        <div class="settings-section">
            <div class="settings-label" data-i18n="settings_store">ناوی فرۆشگا</div>
            <input type="text" id="globalStoreName" class="settings-input" placeholder="فرۆشگای من">
        </div>
        <div class="settings-section">
            <div class="settings-label" data-i18n="settings_tax">ڕێژەی باج (%)</div>
            <input type="number" id="globalTaxRate" class="settings-input" placeholder="0" min="0" max="100" step="0.1">
        </div>
        <div class="settings-section">
            <div class="settings-label" data-i18n="settings_auto_print">چاپی خۆکار</div>
            <label class="toggle-label">
                <input type="checkbox" id="globalAutoPrint">
                <span class="toggle-track"></span>
                <span class="toggle-text" data-i18n="toggle_off">ناچالاک</span>
            </label>
        </div>
        <div class="settings-section">
            <div class="settings-label" data-i18n="settings_sound">دەنگ لە زیادکردن</div>
            <label class="toggle-label">
                <input type="checkbox" id="globalSound" checked>
                <span class="toggle-track"></span>
                <span class="toggle-text" data-i18n="toggle_on">چالاک</span>
            </label>
        </div>
        <div class="settings-section">
            <div class="settings-label" data-i18n="settings_cashier_name">ناوی بەکارهێنەر</div>
            <input type="text" id="globalCashierName" class="settings-input" placeholder="بەڕێوەبەر">
        </div>
        <button class="settings-save-btn" id="btnGlobalSaveSettings">💾 <span data-i18n="settings_save">پاشەکەوت</span></button>
    </div>
</div>

<!-- Calculator Popup -->
<div class="global-popup" id="globalCalcPanel">
    <div class="popup-header">
        <span>🧮 <span data-i18n="calc_title">ژمێرەر</span></span>
        <button class="popup-close" id="calcCloseBtn">✕</button>
    </div>
    <div class="calc-wrap">
        <div class="calc-display">
            <div class="calc-expr" id="globalCalcExpr">&nbsp;</div>
            <div class="calc-result" id="globalCalcResult">0</div>
        </div>
        <div class="calc-grid">
            <button class="cb cb-fn" data-action="clear">AC</button>
            <button class="cb cb-fn" data-action="sign">+/−</button>
            <button class="cb cb-fn" data-action="percent">%</button>
            <button class="cb cb-op" data-op="÷">÷</button>
            <button class="cb" data-num="7">7</button>
            <button class="cb" data-num="8">8</button>
            <button class="cb" data-num="9">9</button>
            <button class="cb cb-op" data-op="×">×</button>
            <button class="cb" data-num="4">4</button>
            <button class="cb" data-num="5">5</button>
            <button class="cb" data-num="6">6</button>
            <button class="cb cb-op" data-op="−">−</button>
            <button class="cb" data-num="1">1</button>
            <button class="cb" data-num="2">2</button>
            <button class="cb" data-num="3">3</button>
            <button class="cb cb-op" data-op="+">+</button>
            <button class="cb cb-zero" data-num="0">0</button>
            <button class="cb" data-num=".">.</button>
            <button class="cb cb-eq" data-action="equals">=</button>
        </div>
    </div>
</div>

<div class="global-popup-backdrop" id="globalBackdrop"></div>

<!-- Main Content -->
<div class="container-fluid mt-4">

    <!-- Page Title -->
    <h2 class="page-title">💳 <span data-i18n="debts_title">بەڕێوەبردنی قەرزەکان</span></h2>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card red">
            <div class="stat-icon">💸</div>
            <div class="stat-info">
                <h3 id="totalDebt">IQD 0</h3>
                <p data-i18n="debts_total">کۆی قەرز</p>
            </div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <h3 id="totalPaid">IQD 0</h3>
                <p data-i18n="debts_total_paid">واردبووی گشتی</p>
            </div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3 id="totalCustomers">0</h3>
                <p data-i18n="debts_total_customers">ژمارەی قەرزاران</p>
            </div>
        </div>
    </div>

    <!-- Add Debt Form -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">➕ <span data-i18n="debts_add">زیادکردنی قەرزی نوێ</span></h5>
        </div>
        <div class="card-body">
            <form id="debtForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" data-i18n="debts_customer_name">ناوی کەس <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="txtCustomerName" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label" data-i18n="debts_phone">ژمارە مۆبایل</label>
                            <input type="text" class="form-control" id="txtPhone" placeholder="0750...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label" data-i18n="debts_amount">بڕی قەرز (IQD) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="txtAmount" step="1000" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label" data-i18n="debts_date">بەروار</label>
                            <input type="date" class="form-control" id="txtDate">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" data-i18n="debts_notes">تێبینی</label>
                            <input type="text" class="form-control" id="txtNotes" placeholder="بۆ کڕینی...">
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-danger" id="btnSave">💾 <span data-i18n="save">پاشەکەوت</span></button>
                    <button type="button" class="btn btn-primary" id="btnUpdate" style="display:none;">✏️ <span data-i18n="debts_update">نوێکردنەوە</span></button>
                    <button type="button" class="btn btn-secondary" id="btnClear">🔄 <span data-i18n="clear">پاككردنەوە</span></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="search-card">
        <div class="row">
            <div class="col-md-4">
                <input type="text" class="form-control" id="txtSearch" data-i18n="debts_search" placeholder="گەڕان بە ناو یان ژمارە مۆبایل...">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterStatus">
                    <option value="" data-i18n="debts_all_status">هەموو دۆخەکان</option>
                    <option value="unpaid" data-i18n="debts_status_unpaid">قەرز (نەدراوە)</option>
                    <option value="partial" data-i18n="debts_status_partial">بەشێکی دراوە</option>
                    <option value="paid" data-i18n="debts_status_paid">تەواو دراوە</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterSort">
                    <option value="date_desc" data-i18n="debts_sort_newest">نوێترین</option>
                    <option value="date_asc" data-i18n="debts_sort_oldest">کۆنترین</option>
                    <option value="amount_desc" data-i18n="debts_sort_highest">زۆرترین قەرز</option>
                    <option value="amount_asc" data-i18n="debts_sort_lowest">کەمترین قەرز</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-warning w-100" id="btnExport">📊 <span data-i18n="debts_export">دەرهێنان</span></button>
            </div>
        </div>
    </div>

    <!-- Debts Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ژ.</th>
                            <th data-i18n="debts_customer_name">ناوی کەس</th>
                            <th data-i18n="debts_phone">مۆبایل</th>
                            <th data-i18n="debts_amount">بڕی قەرز</th>
                            <th data-i18n="debts_paid_amount">بڕە پارەی دراو</th>
                            <th data-i18n="debts_remaining">ماوە</th>
                            <th data-i18n="debts_status">دۆخ</th>
                            <th data-i18n="debts_date">بەروار</th>
                            <th data-i18n="debts_notes">تێبینی</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="debtsTableBody">
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div style="font-size:48px;">💳</div>
                                <p class="text-muted" data-i18n="debts_empty">هیچ قەرزێک تۆمار نەکراوە</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">💰 <span data-i18n="debts_payment_title">واردکردنی پارە</span></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong id="paymentCustomerName"></strong>
                    <div class="text-muted" id="paymentDebtInfo"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label" data-i18n="debts_payment_amount">بڕی پارەی دراو (IQD)</label>
                    <input type="number" class="form-control form-control-lg" id="txtPaymentAmount" step="1000">
                </div>
                <div class="mb-3">
                    <label class="form-label" data-i18n="debts_payment_notes">تێبینی</label>
                    <input type="text" class="form-control" id="txtPaymentNotes" placeholder="واردکرا لە...">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" data-i18n="cancel">هەڵوەشاندنەوە</button>
                <button class="btn btn-success" id="btnConfirmPayment">✅ <span data-i18n="debts_confirm">واردکردن</span></button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">⚠️ <span data-i18n="debts_confirm">دڵنیابوونەوە</span></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <p data-i18n="debts_confirm_delete">دڵنیایت لە سڕینەوەی ئەم قەرزە؟</p>
                <p class="text-danger mb-0"><strong data-i18n="debts_irreversible">ئەم کردارە ناگەڕێتەوە!</strong></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" data-i18n="cancel">هەڵوەشاندنەوە</button>
                <button class="btn btn-danger" id="btnConfirmDelete">🗑️ <span data-i18n="delete">سڕینەوە</span></button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/languages.js') }}"></script>
<script src="{{ asset('js/navbar-global.js') }}"></script>
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/manage-debts-controller.js') }}"></script>
</body>
</html>
