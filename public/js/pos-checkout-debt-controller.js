/**
 * POS CHECKOUT CONTROLLER - With Debt Integration
 */

const ITEMS_URL = "/api/items";
const ORDERS_URL = "/api/orders";
const CUSTOMERS_URL = "/api/customers";
const CATEGORIES_URL = "/api/categories";
const DEBTS_URL = "/api/debts";

let allProducts = [], allCustomers = [];
let cart = [], paymentMethod = "CASH";
let selectedCustomerForDebt = null;
let hasUserTouchedReceivedAmount = false;

function normalizeUnicodeDigits(value) {
    if (value === null || value === undefined) {
        return '';
    }

    const map = {
        '٠': '0', '١': '1', '٢': '2', '٣': '3', '٤': '4',
        '٥': '5', '٦': '6', '٧': '7', '٨': '8', '٩': '9',
        '۰': '0', '۱': '1', '۲': '2', '۳': '3', '۴': '4',
        '۵': '5', '۶': '6', '۷': '7', '۸': '8', '۹': '9'
    };

    return String(value).replace(/[٠-٩۰-۹]/g, char => map[char] || char);
}

// ═══════════════════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════════════════
$(document).ready(function(){
    console.log('Document ready - initializing POS');
    loadCategories();
    loadProducts();
    setupPosListeners();
    updateStats();
    console.log('POS initialization complete');
});

// Configure CSRF header for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    }
});

function setupPosListeners() {
    $('#productSearch').on('input', function(){
        const raw = $(this).val();
        const q = normalizeUnicodeDigits(raw).trim();
        console.log('Search input event:', q);

        if (!q.length) {
            $('#productResults').hide();
            return;
        }

        const exactMatch = allProducts.find(product => {
            const code = normalizeUnicodeDigits(product.code || '');
            const barcode = normalizeUnicodeDigits(product.barcode || '');
            return code === q || barcode === q;
        });

        if (exactMatch) {
            addToCart(exactMatch);
            $(this).val('');
            $('#productResults').hide();
            return;
        }

        showSearchResults(q, $('#categoryFilter').val());
    });

    $('#productSearch').on('keydown', function(e){
        if(e.key==='Escape'){ $('#productResults').hide(); $(this).val(''); }
    });

    $(document).on('click', function(e){
        if(!$(e.target).closest('.search-wrap').length) $('#productResults').hide();
    });

    $('#categoryFilter').on('change', function(){
        const q = $('#productSearch').val().trim();
        if(q) showSearchResults(q, $(this).val());
    });

    $('#discountPercent').on('input', recalc);

    $('.pay-btn').on('click', function(){
        $('.pay-btn').removeClass('active'); $(this).addClass('active');
        paymentMethod = $(this).data('method');
        $('#cashSection').toggle(paymentMethod==='CASH');
    });

    $('#amountReceived').on('input', function() {
        hasUserTouchedReceivedAmount = true;
        calcChange();
    });
    $('#btnClearCart').on('click', clearCart);
    $('#btnCompleteSale').on('click', completeSale);
    $('#btnSellAsDebt').on('click', openCustomerSelect);  // NEW
    $('#btnCancel').on('click', cancelOrder);
    $('#btnHold').on('click', ()=>showToast(t('pos_hold'), 'info'));
    $('#btnReturn').on('click', ()=>$('#returnModal').modal('show'));
    $('#btnNewSale').on('click', newSale);
    $('#btnProcessReturn').on('click', processReturn);

    // Customer selection listeners
    $('#btnQuickAddCustomer').on('click', quickAddCustomer);
    $('#customerSearchInput').on('input', filterCustomers);
}

// ═══════════════════════════════════════════════════════
// LOAD DATA
// ═══════════════════════════════════════════════════════
function loadCategories(){
    $.ajax({ url:CATEGORIES_URL, method:'GET',
        success(cats){
            const sel=$('#categoryFilter');
            sel.find('option:not(:first)').remove();
            (cats||[]).forEach(c=>sel.append(`<option value="${c.name}">${c.name}</option>`));
        },
        error(){ console.error('Failed to load categories'); }
    });
}

function loadProducts(){
    console.log('Loading products from:', ITEMS_URL);
    $.ajax({ url:ITEMS_URL, method:'GET',
        success(items){
            console.log('Products loaded:', items);
            allProducts=(items||[]);
            console.log('allProducts set to:', allProducts);
        },
        error(xhr, status, error){
            console.error('Failed to load products:', status, error, xhr.responseText);
            showToast(t('items_load_error'), 'error');
        }
    });
}

function loadCustomers(){
    $.ajax({ url:CUSTOMERS_URL, method:'GET',
        success(customers){
            allCustomers = customers || [];
            renderCustomers(allCustomers);
        },
        error(){ showToast(t('customers_load_error'), 'error'); }
    });
}

// ═══════════════════════════════════════════════════════
// SEARCH RESULTS
// ═══════════════════════════════════════════════════════
function showSearchResults(q, cat){
    console.log('showSearchResults called with:', q, cat);
    console.log('allProducts:', allProducts);
    let res = allProducts;
    const normalizedQ = normalizeUnicodeDigits(q).toLowerCase();

    res = res.filter(p => {
        const description = normalizeUnicodeDigits(p.description || '').toLowerCase();
        const code = normalizeUnicodeDigits(p.code || '').toLowerCase();
        const barcode = normalizeUnicodeDigits(p.barcode || '').toLowerCase();
        return description.includes(normalizedQ) || code.includes(normalizedQ) || barcode.includes(normalizedQ);
    });

    if(cat) res = res.filter(p=>p.category===cat);
    console.log('Filtered results:', res);

    const box=$('#productResults'); box.empty();
    if(!res.length){
        box.html(`<div style="padding:12px;color:#999;text-align:center;">${t('items_no_result')}</div>`);
        box.show();
        return;
    }

    res.slice(0,10).forEach(p=>{
        const qty=p.qtyOnHand||0, min=p.minStockLevel||10;
        const isOut=qty===0, isLow=!isOut&&qty<=min;
        let stk = `<span class="stock-ok">${qty}</span>`;
        if(isOut)  stk=`<span class="stock-out">${t('items_out_stock')}</span>`;
        else if(isLow) stk=`<span class="stock-low">${qty}</span>`;

        const row=$(`
            <div class="prod-result-row" style="display:flex; justify-content:space-between; padding:10px 16px; cursor:pointer; border-bottom:1px solid #f0f0f0;">
                <div>
                    <div style="font-weight:700; font-size:14px;">${p.description}</div>
                    <div style="font-size:12px; color:#888;">${p.code}${p.category?' · '+p.category:''} · ${stk}</div>
                </div>
                <div style="font-weight:800; color:#2E75B6; font-size:14px;">IQD ${fmt(p.unitPrice)}</div>
            </div>
        `);
        if(!isOut){ row.on('click',()=>{ addToCart(p); $('#productSearch').val(''); box.hide(); }); }
        else { row.css('opacity','.5').css('cursor','not-allowed'); }
        box.append(row);
    });
    box.show();
}

// ═══════════════════════════════════════════════════════
// CART
// ═══════════════════════════════════════════════════════
function addToCart(p){
    const ex=cart.find(c=>c.code===p.code);
        if(ex){
        if(ex.qty>=p.qtyOnHand){ showToast(t('pos_max_stock').replace('{max}', p.qtyOnHand), 'warning'); return; }
        ex.qty++;
    } else {
        cart.push({
            code:p.code,
            name:p.description,
            price:p.unitPrice,
            qty:1,
            maxQty:p.qtyOnHand
        });
    }
    renderCart(); recalc();
    playBeep();
}

function updateQty(code, delta){
    const item=cart.find(c=>c.code===code); if(!item) return;
    item.qty+=delta;
    if(item.qty<=0) cart=cart.filter(c=>c.code!==code);
    else if(item.qty>item.maxQty){ item.qty=item.maxQty; showToast(t('pos_max_stock').replace('{max}', item.maxQty), 'warning'); }
    renderCart(); recalc();
}

function removeItem(code){
    cart=cart.filter(c=>c.code!==code);
    renderCart(); recalc();
}

function clearCart(){
    if(!cart.length) return;
    if(!confirm(t('pos_confirm_clear_cart'))) return;
    cart=[]; renderCart(); recalc();
}

function renderCart(){
    const body=$('#cartItems'); body.empty();
    const totalQty=cart.reduce((s,c)=>s+c.qty,0);
    $('#cartCount').text(totalQty + ' ' + t('unit_label'));

    if(!cart.length){
        body.html(`
            <div class="cart-empty">
                ${illus('cart')}
                <p>${t('pos_cart_empty_title')}</p>
                <small>${t('pos_cart_empty_desc')}</small>
            </div>
        `);
        return;
    }

    cart.forEach(item=>{
        const row=$(`
            <div class="cart-row" style="display:grid; grid-template-columns:1fr 90px 120px 120px 40px; padding:10px 16px; align-items:center;">
                <div style="font-weight:700; font-size:14px;">${item.name}</div>
                <div style="text-align:center;">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">
                        <button class="q-btn" data-code="${item.code}" data-delta="-1" style="width:24px; height:24px; border-radius:50%; border:1.5px solid #ccc; background:white; cursor:pointer;">−</button>
                        <span style="min-width:24px; text-align:center; font-weight:800;">${item.qty}</span>
                        <button class="q-btn" data-code="${item.code}" data-delta="1" style="width:24px; height:24px; border-radius:50%; border:1.5px solid #ccc; background:white; cursor:pointer;">+</button>
                    </div>
                </div>
                <div style="text-align:left; font-size:13px;">IQD ${fmt(item.price)}</div>
                <div style="text-align:left; font-weight:800; color:#2E75B6;">IQD ${fmt(item.price*item.qty)}</div>
                <div style="text-align:center;">
                    <button class="rm-btn" data-code="${item.code}" style="background:none; border:none; color:#e74c3c; font-size:18px; cursor:pointer;">✕</button>
                </div>
            </div>
        `);
        row.find('.q-btn').on('click',function(){ updateQty($(this).data('code'),$(this).data('delta')); });
        row.find('.rm-btn').on('click',function(){ removeItem($(this).data('code')); });
        body.append(row);
    });
}

// ═══════════════════════════════════════════════════════
// TOTALS
// ═══════════════════════════════════════════════════════
function recalc(){
    const sub=cart.reduce((t,c)=>t+(c.price*c.qty),0);
    const discPc=parseFloat($('#discountPercent').val())||0;
    const disc=sub*(discPc/100);
    const total=sub-disc;

    $('#subtotal').text('IQD '+fmt(sub));
    $('#discountAmount').text('- IQD '+fmt(disc));
    $('#totalAmount').text('IQD '+fmt(total));

    const amountInput = $('#amountReceived');
    if (paymentMethod === 'CASH' && cart.length && !hasUserTouchedReceivedAmount) {
        const currentValue = parseFloat(amountInput.val()) || 0;
        if (!amountInput.val() || currentValue === 0) {
            amountInput.val(total);
        }
    }

    calcChange();
}

function calcChange(){
    const total=parseFloat($('#totalAmount').text().replace(/[^0-9.]/g,''))||0;
    const received=parseFloat($('#amountReceived').val())||0;
    const change=received-total;
    $('#changeAmount').text('IQD '+fmt(Math.max(0,change)));
}

// ═══════════════════════════════════════════════════════
// COMPLETE SALE (CASH)
// ═══════════════════════════════════════════════════════
function completeSale(){
    if(!cart.length){ showToast(t('pos_cart_empty_title'),'warning'); return; }

    if(paymentMethod==='CASH'){
        const total=parseFloat($('#totalAmount').text().replace(/[^0-9.]/g,''))||0;
        const received=parseFloat($('#amountReceived').val())||0;
        if(received<total){ showToast(t('pos_amount_received_insufficient'),'warning'); return; }
    }

    const sub=cart.reduce((t,c)=>t+(c.price*c.qty),0);
    const discPc=parseFloat($('#discountPercent').val())||0;
    const disc=sub*(discPc/100);
    const total=sub-disc;
    const paid=parseFloat($('#amountReceived').val())||total;
    const change=Math.max(0,paid-total);

    const order={
        customerId: null,  // No customer for cash sales
        status:'COMPLETED',
        paymentType:'CASH',
        paymentMethod:paymentMethod,
        paymentStatus:'PAID',
        discount:disc,
        discountType:'PERCENTAGE',
        tax:0,
        subtotal:sub,
        totalAmount:total,
        amountPaid:paid,
        changeAmount:change,
        processedBy: localStorage.getItem('userId')||null,
        orderDetails:cart.map(c=>({
            itemCode:c.code,
            quantity:c.qty,
            unitPrice:c.price,
            discount:0,
            tax:0,
            subtotal:c.price*c.qty,
            total:c.price*c.qty
        }))
    };

    saveOrder(order, false);  // false = not a debt
}

// ═══════════════════════════════════════════════════════
// SELL AS DEBT (NEW)
// ═══════════════════════════════════════════════════════
function openCustomerSelect(){
    if(!cart.length){ showToast(t('pos_cart_empty_title'),'warning'); return; }

    selectedCustomerForDebt = null;
    loadCustomers();
    $('#customerSelectModal').modal('show');
}

function quickAddCustomer(){
    const name = $('#quickCustomerName').val().trim();
    const phone = $('#quickCustomerPhone').val().trim();

    if(!name){
        showToast(t('item_name_required'), 'warning');
        return;
    }

    const customer = { name, phone, address: '', notes: '' };

    $.ajax({
        url: CUSTOMERS_URL,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(customer),
        success(res){
            showToast(t('pos_customer_added'), 'success');
            $('#quickCustomerName').val('');
            $('#quickCustomerPhone').val('');
            loadCustomers();  // Reload list
        },
        error(){ showToast(t('debts_add_error'), 'error'); }
    });
}

function renderCustomers(customers){
    const list = $('#customersList');
    list.empty();

    if(!customers || customers.length === 0){
        list.html(`
            <div class="text-center text-muted py-4">
                ${illus('people')}
                <p>${t('customers_empty')}</p>
                <small>${t('add') + ' ' + t('debts_customer_name')}</small>
            </div>
        `);
        return;
    }

    customers.forEach(c => {
        const div = $(`
            <div class="customer-item" data-id="${c.id}">
                <div>
                    <div class="customer-name">${c.name}</div>
                    <div class="customer-phone">${c.phone || t('no_phone')}</div>
                </div>
                <div class="customer-debt-badge">${icon('card', 16)}</div>
            </div>
        `);

        div.on('click', function(){
            $('.customer-item').removeClass('selected');
            $(this).addClass('selected');
            selectCustomerForDebt(c);
        });

        list.append(div);
    });
}

function filterCustomers(){
    const search = $('#customerSearchInput').val().toLowerCase();
    if(!search){
        renderCustomers(allCustomers);
        return;
    }

    const filtered = allCustomers.filter(c =>
        c.name.toLowerCase().includes(search) ||
        (c.phone && c.phone.includes(search))
    );

    renderCustomers(filtered);
}

function selectCustomerForDebt(customer){
    selectedCustomerForDebt = customer;

    // Auto-proceed with debt sale
    setTimeout(() => {
        $('#customerSelectModal').modal('hide');
        completeSaleAsDebt();
    }, 300);
}

function completeSaleAsDebt(){
    if(!selectedCustomerForDebt){
        showToast(t('debts_customer_name') + ' ' + t('item_code_required'), 'warning');
        return;
    }

    const sub=cart.reduce((t,c)=>t+(c.price*c.qty),0);
    const discPc=parseFloat($('#discountPercent').val())||0;
    const disc=sub*(discPc/100);
    const total=sub-disc;

    // Create order as DEBT
    const order={
        customerId: selectedCustomerForDebt.id,
        status:'COMPLETED',
        paymentType:'DEBT',
        paymentMethod:'DEBT',
        paymentStatus:'UNPAID',
        discount:disc,
        discountType:'PERCENTAGE',
        tax:0,
        subtotal:sub,
        totalAmount:total,
        amountPaid:0,
        changeAmount:0,
        processedBy: localStorage.getItem('userId')||null,
        orderDetails:cart.map(c=>({
            itemCode:c.code,
            quantity:c.qty,
            unitPrice:c.price,
            discount:0,
            tax:0,
            subtotal:c.price*c.qty,
            total:c.price*c.qty
        }))
    };

    saveOrder(order, true);  // true = is a debt
}

// ═══════════════════════════════════════════════════════
// SAVE ORDER (Common for both cash and debt)
// ═══════════════════════════════════════════════════════
function saveOrder(order, isDebt){
    const $btnComplete = $('#btnCompleteSale');
    const $btnDebt = $('#btnSellAsDebt');

    $btnComplete.prop('disabled', true).html(icon('clock', 18) + ' ' + t('loading'));
    $btnDebt.prop('disabled', true);

    // Ensure buttons are re-enabled even if something goes wrong
    const resetButtons = () => {
        $btnComplete.prop('disabled', false).html(icon('check', 18) + ' ' + t('pos_complete_sale'));
        $btnDebt.prop('disabled', false);
    };

    $.ajax({
        url: ORDERS_URL,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(order),
        timeout: 30000, // 30 second timeout
        success(res){
            try {
                // Update stats
                const ps = parseFloat(localStorage.getItem('todaySales') || 0);
                const po = parseInt(localStorage.getItem('todayOrders') || 0);
                localStorage.setItem('todaySales', ps + order.totalAmount);
                localStorage.setItem('todayOrders', po + 1);
                updateStats();

                if (isDebt) {
                    // Create debt record
                    createDebtRecord(selectedCustomerForDebt.id, res.id, order.totalAmount);
                    showToast(t('pos_debt_recorded_for').replace('{name}', selectedCustomerForDebt.name), 'success');
                } else {
                    // Show receipt for cash
                    showReceipt(res, order);
                }

                // Reset
                newSale();
            } catch (e) {
                console.error('Error in success handler:', e);
                showToast('Error processing response: ' + e.message, 'error');
            } finally {
                resetButtons();
            }
        },
        error(err){
            try {
                console.error('Order save error:', err);
                // Try to show server response message for debugging
                let errorMessage = t('pos_generic_error');
                if (err.responseJSON && err.responseJSON.message) {
                    errorMessage = err.responseJSON.message;
                } else if (err.responseText) {
                    console.error('Response text:', err.responseText);
                    errorMessage = t('pos_generic_error') + ' - ' + (err.statusText || '');
                } else if (err.statusText) {
                    errorMessage = t('pos_generic_error') + ' - ' + err.statusText;
                }
                showToast(errorMessage, 'error');
            } catch (e) {
                console.error('Error in error handler:', e);
                showToast('Error handling failed: ' + e.message, 'error');
            } finally {
                resetButtons();
            }
        }
    });
}

function createDebtRecord(customerId, orderId, amount){
    const debt = {
        customerId: customerId,
        orderId: orderId,
        totalAmount: amount,
        paidAmount: 0,
        remainingAmount: amount,
        status: 'UNPAID',
        debtDate: new Date().toISOString().split('T')[0],
        notes: t('pos_note_default')
    };

    $.ajax({
        url: DEBTS_URL,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(debt),
        success(){ console.log('Debt created successfully'); },
        error(){ console.error('Failed to create debt record'); }
    });
}

function showReceipt(order, data){
    const now=new Date().toLocaleString('en-GB');
    let rows=cart.map(i=>`
        <div style="display:flex; justify-content:space-between; padding:2px 0;">
            <span>${i.name} x${i.qty}</span>
            <span>IQD ${fmt(i.price*i.qty)}</span>
        </div>
    `).join('');

    $('#receiptContent').html(`
        <div style="font-family:monospace; font-size:13px;">
            <div style="text-align:center; margin-bottom:10px;">
                <strong>${t('brand_name')}</strong><br>
                <small>${now}</small><br>
                <small>#${order.orderNumber||'N/A'}</small>
            </div>
            <hr>${rows}<hr>
            <div style="display:flex; justify-content:space-between;"><span>${t('pos_subtotal_label')}</span><span>${$('#subtotal').text()}</span></div>
            ${data.discount>0?`<div style="display:flex; justify-content:space-between;"><span>${t('pos_discount_label')}</span><span>${$('#discountAmount').text()}</span></div>`:''}
            <div style="display:flex; justify-content:space-between; font-weight:700; font-size:15px;"><span>${t('pos_total_label')}</span><span>${$('#totalAmount').text()}</span></div>
            <div style="display:flex; justify-content:space-between;"><span>${t('pos_amount_received')}</span><span>IQD ${fmt(data.amountPaid)}</span></div>
            ${data.changeAmount>0?`<div style="display:flex; justify-content:space-between;"><span>${t('pos_change_label')}</span><span>IQD ${fmt(data.changeAmount)}</span></div>`:''}
            <hr>
            <div style="text-align:center;"><small>${t('thank_you')}</small></div>
        </div>
    `);
    $('#receiptModal').modal('show');
}

function cancelOrder(){
    if(!cart.length) return;
    if(!confirm(t('confirm_cancel'))) return;
    newSale();
}

function processReturn(){
    showToast(t('refund_not_available'), 'info');
    $('#returnModal').modal('hide');
}

function newSale(){
    cart=[];
    selectedCustomerForDebt = null;
    paymentMethod='CASH';
    renderCart(); recalc();
    $('#discountPercent').val(0);
    $('#amountReceived').val('');
    $('#productSearch').val('').focus();
    $('.pay-btn').removeClass('active');
    $('[data-method="CASH"]').addClass('active');
    $('#cashSection').show();
}

function updateStats(){
    const s = parseFloat(localStorage.getItem('todaySales')||0);
    const orderCount = parseInt(localStorage.getItem('todayOrders')||0);
    $('#todaySales').text('IQD ' + fmt(s));
    $('#todayTransactions').text(orderCount);

    const settings = JSON.parse(localStorage.getItem('posSettings') || '{}');
    $('#cashierName').text(settings.cashier || t('settings_cashier_placeholder'));
}

// UTILS
function fmt(n){ return (n||0).toLocaleString('en-US', {minimumFractionDigits:0}); }

function playBeep(){
    try{
        const a=new AudioContext();
        const o=a.createOscillator();
        const g=a.createGain();
        o.connect(g); g.connect(a.destination);
        o.frequency.value=880;
        g.gain.setValueAtTime(0.3,a.currentTime);
        g.gain.exponentialRampToValueAtTime(0.001,a.currentTime+0.08);
        o.start(); o.stop(a.currentTime+0.08);
    }catch(e){}
}

function showToast(msg,type='info') {
    return window.mkToast(msg, type);
}

console.log('POS Controller with Debt Ready!');


