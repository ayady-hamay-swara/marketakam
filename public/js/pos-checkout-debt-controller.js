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

// ═══════════════════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════════════════
$(document).ready(function(){
    loadCategories();
    loadProducts();
    setupPosListeners();
    updateStats();
});

function setupPosListeners() {
    $('#productSearch').on('input', function(){
        const q = $(this).val().trim();
        if(q.length<1){ $('#productResults').hide(); return; }
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

    $('#amountReceived').on('input', calcChange);
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
    $.ajax({ url:ITEMS_URL, method:'GET',
        success(items){ allProducts=(items||[]); },
        error(){ showToast(t('items_load_error'), 'error'); }
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
    let res = allProducts;
    const ql = q.toLowerCase();
    res = res.filter(p=>p.description.toLowerCase().includes(ql)||p.code.toLowerCase().includes(ql)||(p.barcode&&p.barcode.includes(q)));
    if(cat) res = res.filter(p=>p.category===cat);

    const box=$('#productResults'); box.empty();
    if(!res.length){
        box.html(`<div style="padding:12px;color:#999;text-align:center;">${t('items_no_result')}</div>`);
        box.show();
        return;
    }

    res.slice(0,10).forEach(p=>{
        const qty=p.qtyOnHand||0, min=p.minStockLevel||10;
        const isOut=qty===0, isLow=!isOut&&qty<=min;
        let stk = `<span style="color:#27ae60;">✅ ${qty}</span>`;
        if(isOut)  stk=`<span style="color:#e74c3c;">❌ ${t('items_out_stock')}</span>`;
        else if(isLow) stk=`<span style="color:#f39c12;">⚠️ ${qty}</span>`;

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
                <div style="font-size:48px;">🛒</div>
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
                <div style="font-size:48px;">👥</div>
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
                <div class="customer-debt-badge">💳</div>
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
    $('#btnCompleteSale, #btnSellAsDebt').prop('disabled',true).text(`⏳ ${t('loading')}`);

    $.ajax({
        url: ORDERS_URL,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(order),
        success(res){
            // Update stats
            const ps=parseFloat(localStorage.getItem('todaySales')||0);
            const po=parseInt(localStorage.getItem('todayOrders')||0);
            localStorage.setItem('todaySales', ps + order.totalAmount);
            localStorage.setItem('todayOrders', po + 1);
            updateStats();

                if(isDebt){
                // Create debt record
                createDebtRecord(selectedCustomerForDebt.id, res.id, order.totalAmount);
                showToast(t('pos_debt_recorded_for').replace('{name}', selectedCustomerForDebt.name), 'success');
            } else {
                // Show receipt for cash
                showReceipt(res, order);
            }

            // Reset
            newSale();
            $('#btnCompleteSale, #btnSellAsDebt').prop('disabled',false).text(`✅ ${t('pos_complete_sale')}`);
        },
        error(err){
            console.error(err);
            showToast(t('pos_generic_error'), 'error');
            $('#btnCompleteSale, #btnSellAsDebt').prop('disabled',false).text(`✅ ${t('pos_complete_sale')}`);
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
                <strong>🏪 ${t('brand_name')}</strong><br>
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
    const t = parseInt(localStorage.getItem('todayOrders')||0);
    $('#todaySales').text('IQD ' + fmt(s));
    $('#todayTransactions').text(t);

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

function showToast(msg,type='info'){
    const c={success:'#27ae60',error:'#e74c3c',warning:'#f39c12',info:'#3498db'};
    const ic={success:'✅',error:'❌',warning:'⚠️',info:'ℹ️'};
    const t=$(`<div style="position:fixed;bottom:20px;right:20px;z-index:9999;background:${c[type]};color:white;padding:12px 20px;border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,.3);font-weight:600;font-size:14px;">${ic[type]} ${msg}</div>`);
    $('body').append(t); setTimeout(()=>t.fadeOut(300,()=>t.remove()),2500);
}

console.log('POS Controller with Debt Ready!');
