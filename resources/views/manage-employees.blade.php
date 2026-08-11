<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Employees - POS System</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-reboot.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-grid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/manage-employees.css') }}">
    <!-- Global Navbar CSS -->
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
</head>
<body>

    <!-- ════════════════════════════════════════════════════════════════════
         GLOBAL NAVBAR
         ════════════════════════════════════════════════════════════════════ -->
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



    <!-- Backdrop for popups -->
    <div class="global-popup-backdrop" id="globalBackdrop"></div>


    <!-- ACCESS DENIED OVERLAY -->
    <div id="accessDeniedOverlay" style="display:none;">
        <div style="text-align:center; padding: 100px 20px;">
            <div style="font-size: 80px;">⛔</div>
            <h1 class="text-danger mt-3">Access Denied</h1>
            <p class="lead">You do not have permission to view this page.</p>
            <div class="alert alert-warning d-inline-block">
                <strong>Required Access Level:</strong> Owner, Manager, or Assistant Manager
            </div>
            <br><br>
            <a href="{{ url('/pos-checkout') }}" class="btn btn-primary btn-lg" data-i18n="nav_pos">Go to POS Checkout</a>
            <a href="{{ url('/home') }}" class="btn btn-secondary btn-lg ml-2" data-i18n="nav_home">Go to Dashboard</a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div id="mainContent" style="display:none;">
        <div class="container mt-4">

            <!-- Page Title -->
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h2 class="page-title">👥 <span data-i18n="employees_title">Employee Management</span></h2>
                    <div>
                        <span class="badge badge-info p-2" id="currentUserBadge">👤 Loading...</span>
                        <span class="badge badge-success p-2 ml-2" id="accessLevelBadge">🔓 Access Level: --</span>
                    </div>
                </div>
            </div>
            <hr>

            <!-- Statistics Cards -->
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="card stat-card" style="border-left:4px solid #2E75B6;background:linear-gradient(135deg,#fff 0%,#f0f7ff 100%);box-shadow:0 4px 15px rgba(0,0,0,.1);border-radius:12px;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div style="font-size:48px;">👥</div>
                            <div>
                                <h6 class="text-muted mb-1" data-i18n="employees_total">Total Employees</h6>
                                <h2 id="totalEmployees" class="mb-0">0</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card" style="border-left:4px solid #28a745;background:linear-gradient(135deg,#fff 0%,#e6f7e6 100%);box-shadow:0 4px 15px rgba(0,0,0,.1);border-radius:12px;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div style="font-size:48px;">✅</div>
                            <div>
                                <h6 class="text-muted mb-1" data-i18n="items_active">Active Staff</h6>
                                <h2 id="activeEmployees" class="mb-0">0</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card" style="border-left:4px solid #ffc107;background:linear-gradient(135deg,#fff 0%,#fff9e6 100%);box-shadow:0 4px 15px rgba(0,0,0,.1);border-radius:12px;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div style="font-size:48px;">💼</div>
                            <div>
                                <h6 class="text-muted mb-1" data-i18n="employees_managers">Managers</h6>
                                <h2 id="totalManagers" class="mb-0">0</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card" style="border-left:4px solid #dc3545;background:linear-gradient(135deg,#fff 0%,#fff0f0 100%);box-shadow:0 4px 15px rgba(0,0,0,.1);border-radius:12px;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div style="font-size:48px;">💰</div>
                            <div>
                                <h6 class="text-muted mb-1" data-i18n="employees_payroll">Monthly Payroll</h6>
                                <h2 id="totalPayroll" class="mb-0" style="font-size:22px;">IQD 0</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Form Card -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card" style="border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.1);border:none;">
                        <div class="card-header bg-primary text-white" style="border-radius:12px 12px 0 0;">
                            <h5 class="mb-0">📝 <span data-i18n="employees_title">Employee Information</span></h5>
                        </div>
                        <div class="card-body">
                            <form id="employeeForm">

                                <!-- ROW 1: ID, Name, Position, Status, Salary -->
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="txtEmployeeId" data-i18n="employees_id">Employee ID</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="txtEmployeeId"
                                                       placeholder="E001" readonly>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                            id="btnGenerateId">🔄</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="txtName" data-i18n="employees_name">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="txtName"
                                                   data-i18n="employees_name" placeholder="Enter full name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="txtPosition" data-i18n="employees_position">Position <span class="text-danger">*</span></label>
                                            <select class="form-control" id="txtPosition" required>
                                                <option value="" data-i18n="employees_position">Select position</option>
                                                <option value="OWNER" data-i18n="position_owner">👑 Owner</option>
                                                <option value="MANAGER" data-i18n="position_manager">💼 Manager</option>
                                                <option value="ASSISTANT_MANAGER" data-i18n="position_asst_manager">📋 Assistant Manager</option>
                                                <option value="CASHIER" data-i18n="position_cashier">🧾 Cashier</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="txtStatus" data-i18n="items_status">Status</label>
                                            <select class="form-control" id="txtStatus">
                                                <option value="true" data-i18n="items_active">Active</option>
                                                <option value="false" data-i18n="items_inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="txtSalary" data-i18n="employees_salary">Salary (IQD) <span class="text-muted">(Optional)</span></label>
                                            <input type="number" class="form-control" id="txtSalary"
                                                   placeholder="0.00" step="0.01" min="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- ROW 2: Email, Phone, Hire Date -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="txtEmail" data-i18n="employees_email">Email <span class="text-muted">(Optional)</span></label>
                                            <input type="email" class="form-control" id="txtEmail"
                                                   data-i18n="employees_email" placeholder="employee@example.com">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="txtPhone" data-i18n="employees_phone">Phone <span class="text-muted">(Optional)</span></label>
                                            <input type="text" class="form-control" id="txtPhone"
                                                   data-i18n="employees_phone" placeholder="0771234567">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="txtHireDate" data-i18n="employees_hire_date">Hire Date <span class="text-muted">(Optional)</span></label>
                                            <input type="date" class="form-control" id="txtHireDate">
                                        </div>
                                    </div>
                                </div>

                                <!-- ROW 3: Login Credentials -->
                                <div class="row">
                                    <div class="col-12">
                                        <hr>
                                        <h6 class="text-primary font-weight-bold">
                                            🔐 Login Credentials
                                            <small class="text-muted font-weight-normal"> — Used by this employee to log into the system</small>
                                        </h6>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="txtUsername" data-i18n="employees_username">Username <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="txtUsername"
                                                   data-i18n="employees_username" placeholder="e.g. john_doe" required>
                                            <small class="form-text text-muted">Employee uses this to log in</small>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="txtPassword" data-i18n="employees_password">Password <span class="text-danger" id="passwordRequired">*</span></label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="txtPassword"
                                                       data-i18n="employees_password" placeholder="Enter password">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                            id="btnTogglePassword">👁️</button>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted" id="passwordHint">Required for new employees</small>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="txtConfirmPassword">Confirm Password <span class="text-danger" id="confirmRequired">*</span></label>
                                            <input type="password" class="form-control" id="txtConfirmPassword"
                                                   placeholder="Confirm password">
                                            <small class="form-text text-muted" id="confirmHint">Leave blank to keep existing</small>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Access Level Preview</label>
                                            <div id="accessLevelPreview" class="p-2 rounded text-center"
                                                 style="background:#f8f9fa;border:2px dashed #dee2e6;min-height:38px;">
                                                <small class="text-muted">Select a position</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-success" id="btnSave">
                                            💾 <span data-i18n="employees_save">Save Employee</span>
                                        </button>
                                        <button type="button" class="btn btn-primary" id="btnUpdate" style="display:none;">
                                            ✏️ <span data-i18n="employees_update">Update Employee</span>
                                        </button>
                                        <button type="button" class="btn btn-danger" id="btnDelete" style="display:none;">
                                            🗑️ <span data-i18n="employees_delete">Delete Employee</span>
                                        </button>
                                        <button type="button" class="btn btn-warning" id="btnClear">
                                            🔄 <span data-i18n="clear">Clear Form</span>
                                        </button>
                                        <span id="ownerOnlyNote" class="text-muted ml-3" style="display:none;">
                                            <small>⚠️ Only Owners can delete employees</small>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Section -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card" style="border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.1);border:none;">
                        <div class="card-header bg-info text-white" style="border-radius:12px 12px 0 0;">
                            <h5 class="mb-0">🔍 <span data-i18n="search">Search Employees</span></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label data-i18n="employees_name">Search by Name</label>
                                        <input type="text" class="form-control" id="txtSearchName"
                                               data-i18n="employees_name" placeholder="Enter employee name">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label data-i18n="employees_position">Filter by Position</label>
                                        <select class="form-control" id="txtSearchPosition">
                                            <option value="" data-i18n="pos_all_categories">All Positions</option>
                                            <option value="OWNER" data-i18n="position_owner">Owner</option>
                                            <option value="MANAGER" data-i18n="position_manager">Manager</option>
                                            <option value="ASSISTANT_MANAGER" data-i18n="position_asst_manager">Assistant Manager</option>
                                            <option value="CASHIER" data-i18n="position_cashier">Cashier</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label data-i18n="items_status">Status</label>
                                        <select class="form-control" id="txtSearchStatus">
                                            <option value="" data-i18n="pos_all_categories">All</option>
                                            <option value="true" data-i18n="items_active">Active</option>
                                            <option value="false" data-i18n="items_inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-info btn-block" id="btnSearch">
                                            🔍 <span data-i18n="search">Search</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employees Table -->
            <div class="row mt-4 mb-5">
                <div class="col-md-12">
                    <div class="card" style="border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.1);border:none;">
                        <div class="card-header bg-dark text-white" style="border-radius:12px 12px 0 0;">
                            <h5 class="mb-0">📋 <span data-i18n="employees_title">Employee List</span></h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="employeesTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th data-i18n="employees_id">ID</th>
                                            <th data-i18n="employees_name">Name</th>
                                            <th data-i18n="employees_position">Position</th>
                                            <th data-i18n="employees_username">Username</th>
                                            <th data-i18n="employees_email">Email</th>
                                            <th data-i18n="employees_phone">Phone</th>
                                            <th data-i18n="employees_salary">Salary</th>
                                            <th data-i18n="items_status">Status</th>
                                            <th data-i18n="edit">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblEmployeesBody">
                                        <!-- Rows loaded dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /container -->
    </div><!-- /mainContent -->

    <!-- Footer -->
    @include('partials.footer')

    <!-- jQuery & Bootstrap -->
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- Language Support -->
    <script src="{{ asset('js/languages.js') }}"></script>
    <!-- Navbar JS -->
    <script src="{{ asset('js/navbar-global.js') }}"></script>

    <!-- Server-rendered session values - the ONLY source of truth for role.
         The old localStorage-based role was fully client-controlled and
         trivially spoofable via devtools; this reads it from the actual
         session set during login/store-select instead. -->
    <script>
        window.CURRENT_STORE_ROLE = @json(session('current_store_role', 'CASHIER'));
        window.CURRENT_USER_NAME = @json(auth()->user()->name ?? 'User');
    </script>

    <!-- Controller -->
    <script src="{{ asset('js/manage-employees-controller.js') }}"></script>

</body>
</html>
