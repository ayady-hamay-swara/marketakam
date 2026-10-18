/**
 * DEBTS MANAGEMENT CONTROLLER
 * Track customer debts and payments
 */

let debts = [];
let currentDebtId = null;

// Configure CSRF header for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    }
});

function normalizeDebt(debt = {}) {
    const totalAmount = Number(debt.total_amount ?? debt.totalAmount ?? 0);
    const paidAmount = Number(debt.paid_amount ?? debt.paidAmount ?? 0);
    const remainingAmount = Number(debt.remaining_amount ?? debt.remainingAmount ?? Math.max(0, totalAmount - paidAmount));
    const resolvedStatus = String(debt.status || (remainingAmount <= 0 ? 'paid' : (paidAmount > 0 ? 'partial' : 'unpaid'))).toLowerCase();

    return {
        ...debt,
        id: debt.id,
        name: debt.customer_name || debt.name || (debt.customer && debt.customer.name) || 'Unknown',
        phone: debt.customer_phone || debt.phone || (debt.customer && debt.customer.phone) || '-',
        totalAmount,
        paidAmount,
        remainingAmount,
        status: resolvedStatus,
        notes: debt.notes || '-',
        date: debt.date || debt.created_at || '',
    };
}

// Initialize
$(document).ready(function() {
    loadDebts();
    setupListeners();
    setTodayDate();
    window.addEventListener('languageChanged', () => renderDebts());
});

function setupListeners() {
    $('#btnSave').click(saveDebt);
    $('#btnUpdate').click(updateDebt);
    $('#btnClear').click(clearForm);
    $('#txtSearch').on('input', filterDebts);
    $('#filterStatus').change(filterDebts);
    $('#filterSort').change(sortDebts);
    $('#btnExport').click(exportToExcel);
    $('#btnConfirmPayment').click(processPayment);
    $('#btnConfirmDelete').click(confirmDelete);
}

function setTodayDate() {
    const today = new Date().toISOString().split('T')[0];
    $('#txtDate').val(today);
}

// Load debts from localStorage
function loadDebts() {
    $.ajax({
        url: '/api/debts',
        method: 'GET',
        success(res) {
            debts = (res || []).map(normalizeDebt);
            renderDebts();
            updateStats();
        },
        error() {
            showToast(t('debts_load_error'), 'error');
            debts = [];
            renderDebts();
            updateStats();
        }
    });
}

// Save debts to localStorage
function saveDebts() {
    // no-op (server-backed)
}

// Save new debt
function saveDebt() {
    const name = $('#txtCustomerName').val().trim();
    const phone = $('#txtPhone').val().trim();
    const amount = parseFloat($('#txtAmount').val());
    const date = $('#txtDate').val();
    const notes = $('#txtNotes').val().trim();

    if (!name || !amount) {
        alert(t('debts_save_missing_fields'));
        return;
    }

    $.ajax({
        url: '/api/debts',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ name, phone, totalAmount: amount, paidAmount: 0, remainingAmount: amount, notes: notes, date }),
        success() {
            showToast(t('debts_add_success'), 'success');
            clearForm();
            loadDebts();
        },
        error() { showToast(t('debts_add_error'), 'error'); }
    });
}

// Update debt
function updateDebt() {
    const payload = {
        name: $('#txtCustomerName').val().trim(),
        phone: $('#txtPhone').val().trim(),
        notes: $('#txtNotes').val().trim(),
        totalAmount: parseFloat($('#txtAmount').val())
    };

    $.ajax({
        url: '/api/debts/' + currentDebtId,
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success() { loadDebts(); clearForm(); showToast(t('debts_update_success'), 'success'); },
        error() { showToast(t('debts_update_error'), 'error'); }
    });
}

// Delete debt
let debtToDelete = null;
function deleteDebt(id) {
    debtToDelete = id;
    $('#deleteModal').modal('show');
}

function confirmDelete() {
    $.ajax({ url: '/api/debts/' + debtToDelete, method: 'DELETE', success() {
        $('#deleteModal').modal('hide'); loadDebts(); showToast(t('debts_delete_success'), 'success');
    }, error() { showToast(t('debts_delete_error'), 'error'); } });
}

// Select debt for editing
function selectDebt(id) {
    const debt = normalizeDebt(debts.find(d => d.id === id));
    if (!debt) return;

    currentDebtId = debt.id;
    $('#txtCustomerName').val(debt.name);
    $('#txtPhone').val(debt.phone === '-' ? '' : debt.phone);
    $('#txtAmount').val(debt.totalAmount).prop('disabled', true);
    $('#txtDate').val(debt.date);
    $('#txtNotes').val(debt.notes === '-' ? '' : debt.notes);

    $('#btnSave').hide();
    $('#btnUpdate').show();

    $('html, body').animate({ scrollTop: 0 }, 300);
}

// Clear form
function clearForm() {
    $('#debtForm')[0].reset();
    currentDebtId = null;
    $('#txtAmount').prop('disabled', false);
    $('#btnSave').show();
    $('#btnUpdate').hide();
    setTodayDate();
}

// Open payment modal
let currentPaymentDebt = null;
function openPaymentModal(id) {
    const debt = normalizeDebt(debts.find(d => d.id === id));
    if (!debt) return;

    currentPaymentDebt = debt;
    $('#paymentCustomerName').text(debt.name);
    $('#paymentDebtInfo').html(`
        ${t('debts_total')}: <strong class="text-danger">IQD ${fmt(debt.totalAmount)}</strong><br>
        ${t('debts_paid_amount')}: <strong class="text-success">IQD ${fmt(debt.paidAmount)}</strong><br>
        ${t('debts_remaining')}: <strong class="text-warning">IQD ${fmt(debt.remainingAmount)}</strong>
    `);
    $('#txtPaymentAmount').val('').attr('max', debt.remainingAmount);
    $('#txtPaymentNotes').val('');
    $('#paymentModal').modal('show');
}

// Process payment
function processPayment() {
    const amount = parseFloat($('#txtPaymentAmount').val());
    const notes = $('#txtPaymentNotes').val().trim();

    if (!amount || amount <= 0) {
        alert(t('debts_invalid_payment_amount'));
        return;
    }

    if (amount > currentPaymentDebt.remainingAmount) {
        alert(t('debts_payment_exceeds'));
        return;
    }

    $.ajax({
        url: '/api/debts/' + currentPaymentDebt.id,
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({ paymentAmount: amount, notes }),
        success() {
            $('#paymentModal').modal('hide'); loadDebts(); showToast(t('debts_payment_success'), 'success');
        },
        error() { showToast(t('debts_payment_error'), 'error'); }
    });
}

// Render debts table
function renderDebts() {
    const tbody = $('#debtsTableBody');
    tbody.empty();

    if (debts.length === 0) {
        tbody.html(`
            <tr>
                <td colspan="10" class="text-center py-5">
                    ${illus('card')}
                    <p class="text-muted">${t('debts_empty')}</p>
                </td>
            </tr>
        `);
        return;
    }

    debts.forEach((debt, index) => {
        const normalizedDebt = normalizeDebt(debt);
        const statusBadge = {
            unpaid: `<span class="badge badge-unpaid">${t('debts_status_unpaid')}</span>`,
            partial: `<span class="badge badge-partial">${t('debts_status_partial')}</span>`,
            paid: `<span class="badge badge-paid">${t('debts_status_paid')}</span>`
        }[normalizedDebt.status] || `<span class="badge badge-unpaid">${t('debts_status_unpaid')}</span>`;

        const row = `
            <tr style="cursor:pointer;" onclick="selectDebt(${normalizedDebt.id})">
                <td><strong>${index + 1}</strong></td>
                <td><strong>${normalizedDebt.name}</strong></td>
                <td>${normalizedDebt.phone || '-'}</td>
                <td class="amount-unpaid">IQD ${fmt(normalizedDebt.totalAmount)}</td>
                <td class="amount-paid">IQD ${fmt(normalizedDebt.paidAmount)}</td>
                <td><strong>IQD ${fmt(normalizedDebt.remainingAmount)}</strong></td>
                <td>${statusBadge}</td>
                <td>${formatDate(normalizedDebt.date)}</td>
                <td>${normalizedDebt.notes || '-'}</td>
                <td onclick="event.stopPropagation()">
                    ${normalizedDebt.status !== 'paid' ? `<button class="btn btn-success btn-sm" onclick="openPaymentModal(${normalizedDebt.id})">${icon('dollar',14)} ${t('debts_payment_title')}</button>` : ''}
                    <button class="btn btn-danger btn-sm" onclick="deleteDebt(${normalizedDebt.id})">${icon('trash',14)} ${t('delete')}</button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Filter debts
function filterDebts() {
    const search = $('#txtSearch').val().toLowerCase();
    const status = $('#filterStatus').val();

    let filtered = debts;

    if (search) {
        filtered = filtered.filter(d =>
            d.name.toLowerCase().includes(search) ||
            (d.phone && d.phone.includes(search))
        );
    }

    if (status) {
        filtered = filtered.filter(d => d.status === status);
    }

    const temp = debts;
    debts = filtered;
    renderDebts();
    debts = temp;
}

// Sort debts
function sortDebts() {
    const sort = $('#filterSort').val();

    switch(sort) {
        case 'date_desc':
            debts.sort((a, b) => new Date(b.date) - new Date(a.date));
            break;
        case 'date_asc':
            debts.sort((a, b) => new Date(a.date) - new Date(b.date));
            break;
        case 'amount_desc':
            debts.sort((a, b) => b.remainingAmount - a.remainingAmount);
            break;
        case 'amount_asc':
            debts.sort((a, b) => a.remainingAmount - b.remainingAmount);
            break;
    }

    renderDebts();
}

// Update statistics
function updateStats() {
    const normalized = debts.map(normalizeDebt);
    const totalDebt = normalized.reduce((sum, d) => sum + Number(d.totalAmount || 0), 0);
    const totalPaid = normalized.reduce((sum, d) => sum + Number(d.paidAmount || 0), 0);
    const uniqueCustomers = [...new Set(normalized.map(d => d.name))].length;

    $('#totalDebt').text('IQD ' + fmt(totalDebt));
    $('#totalPaid').text('IQD ' + fmt(totalPaid));
    $('#totalCustomers').text(uniqueCustomers);
}

// Export to Excel (simple CSV)
function exportToExcel() {
    if (debts.length === 0) {
           showToast(t('debts_empty_export'), 'info');
        return;
    }

    let csv = t('debts_csv_headers');

    debts.forEach((d, i) => {
        const status = d.status === 'unpaid' ? t('debts_status_unpaid') : (d.status === 'partial' ? t('debts_status_partial') : t('debts_status_paid'));
        csv += `${i+1},"${d.name}","${d.phone || ''}",${d.totalAmount},${d.paidAmount},${d.remainingAmount},"${status}","${d.date}","${d.notes || ''}"\n`;
    });

    const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `debts_${new Date().toISOString().split('T')[0]}.csv`;
    link.click();

    showToast(t('debts_file_exported'), 'success');
}

// Utilities
function fmt(n) {
    return (n || 0).toLocaleString('en-US', { minimumFractionDigits: 0 });
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-GB');
}

function showToast(msg, type = 'info') {
    return window.mkToast(msg, type);
}

console.log('Debts Controller Ready!');
