<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فرۆشتن - سیستەمی فرۆشگا</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/navbar-global.css">
    <link rel="stylesheet" href="css/pos-checkout.css">
</head>
<body>

@include('partials.navbar')

<!-- Settings & Calculator Popups (same as before) -->
<div class="global-popup" id="globalSettingsPanel">
    <div class="popup-header">
        <span>⚙️ ڕێکخستنەکان</span>
        <button class="popup-close" id="settingsCloseBtn">✕</button>
    </div>
    <div class="settings-body">
        <div class="settings-section">
            <div class="settings-label">ناوی فرۆشگا</div>
            <input type="text" id="globalStoreName" class="settings-input" placeholder="فرۆشگای من">
        </div>
        <div class="settings-section">
            <div class="settings-label">ڕێژەی باج (%)</div>
            <input type="number" id="globalTaxRate" class="settings-input" placeholder="0" min="0" max="100" step="0.1">
        </div>
        <div class="settings-section">
            <div class="settings-label">چاپی خۆکار</div>
            <label class="toggle-label">
                <input type="checkbox" id="globalAutoPrint">
                <span class="toggle-track"></span>
                <span class="toggle-text">ناچالاک</span>
            </label>
        </div>
        <div class="settings-section">
            <div class="settings-label">دەنگ لە زیادکردن</div>
            <label class="toggle-label">
                <input type="checkbox" id="globalSound" checked>
                <span class="toggle-track"></span>
                <span class="toggle-text">چالاک</span>
            </label>
        </div>
        <div class="settings-section">
            <div class="settings-label">ناوی بەکارهێنەر</div>
            <input type="text" id="globalCashierName" class="settings-input" placeholder="بەڕێوەبەر">
        </div>
        <button class="settings-save-btn" id="btnGlobalSaveSettings">💾 پاشەکەوت</button>
    </div>
</div>

<div class="global-popup" id="globalCalcPanel">
    <div class="popup-header">
        <span>🧮 ژمێرەر</span>
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

<!-- POS Layout -->
<div class="pos-wrapper">
    <div class="pos-layout">

        <!-- LEFT PANEL -->
        <div class="pos-left">

            <!-- Stats Bar -->
            <div class="stats-bar">
                <div class="stat-pill">
                    <span class="stat-lbl">فرۆشی ئەمڕۆ</span>
                    <span class="stat-val" id="todaySales">IQD 0</span>
                </div>
                <div class="stat-pill">
                    <span class="stat-lbl">وەسڵەکان</span>
                    <span class="stat-val" id="todayTransactions">0</span>
                </div>
                <div class="stat-pill">
                    <span class="stat-lbl">کاشێر</span>
                    <span class="stat-val" id="cashierName">بەڕێوەبەر</span>
                </div>
            </div>

            <!-- Search + Category -->
            <div class="search-row">
                <div class="search-wrap">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="productSearch" class="search-input"
                           placeholder="گەڕان بە ناو، کۆد یان بارکۆد..." autofocus>
                    <div class="product-results" id="productResults" style="display:none;"></div>
                </div>
                <select id="categoryFilter" class="category-select">
                    <option value="">هەموو جۆرەکان</option>
                </select>
            </div>

            <!-- Cart Box -->
            <div class="cart-box">
                <div class="cart-box-header">
                    <span>🛒 کاڵاکانی سەبەتە <span class="cart-count" id="cartCount">0 دانە</span></span>
                    <button class="btn-clear-cart" id="btnClearCart">🗑 پاککردنەوە</button>
                </div>
                <div class="cart-table-head">
                    <span class="col-name">کاڵا</span>
                    <span class="col-qty">ژمارە</span>
                    <span class="col-price">نرخی یەک</span>
                    <span class="col-total">کۆی گشتی</span>
                    <span class="col-action"></span>
                </div>
                <div class="cart-table-body" id="cartItems">
                    <div class="cart-empty">
                        <div style="font-size:48px;">🛒</div>
                        <p>سەبەتە بەتاڵە</p>
                        <small>کاڵایەک بگەڕێ یان سکان بکە</small>
                    </div>
                </div>
            </div>

            <!-- Totals Bar -->
            <div class="totals-bar">
                <div class="total-item">
                    <span class="total-lbl">کۆی لاوەکی:</span>
                    <span class="total-val" id="subtotal">IQD 0</span>
                </div>
                <div class="total-item discount-item">
                    <span class="total-lbl">داشکاندن:</span>
                    <input type="number" id="discountPercent" class="discount-input" value="0" min="0" max="100" step="1">
                    <span class="total-lbl">%</span>
                    <span class="total-val text-danger" id="discountAmount">- IQD 0</span>
                </div>
                <div class="total-item total-final">
                    <span class="total-lbl">کۆی گشتی:</span>
                    <span class="total-val" id="totalAmount">IQD 0</span>
                </div>
            </div>

        </div>

        <!-- RIGHT PANEL (Updated with Debt button) -->
        <div class="pos-right">

            <!-- Payment Method -->
            <div class="right-section payment-section">
                <div class="right-section-title">💳 شێوازی پارەدان</div>
                <div class="payment-btns">
                    <button class="pay-btn active" data-method="CASH">💵 کاش</button>
                    <button class="pay-btn" data-method="CARD">💳 کارت</button>
                    <button class="pay-btn" data-method="TRANSFER">🏦 گواستنەوە</button>
                </div>
                <div id="cashSection" class="cash-section">
                    <label class="cash-label">بڕی وەرگیراو</label>
                    <input type="number" id="amountReceived" class="cash-input" placeholder="0" step="1000">
                    <div class="change-row">
                        <span>پارەی ماوە:</span>
                        <span id="changeAmount" class="change-val">IQD 0</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons (Updated with Debt button) -->
            <div class="right-section action-section">
                <button class="btn-complete" id="btnCompleteSale">✅ تەواوکردنی فرۆشتن</button>

                <!-- NEW: Sell as Debt Button -->
                <button class="btn-debt" id="btnSellAsDebt">💳 فرۆشتن بە قەرز</button>

                <div class="btn-row-2">
                    <button class="btn-hold" id="btnHold">⏸ ڕاگرتن</button>
                    <button class="btn-cancel" id="btnCancel">✕ هەڵوەشاندنەوە</button>
                </div>
                <button class="btn-return" id="btnReturn">↩ گەڕاندنەوە</button>
            </div>

        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">✅ فرۆشتن تەواو بوو!</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body" id="receiptContent"></div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="window.print()">🖨️ چاپ</button>
                <button class="btn btn-success" data-dismiss="modal" id="btnNewSale">➕ فرۆشتنی نوێ</button>
            </div>
        </div>
    </div>
</div>

<!-- Customer Selection Modal (NEW) -->
<div class="modal fade" id="customerSelectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white;">
                <h5 class="modal-title">👤 هەڵبژاردنی کڕیار بۆ قەرز</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">

                <!-- Quick Add Customer -->
                <div class="card mb-3" style="border: 2px solid #e74c3c;">
                    <div class="card-body">
                        <h6 class="text-danger mb-3">➕ زیادکردنی کڕیاری نوێ (خێرا)</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="quickCustomerName" placeholder="ناوی کڕیار *">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="quickCustomerPhone" placeholder="ژمارە مۆبایل">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-danger btn-block" id="btnQuickAddCustomer">زیادکردن</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Existing -->
                <div class="form-group">
                    <input type="text" class="form-control" id="customerSearchInput"
                           placeholder="گەڕان بە ناو یان ژمارە مۆبایل...">
                </div>

                <!-- Customers List -->
                <div id="customersList" style="max-height: 300px; overflow-y: auto;">
                    <div class="text-center text-muted py-4">
                        <div style="font-size: 48px;">👥</div>
                        <p>چاوەڕوان بە...</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Return Modal -->
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">↩ گەڕاندنەوە</h5>
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>ژمارەی وەسڵ</label>
                    <input type="text" class="form-control" id="returnOrderNumber" placeholder="ORD0000001">
                </div>
                <div class="form-group">
                    <label>هۆکار</label>
                    <select class="form-control" id="returnReason">
                        <option value="">هەڵبژێرە...</option>
                        <option value="DEFECTIVE">کاڵا خراپە</option>
                        <option value="WRONG_ITEM">کاڵای هەڵە</option>
                        <option value="CUSTOMER_CHANGE">کڕیار بیری گۆڕی</option>
                        <option value="OTHER">هۆکاری تر</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>تێبینی</label>
                    <textarea class="form-control" id="returnNotes" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">هەڵوەشاندنەوە</button>
                <button class="btn btn-warning" id="btnProcessReturn">جێبەجێکردن</button>
            </div>
        </div>
    </div>
</div>

<script src="js/languages.js"></script>
<script src="js/navbar-global.js"></script>
<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/pos-checkout-debt-controller.js"></script>

<style>
/* Additional styles for debt button */
.btn-debt {
    width: 100%;
    padding: 14px;
    font-size: 16px;
    font-weight: 800;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    cursor: pointer;
    letter-spacing: .3px;
    box-shadow: 0 4px 15px rgba(231,76,60,.4);
    transition: all .2s;
    margin-bottom: 8px;
}

.btn-debt:hover {
    transform: translateY(-2px);
    box-shadow: 0 7px 20px rgba(231,76,60,.5);
}

/* Customer list styles */
.customer-item {
    padding: 12px 15px;
    border: 2px solid #ecf0f1;
    border-radius: 10px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.customer-item:hover {
    border-color: #e74c3c;
    background: #fff5f5;
    transform: translateX(-3px);
}

.customer-item.selected {
    border-color: #e74c3c;
    background: #ffe6e6;
}

.customer-name {
    font-weight: 700;
    font-size: 15px;
    color: #2c3e50;
}

.customer-phone {
    font-size: 13px;
    color: #7f8c8d;
    margin-top: 2px;
}

.customer-debt-badge {
    background: #e74c3c;
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
}
</style>

</body>
</html>
