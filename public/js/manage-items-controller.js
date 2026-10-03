/**
 * Inventory management for cashier items page
 */

const BASE_URL = '/api/items';
const CURRENCY_STEP = 250;
let currentItemCode = null;
let allItems = [];

$(document).ready(function () {
    setupEventListeners();
    loadAllItems();
    generateItemCode();
});

function setupEventListeners() {
    $('#btnGenerateCode').on('click', generateItemCode);
    $('#btnFloorPrice').on('click', floorPriceTo250);
    $('#btnSave').on('click', saveItem);
    $('#btnUpdate').on('click', updateItem);
    $('#btnDelete').on('click', deleteItem);
    $('#btnClear').on('click', clearForm);

    if ($('#btnOpenSettingsPage').length) {
        $('#btnOpenSettingsPage').on('click', function () {
            window.location.href = '/settings';
        });
    }

    $('#txtSearch').on('input', searchItems);
    $('#filterCategory').on('change', searchItems);
    $('#filterStock').on('change', searchItems);
}

function loadAllItems() {
    $.ajax({
        url: BASE_URL,
        method: 'GET',
        success: function (items) {
            allItems = items || [];
            displayItems(allItems);
            populateCategoryOptions();
            updateStatistics();
        },
        error: function (error) {
            console.error('Error loading items:', error);
            showNotification(t('items_load_error'), 'error');
            allItems = [];
            displayItems([]);
            updateStatistics();
        }
    });
}

function displayItems(items) {
    const tbody = $('#itemsTableBody');
    tbody.empty();

    if (!items.length) {
        tbody.append(`
            <tr>
                <td colspan="8" class="text-center text-muted py-4">${t('items_no_result')}</td>
            </tr>
        `);
        return;
    }

    items.forEach(function (item) {
        const row = $(`
            <tr>
                <td><strong>${item.code}</strong></td>
                <td>${item.name || item.description || ''}</td>
                <td>${item.category || '-'}</td>
                <td>IQD ${Number(item.price || item.unitPrice || 0).toLocaleString('en-US')}</td>
                <td>${item.stock || item.qtyOnHand || 0}</td>
                <td>${item.barcode || '-'}</td>
                <td>${item.notes || '-'}</td>
                <td>
                    <button class="btn btn-sm btn-primary" type="button" data-code="${item.code}" data-action="edit">${t('edit')}</button>
                    <button class="btn btn-sm btn-danger ml-2" type="button" data-code="${item.code}" data-action="delete">${t('delete')}</button>
                </td>
            </tr>
        `);

        row.find('[data-action="edit"]').on('click', function () {
            selectItem(item);
        });

        row.find('[data-action="delete"]').on('click', function () {
            currentItemCode = item.code;
            deleteItem();
        });

        tbody.append(row);
    });
}

function populateCategoryOptions() {
    const categories = [...new Set(allItems.map(item => item.category).filter(Boolean))];
    const select = $('#filterCategory');
    const current = select.val();

    select.empty();
    select.append(`<option value="">${t('pos_all_categories')}</option>`);

    categories.forEach(function (category) {
        select.append(`<option value="${category}">${category}</option>`);
    });

    if (current) {
        select.val(current);
    }
}

function updateStatistics() {
    const activeItems = allItems.filter(item => item.active || item.is_active);
    const lowStock = allItems.filter(item => {
        const stock = Number(item.stock ?? item.qtyOnHand ?? 0);
        const min = Number(item.min_stock ?? item.minStockLevel ?? 10);
        return (item.active || item.is_active) && stock > 0 && stock <= min;
    }).length;
    const outOfStock = allItems.filter(item => {
        const stock = Number(item.stock ?? item.qtyOnHand ?? 0);
        return (item.active || item.is_active) && stock === 0;
    }).length;
    const totalValue = allItems.reduce((sum, item) => {
        const active = item.active || item.is_active;
        if (!active) return sum;
        const price = Number(item.price ?? item.unitPrice ?? 0);
        const stock = Number(item.stock ?? item.qtyOnHand ?? 0);
        return sum + (price * stock);
    }, 0);

    $('#totalItems').text(activeItems.length);
    $('#lowStockItems').text(lowStock);
    $('#outOfStockItems').text(outOfStock);
    $('#totalValue').text('IQD ' + totalValue.toLocaleString('en-US'));
}

function floorPriceTo250() {
    const rawValue = Number($('#txtPrice').val());

    if (!Number.isFinite(rawValue) || rawValue < 0) {
        $('#txtPrice').val(0);
        return;
    }

    const floored = Math.floor(rawValue / CURRENCY_STEP) * CURRENCY_STEP;
    $('#txtPrice').val(floored);
    showNotification(t('items_price_floored').replace('{amount}', floored), 'info');
}

function normalizePriceValue(value) {
    if (!Number.isFinite(Number(value))) {
        return 0;
    }

    return Math.floor(Number(value) / CURRENCY_STEP) * CURRENCY_STEP;
}

function getFormData() {
    const price = normalizePriceValue($('#txtPrice').val());
    $('#txtPrice').val(price);

    return {
        code: $('#txtCode').val().trim(),
        name: $('#txtName').val().trim(),
        category: $('#txtCategory').val() || null,
        price: price,
        stock: Number($('#txtStock').val()),
        min_stock: Number($('#txtMinStock').val()) || 0,
        barcode: $('#txtBarcode').val() || null,
        notes: $('#txtNotes').val() || null,
        is_active: true,
    };
}

function validateForm() {
    const code = $('#txtCode').val().trim();
    const name = $('#txtName').val().trim();
    const price = Number($('#txtPrice').val());
    const stock = Number($('#txtStock').val());

    if (!code) {
        showNotification(t('item_code_required'), 'warning');
        $('#txtCode').focus();
        return false;
    }

    if (!name) {
        showNotification(t('item_name_required'), 'warning');
        $('#txtName').focus();
        return false;
    }

    if (!Number.isFinite(price) || price <= 0) {
        showNotification(t('item_invalid_price'), 'warning');
        $('#txtPrice').focus();
        return false;
    }

    if (!Number.isFinite(stock) || stock < 0) {
        showNotification(t('item_invalid_stock'), 'warning');
        $('#txtStock').focus();
        return false;
    }

    return true;
}

function saveItem() {
    if (!validateForm()) return;

    const payload = getFormData();

    $.ajax({
        url: BASE_URL,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function () {
            showNotification(t('item_saved'), 'success');
            clearForm();
            loadAllItems();
        },
        error: function (error) {
            console.error('Save error:', error);
            const message = error.responseJSON?.message || t('item_save_failed');
            showNotification(message, 'error');
        }
    });
}

function updateItem() {
    if (!currentItemCode) {
        showNotification(t('items_no_result'), 'warning');
        return;
    }

    if (!validateForm()) return;

    const payload = getFormData();

    $.ajax({
        url: `${BASE_URL}/${currentItemCode}`,
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function () {
            showNotification(t('item_updated'), 'success');
            clearForm();
            loadAllItems();
        },
        error: function (error) {
            console.error('Update error:', error);
            const message = error.responseJSON?.message || t('item_update_failed');
            showNotification(message, 'error');
        }
    });
}

function deleteItem() {
    if (!currentItemCode) {
        showNotification(t('item_none_selected'), 'warning');
        return;
    }

    if (!confirm(t('confirm_delete_item').replace('{code}', currentItemCode))) {
        return;
    }

    $.ajax({
        url: `${BASE_URL}/${currentItemCode}`,
        method: 'DELETE',
        success: function () {
            showNotification(t('item_deleted'), 'success');
            clearForm();
            loadAllItems();
        },
        error: function (error) {
            console.error('Delete error:', error);
            showNotification(t('item_delete_failed'), 'error');
        }
    });
}

function selectItem(item) {
    currentItemCode = item.code;

    $('#txtCode').val(item.code);
    $('#txtName').val(item.name || item.description || '');
    $('#txtCategory').val(item.category || '');
    $('#txtPrice').val(item.price ?? item.unitPrice ?? 0);
    $('#txtStock').val(item.stock ?? item.qtyOnHand ?? 0);
    $('#txtMinStock').val(item.min_stock ?? item.minStockLevel ?? 0);
    $('#txtBarcode').val(item.barcode || '');
    $('#txtNotes').val(item.notes || '');

    $('#btnSave').hide();
    $('#btnUpdate').show();
    $('#btnDelete').show();
}

function clearForm() {
    currentItemCode = null;
    $('#itemForm')[0].reset();
    $('#btnSave').show();
    $('#btnUpdate').hide();
    $('#btnDelete').hide();
    generateItemCode();
}

function generateItemCode() {
    $.ajax({
        url: `${BASE_URL}/next-code`,
        method: 'GET',
        success: function (code) {
            $('#txtCode').val(code);
        },
        error: function () {
            const next = allItems.length ? allItems.reduce((max, item) => {
                const num = Number(String(item.code || '').replace(/\D/g, '')) || 0;
                return Math.max(max, num);
            }, 0) + 1 : 1;
            $('#txtCode').val(`P-${String(next).padStart(4, '0')}`);
        }
    });
}

function searchItems() {
    const search = $('#txtSearch').val().toLowerCase().trim();
    const category = $('#filterCategory').val();
    const stockFilter = $('#filterStock').val();

    let filtered = [...allItems];

    if (search) {
        filtered = filtered.filter(item => {
            const name = (item.name || item.description || '').toLowerCase();
            const code = (item.code || '').toLowerCase();
            const barcode = (item.barcode || '').toLowerCase();
            return name.includes(search) || code.includes(search) || barcode.includes(search);
        });
    }

    if (category) {
        filtered = filtered.filter(item => (item.category || '') === category);
    }

    if (stockFilter === 'low') {
        filtered = filtered.filter(item => {
            const stock = Number(item.stock ?? item.qtyOnHand ?? 0);
            const min = Number(item.min_stock ?? item.minStockLevel ?? 10);
            return stock > 0 && stock <= min;
        });
    } else if (stockFilter === 'out') {
        filtered = filtered.filter(item => Number(item.stock ?? item.qtyOnHand ?? 0) === 0);
    } else if (stockFilter === 'ok') {
        filtered = filtered.filter(item => Number(item.stock ?? item.qtyOnHand ?? 0) > 0);
    }

    displayItems(filtered);
}

function showNotification(message, type = 'info') {
    const alertClass = {
        success: 'alert-success',
        error: 'alert-danger',
        warning: 'alert-warning',
        info: 'alert-info'
    }[type] || 'alert-info';

    const alert = $(`
        <div class="alert ${alertClass} alert-dismissible fade show" style="position: fixed; top: 70px; right: 20px; z-index: 9999; min-width: 260px;">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `);

    $('body').append(alert);
    setTimeout(() => alert.alert('close'), 3000);
}

    // Filter by category
    if (category) {
        filtered = filtered.filter(item => item.category === category);
    }

    // Filter by stock level
    if (stockFilter === 'in-stock') {
        filtered = filtered.filter(item => item.qtyOnHand > (item.minStockLevel || 10));
    } else if (stockFilter === 'low-stock') {
        filtered = filtered.filter(item =>
            item.qtyOnHand > 0 && item.qtyOnHand <= (item.minStockLevel || 10)
        );
    } else if (stockFilter === 'out-of-stock') {
        filtered = filtered.filter(item => item.qtyOnHand === 0);
    }

    // Filter by status
    if (status) {
        const isActive = status === 'true';
        filtered = filtered.filter(item => item.active === isActive);
    }

    displayItems(filtered);

function resetFilters() {
    $('#txtSearch').val('');
    $('#filterCategory').val('');
    $('#filterStock').val('');
    $('#filterStatus').val('');
    displayItems(allItems);
}

// ============================================================================
// STOCK ADJUSTMENT
// ============================================================================

function openStockAdjustment(item) {
    currentItemCode = item.code;
    $('#currentStock').val(`${item.description} - Current: ${item.qtyOnHand}`);
    $('#adjustmentType').val('add');
    $('#adjustmentQty').val('');
    $('#adjustmentReason').val('purchase');
    $('#adjustmentNotes').val('');
    $('#newStock').text(item.qtyOnHand);

    $('#stockAdjustModal').modal('show');
}

function calculateNewStock() {
    const item = allItems.find(i => i.code === currentItemCode);
    if (!item) return;

    const currentStock = item.qtyOnHand;
    const type = $('#adjustmentType').val();
    const qty = parseInt($('#adjustmentQty').val()) || 0;

    let newStock = currentStock;

    switch(type) {
        case 'add':
            newStock = currentStock + qty;
            break;
        case 'remove':
            newStock = currentStock - qty;
            break;
        case 'set':
            newStock = qty;
            break;
    }

    newStock = Math.max(0, newStock);
    $('#newStock').text(newStock);
}

function confirmStockAdjustment() {
    const item = allItems.find(i => i.code === currentItemCode);
    if (!item) return;

    const newStock = parseInt($('#newStock').text());
    const reason = $('#adjustmentReason').val();
    const notes = $('#adjustmentNotes').val();

    // Update item stock
    item.qtyOnHand = newStock;

    $.ajax({
        url: `${BASE_URL}/${currentItemCode}`,
        method: "PUT",
        contentType: "application/json",
        data: JSON.stringify(item),
        success: function() {
            showNotification(`Stock adjusted successfully! New stock: ${newStock}`, "success");
            $('#stockAdjustModal').modal('hide');
            loadAllItems();

            // Log adjustment (in production, this would save to audit log)
            console.log(`Stock Adjustment: ${currentItemCode}, Reason: ${reason}, Notes: ${notes}`);
        },
        error: function(error) {
            console.error("Error adjusting stock:", error);
            showNotification("Error adjusting stock", "error");
        }
    });
}

// ============================================================================
// BULK IMPORT/EXPORT
// ============================================================================

function exportToCSV() {
    const headers = ['Code', 'Description', 'Category', 'Price', 'Stock', 'MinStock', 'Barcode', 'Active'];
    const rows = allItems.map(item => [
        item.code,
        item.description,
        item.category || '',
        item.unitPrice,
        item.qtyOnHand,
        item.minStockLevel || 10,
        item.barcode || '',
        item.active ? 'Yes' : 'No'
    ]);

    let csv = headers.join(',') + '\n';
    rows.forEach(row => {
        csv += row.map(cell => `"${cell}"`).join(',') + '\n';
    });

    downloadCSV(csv, 'items_export.csv');
    showNotification("Items exported successfully!", "success");
}

function downloadCSVTemplate() {
    const headers = ['Code', 'Description', 'Category', 'Price', 'Stock', 'MinStock', 'Barcode', 'Active'];
    const sample = [
        'I001', 'Sample Product', 'Electronics', '1000.00', '50', '10', '123456789', 'Yes'
    ];

    let csv = headers.join(',') + '\n';
    csv += sample.map(cell => `"${cell}"`).join(',') + '\n';

    downloadCSV(csv, 'items_template.csv');
    showNotification("Template downloaded!", "success");
}

function downloadCSV(content, filename) {
    const blob = new Blob([content], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    window.URL.revokeObjectURL(url);
}

function previewCSVImport(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const csv = e.target.result;
        const lines = csv.split('\n');
        const headers = lines[0].split(',');

        let preview = '<thead><tr>';
        headers.forEach(h => preview += `<th>${h}</th>`);
        preview += '</tr></thead><tbody>';

        for (let i = 1; i < Math.min(6, lines.length); i++) {
            const cells = lines[i].split(',');
            preview += '<tr>';
            cells.forEach(c => preview += `<td>${c.replace(/"/g, '')}</td>`);
            preview += '</tr>';
        }
        preview += '</tbody>';

        $('#previewTable').html(preview);
        $('#importPreview').show();
    };
    reader.readAsText(file);
}

function confirmBulkImport() {
    showNotification("Bulk import functionality coming soon!", "info");
    // In production, this would parse CSV and create multiple items
}

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================

function formatCurrency(amount) {
    return amount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function showNotification(message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';

    const alert = $(`
        <div class="alert ${alertClass} alert-dismissible fade show"
             style="position: fixed; top: 70px; right: 20px; z-index: 9999; min-width: 300px;">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);

    $('body').append(alert);

    setTimeout(() => {
        alert.alert('close');
    }, 3000);
}

console.log("Items Management Controller Loaded!");
