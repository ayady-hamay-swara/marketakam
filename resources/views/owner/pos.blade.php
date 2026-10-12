<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Marketakam</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owner-pos.css') }}">
</head>
<body class="ow-pos">

<div class="pos-shell">

    {{-- ═════════ PRODUCTS ═════════ --}}
    <section class="pos-products">
        <div class="pos-top">
            {{-- The only way out: back to the owner dashboard --}}
            <a href="{{ url('/owner/dashboard') }}" class="pos-exit" id="posExit">
                @include('owner.partials.icon', ['name' => 'arrow-left', 'size' => 18])
                <span data-i18n="owner_exit_pos">گەڕانەوە بۆ داشبۆرد</span>
            </a>
            <div class="pos-search">
                @include('owner.partials.icon', ['name' => 'search', 'size' => 18])
                <input type="text" class="form-control" id="posSearch" data-i18n="pos_search_placeholder" placeholder="گەڕان بە ناو، کۆد یان بارکۆد..." autofocus>
            </div>
        </div>

        <div class="pos-cats" id="posCats">
            <button type="button" class="on" data-cat="all" data-i18n="pos_all_categories">هەموو جۆرەکان</button>
            <button type="button" data-cat="drinks" data-i18n="owner_cat_drinks">خواردنەوە</button>
            <button type="button" data-cat="snacks" data-i18n="owner_cat_snacks">خواردنی سووک</button>
            <button type="button" data-cat="household" data-i18n="owner_cat_household">کەلوپەلی ماڵ</button>
            <button type="button" data-cat="hygiene" data-i18n="owner_cat_hygiene">پاکوخاوێنی</button>
        </div>

        <div class="pos-grid" id="posGrid">
            @php
            $products = [
                [1,'ئاوی خواردنەوە 0.5L','🥤',250,'drinks'],
                [2,'پێپسی 330ml','🥫',500,'drinks'],
                [3,'شیری تازە 1L','🥛',1750,'drinks'],
                [4,'ئاوی پرتەقاڵ','🍊',1000,'drinks'],
                [5,'پەتاتە چیپس','🥔',750,'snacks'],
                [6,'بسکویتی شیرین','🍪',500,'snacks'],
                [7,'شۆکۆلاتە','🍫',1250,'snacks'],
                [8,'نانی ڕۆژانە','🥖',500,'snacks'],
                [9,'شلەی ئاو (شوشتن)','🧴',3500,'household'],
                [10,'دەستمال','🧻',1500,'household'],
                [11,'سابوونی دەست','🧼',1250,'hygiene'],
                [12,'شامپۆ','🧴',4500,'hygiene'],
            ];
            @endphp
            @foreach($products as $p)
            <button type="button" class="pp" data-id="{{ $p[0] }}" data-name="{{ $p[1] }}" data-price="{{ $p[3] }}" data-cat="{{ $p[4] }}">
                <span class="pp-ico">{{ $p[2] }}</span>
                <span class="pp-name">{{ $p[1] }}</span>
                <span class="pp-price">IQD {{ number_format($p[3]) }}</span>
            </button>
            @endforeach
        </div>
    </section>

    {{-- ═════════ CART ═════════ --}}
    <aside class="pos-cart">
        <div class="pc-head">
            <h2>@include('owner.partials.icon', ['name' => 'cart', 'size' => 20])<span data-i18n="pos_cart_items">کاڵاکانی سەبەتە</span> <span class="pc-count" id="cartCount">0</span></h2>
            <button type="button" class="pc-clear" id="btnClear">@include('owner.partials.icon', ['name' => 'trash', 'size' => 15])<span data-i18n="pos_clear_cart">پاککردنەوە</span></button>
        </div>

        <div class="pc-body">
            <div class="pc-empty" id="cartEmpty">
                <div class="big">🛒</div>
                <b data-i18n="pos_cart_empty_title">سەبەتە بەتاڵە</b>
                <small data-i18n="pos_cart_empty_desc">کاڵایەک بگەڕێ یان سکان بکە</small>
            </div>
            <div id="cartList"></div>
        </div>

        <div class="pc-foot">
            <div class="pc-totals">
                <div>
                    <span class="lbl" data-i18n="pos_subtotal_label">کۆی لاوەکی:</span>
                    <span class="v" id="subtotal">IQD 0</span>
                </div>
                <div class="disc">
                    <span class="lbl" data-i18n="pos_discount_label">داشکاندن:</span>
                    <div class="row-in"><input type="number" id="discount" value="0" min="0" max="100"><span>%</span><span class="v" id="discAmt">- IQD 0</span></div>
                </div>
                <div class="grand">
                    <span class="lbl" data-i18n="pos_total_label">کۆی گشتی:</span>
                    <span class="v" id="total">IQD 0</span>
                </div>
            </div>

            <div class="pc-pay" id="payMethods">
                <button type="button" class="on" data-m="cash">@include('owner.partials.icon', ['name' => 'dollar', 'size' => 18])<span data-i18n="pos_pay_cash">کاش</span></button>
                <button type="button" data-m="card">@include('owner.partials.icon', ['name' => 'card', 'size' => 18])<span data-i18n="pos_pay_card">کارت</span></button>
                <button type="button" data-m="transfer">@include('owner.partials.icon', ['name' => 'swap', 'size' => 18])<span data-i18n="pos_pay_transfer">گواستنەوە</span></button>
            </div>

            <div class="pc-actions">
                <button type="button" class="pc-complete" id="btnComplete" disabled>@include('owner.partials.icon', ['name' => 'check', 'size' => 22])<span data-i18n="pos_complete_sale">تەواوکردنی فرۆشتن</span></button>
                <button type="button" class="pc-secondary" id="btnDebt" disabled>@include('owner.partials.icon', ['name' => 'card', 'size' => 17])<span data-i18n="pos_sell_as_debt">فرۆشتن بە قەرز</span></button>
                <button type="button" class="pc-secondary" id="btnHold" disabled>@include('owner.partials.icon', ['name' => 'pause', 'size' => 17])<span data-i18n="pos_hold">ڕاگرتن</span></button>
            </div>
        </div>
    </aside>
</div>

<div class="pos-toast" id="posToast" data-i18n="owner_sale_done">فرۆشتنەکە تەواو بوو</div>

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/languages.js') }}"></script>
<script>
/* Static prototype: cart lives in memory only. Backend wiring comes later. */
(function () {
    const $ = (id) => document.getElementById(id);
    const cart = {};
    const fmt = (n) => 'IQD ' + Math.round(n).toLocaleString('en-US');

    function render() {
        const ids = Object.keys(cart);
        $('cartEmpty').style.display = ids.length ? 'none' : 'flex';
        $('cartList').innerHTML = ids.map(id => {
            const it = cart[id];
            return `<div class="pc-item" data-id="${id}">
                <div class="pc-name">${it.name}<small>${fmt(it.price)}</small></div>
                <div class="pc-qty"><button type="button" data-act="dec">−</button><span>${it.qty}</span><button type="button" data-act="inc">+</button></div>
                <div class="pc-line">${fmt(it.price * it.qty)}</div></div>`;
        }).join('');

        const sub = ids.reduce((s, id) => s + cart[id].price * cart[id].qty, 0);
        const pct = Math.min(100, Math.max(0, parseFloat($('discount').value) || 0));
        const disc = sub * pct / 100;
        $('subtotal').textContent = fmt(sub);
        $('discAmt').textContent = '- ' + fmt(disc);
        $('total').textContent = fmt(sub - disc);
        $('cartCount').textContent = ids.reduce((s, id) => s + cart[id].qty, 0);
        ['btnComplete', 'btnDebt', 'btnHold'].forEach(b => $(b).disabled = !ids.length);
    }

    document.querySelectorAll('.pp').forEach(p => p.addEventListener('click', () => {
        const id = p.dataset.id;
        if (cart[id]) cart[id].qty++;
        else cart[id] = { name: p.dataset.name, price: +p.dataset.price, qty: 1 };
        render();
    }));

    $('cartList').addEventListener('click', (e) => {
        const b = e.target.closest('button[data-act]'); if (!b) return;
        const id = b.closest('.pc-item').dataset.id;
        cart[id].qty += b.dataset.act === 'inc' ? 1 : -1;
        if (cart[id].qty <= 0) delete cart[id];
        render();
    });

    const reset = () => { Object.keys(cart).forEach(k => delete cart[k]); $('discount').value = 0; render(); };
    $('btnClear').addEventListener('click', reset);
    $('btnHold').addEventListener('click', reset);
    $('btnDebt').addEventListener('click', reset);
    $('btnComplete').addEventListener('click', () => {
        reset();
        const t = $('posToast'); t.classList.add('show'); setTimeout(() => t.classList.remove('show'), 1800);
    });
    $('discount').addEventListener('input', render);

    /* payment method */
    $('payMethods').addEventListener('click', (e) => {
        const b = e.target.closest('button'); if (!b) return;
        $('payMethods').querySelectorAll('button').forEach(x => x.classList.toggle('on', x === b));
    });

    /* category chips + search */
    let cat = 'all';
    const filter = () => {
        const q = $('posSearch').value.trim().toLowerCase();
        document.querySelectorAll('.pp').forEach(p => {
            const okCat = cat === 'all' || p.dataset.cat === cat;
            const okQ = !q || p.dataset.name.toLowerCase().includes(q);
            p.classList.toggle('hide', !(okCat && okQ));
        });
    };
    $('posCats').addEventListener('click', (e) => {
        const b = e.target.closest('button'); if (!b) return;
        cat = b.dataset.cat;
        $('posCats').querySelectorAll('button').forEach(x => x.classList.toggle('on', x === b));
        filter();
    });
    $('posSearch').addEventListener('input', filter);

    render();
})();
</script>
</body>
</html>
