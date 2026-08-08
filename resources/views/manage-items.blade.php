<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بەڕێوەبردنی کاڵا</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/manage-items.css') }}">
</head>
<body>

<!-- Global Navbar -->
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

<!-- Main Content -->
<div class="container-fluid mt-4">

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <h2 class="page-title">📦 <span data-i18n="items_title">بەڕێوەبردنی کاڵا</span></h2>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mt-3">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-info">
                    <h3 id="totalItems">0</h3>
                    <p data-i18n="items_total">کۆی کاڵاکان</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon">⚠️</div>
                <div class="stat-info">
                    <h3 id="lowStockItems">0</h3>
                    <p data-i18n="items_low_stock">کۆگای کەم</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon">❌</div>
                <div class="stat-info">
                    <h3 id="outOfStockItems">0</h3>
                    <p data-i18n="items_out_stock">کۆگا نییە</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <h3 id="totalValue">IQD 0</h3>
                    <p data-i18n="items_total_value">کۆی نرخ</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Item Form -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card item-form-card">
                <div class="card-header">
                    <h5 data-i18n="items_form_title">زانیاری کاڵا</h5>
                </div>
                <div class="card-body">
                    <form id="itemForm">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label data-i18n="items_code">کۆدی کاڵا <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="txtCode" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label data-i18n="items_name">ناوی کاڵا <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="txtName" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label data-i18n="items_category">جۆر</label>
                                    <div class="input-group">
                                        <select class="form-control" id="txtCategory">
                                            <option value="" data-i18n="items_category_placeholder">هەڵبژێرە...</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="btnManageCategories">⚙️</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label data-i18n="items_barcode">بارکۆد</label>
                                    <input type="text" class="form-control" id="txtBarcode">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label data-i18n="items_price">نرخ (IQD) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="txtPrice" step="250" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label data-i18n="items_stock">کۆگا <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="txtStock" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label data-i18n="items_min_stock">کەمترین کۆگا</label>
                                    <input type="number" class="form-control" id="txtMinStock" value="10">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label data-i18n="items_notes">تێبینی</label>
                                    <textarea class="form-control" id="txtNotes" rows="2" data-i18n="items_notes_placeholder" placeholder="قەبارە، ڕەنگ، ژمارە زنجیرە..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-success" id="btnSave">
                                <span data-i18n="save">پاشەکەوت</span>
                            </button>
                            <button type="button" class="btn btn-primary" id="btnUpdate" style="display:none;">
                                <span data-i18n="items_update">نوێکردنەوە</span>
                            </button>
                            <button type="button" class="btn btn-danger" id="btnDelete" style="display:none;">
                                <span data-i18n="delete">سڕینەوە</span>
                            </button>
                            <button type="button" class="btn btn-secondary" id="btnClear">
                                <span data-i18n="clear">پاککردنەوە</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="row mt-4 items-search-filter">
        <div class="col-md-6">
            <input type="text" class="form-control" id="txtSearch" data-i18n="pos_search_placeholder" placeholder="گەڕان بە ناو، کۆد یان بارکۆد...">
        </div>
        <div class="col-md-3">
            <select class="form-control" id="filterCategory">
                <option value="" data-i18n="pos_all_categories">هەموو جۆرەکان</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-control" id="filterStock">
                <option value="" data-i18n="items_stock_filter_all">هەموو کاڵاکان</option>
                <option value="low" data-i18n="items_low_stock">کۆگای کەم</option>
                <option value="out" data-i18n="items_out_stock">کۆگا نییە</option>
                <option value="ok" data-i18n="items_stock_ok">کۆگا هەیە</option>
            </select>
        </div>
    </div>

    <!-- Items Table -->
    <div class="row mt-3 mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th data-i18n="items_code">کۆد</th>
                                    <th data-i18n="items_name">ناوی کاڵا</th>
                                    <th data-i18n="items_category">جۆر</th>
                                    <th data-i18n="items_price">نرخ</th>
                                    <th data-i18n="items_stock">کۆگا</th>
                                    <th data-i18n="items_barcode">بارکۆد</th>
                                    <th data-i18n="items_notes">تێبینی</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Category Management Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">بەڕێوەبردنی جۆرەکان</h5>
                <button type="button" class="close" data-dismiss="modal"><span>✕</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="newCategoryName" placeholder="ناوی جۆری نوێ...">
                </div>
                <button class="btn btn-success btn-sm" id="btnAddCategory">➕ زیادکردن</button>

                <div class="mt-3">
                    <div id="categoriesList"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/languages.js') }}"></script>
<script src="{{ asset('js/navbar-global.js') }}"></script>
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/manage-items-controller.js') }}"></script>
@include('partials.footer')
</body>
</html>
