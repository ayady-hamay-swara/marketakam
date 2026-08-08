/**
 * LANGUAGE SYSTEM - Kurdish Default
 */

const translations = {
    ku: {
        // Common
        loading: 'چاوەڕوان بە...',
        save: 'پاشەکەوت',
        cancel: 'هەڵوەشاندنەوە',
        delete: 'سڕینەوە',
        edit: 'دەستکاری',
        add: 'زیادکردن',
        search: 'گەڕان',
        clear: 'پاككردنەوە',

        // Navigation
        nav_home: 'سەرەکی',
        nav_items: 'کاڵاکان',
        nav_employees: 'کارمەندان',
        nav_pos: 'فرۆشتن',
        nav_search: 'قەرزەکان',
        nav_debts: 'قەرزەکان',

        // Brand
        brand_name: 'سیستەمی فرۆشتن',

        // Items
        items_title: 'بەڕێوەبردنی کاڵا',
        items_form_title: 'زانیاری کاڵا',
        items_code: 'کۆدی کاڵا',
        items_name: 'ناوی کاڵا',
        items_category: 'جۆر',
        items_price: 'نرخ',
        items_stock: 'کۆگا',
        items_min_stock: 'کەمترین کۆگا',
        items_barcode: 'بارکۆد',
        items_notes: 'تێبینی',
        items_update: 'نوێکردنەوە',
        items_total: 'کۆی کاڵاکان',
        items_low_stock: 'کۆگای کەم',
        items_out_stock: 'کۆگا نییە',
        items_total_value: 'کۆی نرخ',

        // Debts
        debts_title: 'بەڕێوەبردنی قەرزەکان',
        debts_add: 'زیادکردنی قەرزی نوێ',
        debts_customer_name: 'ناوی کەس',
        debts_phone: 'ژمارە مۆبایل',
        debts_amount: 'بڕی قەرز (IQD)',
        debts_date: 'بەروار',
        debts_notes: 'تێبینی',
        debts_total: 'کۆی قەرز',
        debts_total_paid: 'واردبووی گشتی',
        debts_total_customers: 'ژمارەی قەرزاران',
        debts_status: 'دۆخ',
        debts_status_unpaid: 'قەرز (نەدراوە)',
        debts_status_partial: 'بەشێکی دراوە',
        debts_status_paid: 'تەواو دراوە',
        debts_remaining: 'ماوە',
        debts_paid_amount: 'واردبووی',
        debts_update: 'نوێکردنەوە',
        debts_export: 'دەرهێنان',
        debts_search: 'گەڕان بە ناو یان ژمارە مۆبایل...',
        debts_all_status: 'هەموو دۆخەکان',
        debts_sort_newest: 'نوێترین',
        debts_sort_oldest: 'کۆنترین',
        debts_sort_highest: 'زۆرترین قەرز',
        debts_sort_lowest: 'کەمترین قەرز',
        debts_empty: 'هیچ قەرزێک تۆمار نەکراوە',
        debts_no_result: 'هیچ ئەنجامێک نەدۆزرایەوە',
        debts_confirm_delete: 'دڵنیایت لە سڕینەوەی ئەم قەرزە؟',
        debts_irreversible: 'ئەم کردارە ناگەڕێتەوە!',
        debts_payment_title: 'واردکردنی پارە',
        debts_payment_amount: 'بڕی پارەی وارد (IQD)',
        debts_payment_notes: 'تێبینی',
        debts_confirm: 'دڵنیابوونەوە',

        // Settings
        settings_title: 'ڕێکخستنەکان',
        settings_store: 'ناوی فرۆشگا',
        settings_store_placeholder: 'فرۆشگای من',
        settings_cashier_name: 'ناوی بەکارهێنەر',
        settings_cashier_placeholder: 'بەڕێوەبەر',
        settings_save: 'پاشەکەوت',
        toggle_on: 'چالاک',
        toggle_off: 'ناچالاک',

        // Items page extras
        items_notes_placeholder: 'قەبارە، ڕەنگ، ژمارە زنجیرە...',
        items_new_category_placeholder: 'ناوی جۆری نوێ...',
        items_category_manage_title: 'بەڕێوەبردنی جۆرەکان',
        items_category_placeholder: 'هەڵبژێرە...',
        items_stock_filter_all: 'هەموو کاڵاکان',
        items_stock_ok: 'کۆگا هەیە',

        // Calculator
        calc_title: 'ژمێرەر',

        // Dashboard
        dashboard_welcome: 'بەخێربێیت! 👋',
        dashboard_subtitle: 'سیستەمی بەڕێوەبردنی فرۆشگا',
        dashboard_today_sales: 'فرۆشی ئەمڕۆ',
        dashboard_orders_count: 'ژمارەی وەسڵەکان',
        dashboard_low_stock: 'کاڵای کۆگای کەم',
        dashboard_action_pos_title: 'فرۆشتن',
        dashboard_action_pos_desc: 'دەستپێکردنی فرۆشتنی نوێ',
        dashboard_action_items_title: 'بەڕێوەبردنی کاڵا',
        dashboard_action_items_desc: 'زیادکردن و دەستکاریکردنی کاڵاکان',
        dashboard_action_employees_title: 'کارمەندان',
        dashboard_action_employees_desc: 'بەڕێوەبردنی کارمەندان',
        dashboard_action_search_title: 'گەڕان لە وەسڵەکان',
        dashboard_action_search_desc: 'بینینی وەسڵە پێشووەکان',
        blog_nav: 'بلۆگ',
        blog_title: 'بلۆگی کەسی',
        blog_subtitle: 'بیر و بۆچوون، پرۆژەکان و ئەزموونەکانم',
        blog_empty: 'هیچ بابەتێک هێشتا بڵاو نەکراوەتەوە. بەم زووانە دێتەوە!',

        // POS Checkout
        pos_transactions_label: 'وەسڵەکان',
        pos_cashier_label: 'کاشێر',
        pos_search_placeholder: 'گەڕان بە ناو، کۆد یان بارکۆد...',
        pos_all_categories: 'هەموو جۆرەکان',
        pos_cart_items: 'کاڵاکانی سەبەتە',
        pos_clear_cart: 'پاککردنەوە',
        pos_col_item: 'کاڵا',
        pos_col_qty: 'ژمارە',
        pos_col_unit_price: 'نرخی یەک',
        pos_col_total: 'کۆی گشتی',
        pos_cart_empty_title: 'سەبەتە بەتاڵە',
        pos_cart_empty_desc: 'کاڵایەک بگەڕێ یان سکان بکە',
        pos_subtotal_label: 'کۆی لاوەکی:',
        pos_discount_label: 'داشکاندن:',
        pos_total_label: 'کۆی گشتی:',
        pos_payment_method_title: 'شێوازی پارەدان',
        pos_pay_cash: 'کاش',
        pos_pay_card: 'کارت',
        pos_pay_transfer: 'گواستنەوە',
        pos_amount_received: 'بڕی وەرگیراو',
        pos_change_label: 'پارەی ماوە:',
        pos_complete_sale: 'تەواوکردنی فرۆشتن',
        pos_sell_as_debt: 'فرۆشتن بە قەرز',
        pos_hold: 'ڕاگرتن',
        pos_return: 'گەڕاندنەوە',

        // Login
        login_title: 'چوونەژوورەوە',
        login_subtitle: 'بەخێربێیت بۆ سیستەمی فرۆشتنی Marketakam',
        login_username: 'ناوی بەکارهێنەر',
        login_password: 'تێپەڕەوشە',
        login_remember: 'بیرهێنانەوە',
        login_button: 'چوونەژوورەوە',
        login_username_placeholder: 'ناوی بەکارهێنەر',
        login_password_placeholder: 'تێپەڕەوشە'
    },

    en: {
        // Common
        loading: 'Loading...',
        save: 'Save',
        cancel: 'Cancel',
        delete: 'Delete',
        edit: 'Edit',
        add: 'Add',
        search: 'Search',
        clear: 'Clear',

        // Navigation
        nav_home: 'Home',
        nav_items: 'Items',
        nav_employees: 'Employees',
        nav_pos: 'POS',
        nav_search: 'Debts',
        nav_debts: 'Debts',

        // Brand
        brand_name: 'Sales System',

        // Items
        items_title: 'Inventory Management',
        items_form_title: 'Item Details',
        items_code: 'Item Code',
        items_name: 'Item Name',
        items_category: 'Category',
        items_price: 'Price',
        items_stock: 'Stock',
        items_min_stock: 'Min Stock',
        items_barcode: 'Barcode',
        items_notes: 'Notes',
        items_update: 'Update',
        items_total: 'Total Items',
        items_low_stock: 'Low Stock',
        items_out_stock: 'Out of Stock',
        items_total_value: 'Total Value',

        // Debts
        debts_title: 'Debt Management',
        debts_add: 'Add New Debt',
        debts_customer_name: 'Customer Name',
        debts_phone: 'Phone Number',
        debts_amount: 'Debt Amount (IQD)',
        debts_date: 'Date',
        debts_notes: 'Notes',
        debts_total: 'Total Debt',
        debts_total_paid: 'Total Received',
        debts_total_customers: 'Number of Debtors',
        debts_status: 'Status',
        debts_status_unpaid: 'Unpaid',
        debts_status_partial: 'Partially Paid',
        debts_status_paid: 'Fully Paid',
        debts_remaining: 'Remaining',
        debts_paid_amount: 'Paid',
        debts_update: 'Update',
        debts_export: 'Export',
        debts_search: 'Search by name or phone...',
        debts_all_status: 'All Statuses',
        debts_sort_newest: 'Newest',
        debts_sort_oldest: 'Oldest',
        debts_sort_highest: 'Highest Debt',
        debts_sort_lowest: 'Lowest Debt',
        debts_empty: 'No debts recorded',
        debts_no_result: 'No results found',
        debts_confirm_delete: 'Are you sure you want to delete this debt?',
        debts_irreversible: 'This action cannot be undone!',
        debts_payment_title: 'Record Payment',
        debts_payment_amount: 'Payment Amount (IQD)',
        debts_payment_notes: 'Notes',
        debts_confirm: 'Confirm',

        // Settings
        settings_title: 'Settings',
        settings_store: 'Store Name',
        settings_store_placeholder: 'My Store',
        settings_cashier_name: 'User Name',
        settings_cashier_placeholder: 'Manager',
        settings_save: 'Save',
        toggle_on: 'On',
        toggle_off: 'Off',

        // Items page extras
        items_notes_placeholder: 'Size, color, serial number...',
        items_new_category_placeholder: 'New category name...',
        items_category_manage_title: 'Manage Categories',
        items_category_placeholder: 'Select...',
        items_stock_filter_all: 'All Items',
        items_stock_ok: 'In Stock',

        // Calculator
        calc_title: 'Calculator',

        // Dashboard
        dashboard_welcome: 'Welcome! 👋',
        dashboard_subtitle: 'Store Management System',
        dashboard_today_sales: "Today's Sales",
        dashboard_orders_count: 'Number of Orders',
        dashboard_low_stock: 'Low Stock Items',
        dashboard_action_pos_title: 'Sell',
        dashboard_action_pos_desc: 'Start a new sale',
        dashboard_action_items_title: 'Manage Items',
        dashboard_action_items_desc: 'Add and edit items',
        dashboard_action_employees_title: 'Employees',
        dashboard_action_employees_desc: 'Manage employees',
        dashboard_action_search_title: 'Search Orders',
        dashboard_action_search_desc: 'View previous orders',
        blog_nav: 'Blog',
        blog_title: 'Personal Blog',
        blog_subtitle: 'Thoughts, projects, and experiences',
        blog_empty: 'No posts published yet. Coming soon!',

        // POS Checkout
        pos_transactions_label: 'Transactions',
        pos_cashier_label: 'Cashier',
        pos_search_placeholder: 'Search by name, code or barcode...',
        pos_all_categories: 'All Categories',
        pos_cart_items: 'Cart Items',
        pos_clear_cart: 'Clear',
        pos_col_item: 'Item',
        pos_col_qty: 'Qty',
        pos_col_unit_price: 'Unit Price',
        pos_col_total: 'Total',
        pos_cart_empty_title: 'Cart is empty',
        pos_cart_empty_desc: 'Search or scan an item',
        pos_subtotal_label: 'Subtotal:',
        pos_discount_label: 'Discount:',
        pos_total_label: 'Total:',
        pos_payment_method_title: 'Payment Method',
        pos_pay_cash: 'Cash',
        pos_pay_card: 'Card',
        pos_pay_transfer: 'Transfer',
        pos_amount_received: 'Amount Received',
        pos_change_label: 'Change:',
        pos_complete_sale: 'Complete Sale',
        pos_sell_as_debt: 'Sell as Debt',
        pos_hold: 'Hold',
        pos_return: 'Return',

        // Login
        login_title: 'Sign In',
        login_subtitle: 'Welcome to the Marketakam sales system',
        login_username: 'Username',
        login_password: 'Password',
        login_remember: 'Remember me',
        login_button: 'Sign In',
        login_username_placeholder: 'Username',
        login_password_placeholder: 'Password'
    },

    ar: {
        // Common
        loading: 'جاري التحميل...',
        save: 'حفظ',
        cancel: 'إلغاء',
        delete: 'حذف',
        edit: 'تعديل',
        add: 'إضافة',
        search: 'بحث',
        clear: 'مسح',

        // Navigation
        nav_home: 'الرئيسية',
        nav_items: 'المنتجات',
        nav_employees: 'الموظفون',
        nav_pos: 'المبيعات',
        nav_search: 'الطلبات',
        nav_debts: 'الديون',

        // Brand
        brand_name: 'نظام المبيعات',

        // Items
        items_title: 'إدارة المخزون',
        items_form_title: 'بيانات المنتج',
        items_code: 'كود المنتج',
        items_name: 'اسم المنتج',
        items_category: 'الفئة',
        items_price: 'السعر',
        items_stock: 'المخزون',
        items_min_stock: 'الحد الأدنى',
        items_barcode: 'الباركود',
        items_notes: 'ملاحظات',
        items_update: 'تحديث',
        items_total: 'إجمالي المنتجات',
        items_low_stock: 'مخزون منخفض',
        items_out_stock: 'نفذ المخزون',
        items_total_value: 'القيمة الإجمالية',

        // Debts
        debts_title: 'إدارة الديون',
        debts_add: 'إضافة دين جديد',
        debts_customer_name: 'اسم الشخص',
        debts_phone: 'رقم الهاتف',
        debts_amount: 'مبلغ الدين (IQD)',
        debts_date: 'التاريخ',
        debts_notes: 'ملاحظات',
        debts_total: 'إجمالي الديون',
        debts_total_paid: 'إجمالي المستلم',
        debts_total_customers: 'عدد المدينين',
        debts_status: 'الحالة',
        debts_status_unpaid: 'غير مدفوع',
        debts_status_partial: 'مدفوع جزئياً',
        debts_status_paid: 'مدفوع بالكامل',
        debts_remaining: 'المتبقي',
        debts_paid_amount: 'المدفوع',
        debts_update: 'تحديث',
        debts_export: 'تصدير',
        debts_search: 'البحث بالاسم أو رقم الهاتف...',
        debts_all_status: 'جميع الحالات',
        debts_sort_newest: 'الأحدث',
        debts_sort_oldest: 'الأقدم',
        debts_sort_highest: 'أعلى دين',
        debts_sort_lowest: 'أدنى دين',
        debts_empty: 'لا توجد ديون مسجلة',
        debts_no_result: 'لا توجد نتائج',
        debts_confirm_delete: 'هل أنت متأكد من حذف هذا الدين؟',
        debts_irreversible: 'لا يمكن التراجع عن هذا الإجراء!',
        debts_payment_title: 'تسجيل دفعة',
        debts_payment_amount: 'مبلغ الدفعة (IQD)',
        debts_payment_notes: 'ملاحظات',
        debts_confirm: 'تأكيد',

        // Settings
        settings_title: 'الإعدادات',
        settings_store: 'اسم المتجر',
        settings_store_placeholder: 'متجري',
        settings_cashier_name: 'اسم المستخدم',
        settings_cashier_placeholder: 'المدير',
        settings_save: 'حفظ',
        toggle_on: 'مفعّل',
        toggle_off: 'معطّل',

        // Items page extras
        items_notes_placeholder: 'المقاس، اللون، الرقم التسلسلي...',
        items_new_category_placeholder: 'اسم الفئة الجديدة...',
        items_category_manage_title: 'إدارة الفئات',
        items_category_placeholder: 'اختر...',
        items_stock_filter_all: 'كل المنتجات',
        items_stock_ok: 'متوفر',

        // Calculator
        calc_title: 'الآلة الحاسبة',

        // Dashboard
        dashboard_welcome: 'مرحبًا! 👋',
        dashboard_subtitle: 'نظام إدارة المتجر',
        dashboard_today_sales: 'مبيعات اليوم',
        dashboard_orders_count: 'عدد الطلبات',
        dashboard_low_stock: 'مخزون منخفض',
        dashboard_action_pos_title: 'بيع',
        dashboard_action_pos_desc: 'بدء عملية بيع جديدة',
        dashboard_action_items_title: 'إدارة المنتجات',
        dashboard_action_items_desc: 'إضافة وتعديل المنتجات',
        dashboard_action_employees_title: 'الموظفون',
        dashboard_action_employees_desc: 'إدارة الموظفين',
        dashboard_action_search_title: 'البحث في الطلبات',
        dashboard_action_search_desc: 'عرض الطلبات السابقة',
        blog_nav: 'المدونة',
        blog_title: 'المدونة الشخصية',
        blog_subtitle: 'أفكار ومشاريع وتجارب',
        blog_empty: 'لا توجد مقالات منشورة بعد. قريبًا!',

        // POS Checkout
        pos_transactions_label: 'الطلبات',
        pos_cashier_label: 'الكاشير',
        pos_search_placeholder: 'البحث بالاسم أو الكود أو الباركود...',
        pos_all_categories: 'جميع الفئات',
        pos_cart_items: 'منتجات السلة',
        pos_clear_cart: 'تفريغ',
        pos_col_item: 'المنتج',
        pos_col_qty: 'الكمية',
        pos_col_unit_price: 'سعر الوحدة',
        pos_col_total: 'الإجمالي',
        pos_cart_empty_title: 'السلة فارغة',
        pos_cart_empty_desc: 'ابحث عن منتج أو امسح الباركود',
        pos_subtotal_label: 'المجموع الفرعي:',
        pos_discount_label: 'الخصم:',
        pos_total_label: 'الإجمالي:',
        pos_payment_method_title: 'طريقة الدفع',
        pos_pay_cash: 'نقدي',
        pos_pay_card: 'بطاقة',
        pos_pay_transfer: 'تحويل',
        pos_amount_received: 'المبلغ المستلم',
        pos_change_label: 'المبلغ المتبقي:',
        pos_complete_sale: 'إتمام البيع',
        pos_sell_as_debt: 'بيع بالدين',
        pos_hold: 'تعليق',
        pos_return: 'إرجاع',

        // Login
        login_title: 'تسجيل الدخول',
        login_subtitle: 'مرحبًا بكم في نظام Marketakam للمبيعات',
        login_username: 'اسم المستخدم',
        login_password: 'كلمة المرور',
        login_remember: 'تذكرني',
        login_button: 'تسجيل الدخول',
        login_username_placeholder: 'اسم المستخدم',
        login_password_placeholder: 'كلمة المرور'
    }
};

// KURDISH IS DEFAULT
let currentLang = localStorage.getItem('posLang') || 'ku';

function t(key) {
    return translations[currentLang]?.[key] || translations['ku'][key] || key;
}

function setLanguage(lang) {
    if (!translations[lang]) return;
    currentLang = lang;
    localStorage.setItem('posLang', lang);
    document.documentElement.dir = (lang === 'ar' || lang === 'ku') ? 'rtl' : 'ltr';
    document.documentElement.lang = lang;
    translatePage();
    window.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang } }));
}

function translatePage() {
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        const translation = t(key);
        if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
            if (el.type === 'button' || el.type === 'submit') el.value = translation;
            else el.placeholder = translation;
        } else {
            el.textContent = translation;
        }
    });

    // Update language label in navbar
    const labels = { ku: 'کوردی', en: 'English', ar: 'العربية' };
    const labelEl = document.getElementById('currentLangLabel');
    if (labelEl) labelEl.textContent = labels[currentLang] || 'کوردی';
}

// Auto-translate on load
document.addEventListener('DOMContentLoaded', () => {
    const savedLang = localStorage.getItem('posLang') || 'ku';
    setLanguage(savedLang);
});
