# Marketakam Frontend-to-Backend Audit

## Executive Summary

This document traces all frontend pages and actions to their corresponding backend routes, controllers, models, middleware, and database tables. The application has a **dual architecture**:

1. **Legacy Architecture** (Store-based): Uses `/api/*` routes with `store.ownership` middleware
2. **New Multi-Tenant Architecture** (Business/Branch-based): Uses `/api/businesses/{business}/branches/{branch}/*` routes with `tenant.connection` middleware

---

## Frontend Pages & Their API Endpoints

### 1. Cashier Dashboard (`/home` → `cashier.index`)
**View:** `resources/views/cashier/index.blade.php`
**Route:** `GET /home` → `CashierDashboardController@index`
**Middleware:** `auth`, `tenant.connection`
**Controller:** `CashierDashboardController::index()`
**Models:** `Order`, `Product`, `BranchInventory`
**Tables:** `orders`, `products`, `branch_inventory`
**API Calls:** `/api/dashboard-summary` (legacy) or `/api/businesses/{b}/branches/{br}/reports/dashboard-summary` (new)

---

### 2. Manage Items (`/businesses/{b}/branches/{br}/items` → `cashier.manage-items`)
**View:** `resources/views/cashier/manage-items.blade.php`
**JS Controller:** `public/js/manage-items-controller.js`
**Route:** `GET /businesses/{business}/branches/{branch}/items` (view)
**Legacy API Routes (store.ownership):**
| Action | Frontend Call | Route | Controller |
|--------|--------------|-------|------------|
| Load items | `loadAllItems()` | `GET /api/items` | `CashierDashboardController@items` |
| Load categories | `loadCategories()` | `GET /api/categories` | `CashierDashboardController@categories` |
| Generate code | `generateItemCode()` | `GET /api/items/next-code` | `CashierDashboardController@nextCode` |
| Save item | `saveItem()` | `POST /api/items` | `CashierDashboardController@storeItem` |
| Update item | `updateItem()` | `PUT /api/items/{code}` | `CashierDashboardController@updateItem` |
| Delete item | `deleteItem()` | `DELETE /api/items/{code}` | `CashierDashboardController@destroyItem` |
| Show item | `selectItem()` | `GET /api/items/{code}` | `CashierDashboardController@showItem` |

**New Tenant API Routes (tenant.connection):**
| Action | Route | Controller |
|--------|-------|------------|
| List products | `GET /api/businesses/{b}/branches/{br}/products` | `Tenant\ProductController@index` |
| Get next code | `GET /api/businesses/{b}/branches/{br}/products/next-code` | `Tenant\ProductController@nextCode` |
| Show product | `GET /api/businesses/{b}/branches/{br}/products/{product}` | `Tenant\ProductController@show` |
| Create product | `POST /api/businesses/{b}/branches/{br}/products` | `Tenant\ProductController@store` |
| Update product | `PUT /api/businesses/{b}/branches/{br}/products/{product}` | `Tenant\ProductController@update` |
| Delete product | `DELETE /api/businesses/{b}/branches/{br}/products/{product}` | `Tenant\ProductController@destroy` |
| Categories | `GET /api/businesses/{b}/branches/{br}/categories` | `Tenant\ProductController@categories` |

**Models:** `Product`, `Category`, `BranchInventory`, `Branch`, `Store`
**Tables:** `products`, `categories`, `branch_inventory`, `branches`, `stores`

---

### 3. POS Checkout (`/businesses/{b}/branches/{br}/pos` → `cashier.pos-checkout`)
**View:** `resources/views/cashier/pos-checkout.blade.php`
**JS Controller:** `public/js/pos-checkout-debt-controller.js`
**Route:** `GET /businesses/{business}/branches/{branch}/pos` (view)
**Legacy API Routes (store.ownership):**
| Action | Frontend Call | Route | Controller |
|--------|--------------|-------|------------|
| Load products | `loadProducts()` | `GET /api/items` | `CashierDashboardController@items` |
| Load categories | `loadCategories()` | `GET /api/categories` | `CashierDashboardController@categories` |
| Load customers | `loadCustomers()` | `GET /api/customers` | `ApiController@customers` |
| Create order | `completeSale()` | `POST /api/orders` | `ApiController@storeOrder` |
| Create debt | `processPayment()` (debt) | `POST /api/debts` | `ApiController@storeDebt` |

**New Tenant API Routes (tenant.connection):**
| Action | Route | Controller |
|--------|-------|------------|
| List products | `GET /api/businesses/{b}/branches/{br}/products` | `Tenant\ProductController@index` |
| List categories | `GET /api/businesses/{b}/branches/{br}/categories` | `Tenant\ProductController@categories` |
| List customers | `GET /api/businesses/{b}/branches/{br}/customers` | `Tenant\CustomerController@index` |
| Create order | `POST /api/businesses/{b}/branches/{br}/orders` | `Tenant\OrderController@store` |
| Add payment | `POST /api/businesses/{b}/branches/{br}/orders/{order}/payments` | `Tenant\OrderController@addPayment` |
| Create debt | `POST /api/businesses/{b}/branches/{br}/debts` | `Tenant\DebtController@store` |
| Add debt payment | `POST /api/businesses/{b}/branches/{br}/debts/{debt}/payments` | `Tenant\DebtController@addPayment` |

**Models:** `Product`, `Category`, `Customer`, `Order`, `OrderItem`, `Debt`, `BranchInventory`, `Payment`, `InventoryMovement`
**Tables:** `products`, `categories`, `customers`, `orders`, `order_items`, `debts`, `branch_inventory`, `payments`, `inventory_movements`

---

### 4. Manage Debts (`/businesses/{b}/branches/{br}/debts` → `cashier.search-orders`)
**View:** `resources/views/cashier/search-orders.blade.php` (contains debts management)
**JS Controller:** `public/js/manage-debts-controller.js`
**Route:** `GET /businesses/{business}/branches/{branch}/orders/search` (view)
**Legacy API Routes (store.ownership):**
| Action | Frontend Call | Route | Controller |
|--------|--------------|-------|------------|
| Load debts | `loadDebts()` | `GET /api/debts` | `ApiController@debts` |
| Save debt | `saveDebt()` | `POST /api/debts` | `ApiController@storeDebt` |
| Update debt | `updateDebt()` | `PUT /api/debts/{id}` | `ApiController@updateDebt` |
| Delete debt | `confirmDelete()` | `DELETE /api/debts/{id}` | `ApiController@deleteDebt` |
| Show debt | `selectDebt()` | `GET /api/debts/{id}` | `ApiController@showDebt` |
| Process payment | `processPayment()` | `POST /api/debts/{id}/payments` | (Not implemented in legacy) |

**New Tenant API Routes (tenant.connection):**
| Action | Route | Controller |
|--------|-------|------------|
| List debts | `GET /api/businesses/{b}/branches/{br}/debts` | `Tenant\DebtController@index` |
| Create debt | `POST /api/businesses/{b}/branches/{br}/debts` | `Tenant\DebtController@store` |
| Show debt | `GET /api/businesses/{b}/branches/{br}/debts/{debt}` | `Tenant\DebtController@show` |
| Update debt | `PUT /api/businesses/{b}/branches/{br}/debts/{debt}` | `Tenant\DebtController@update` |
| Delete debt | `DELETE /api/businesses/{b}/branches/{br}/debts/{debt}` | `Tenant\DebtController@destroy` |
| Add payment | `POST /api/businesses/{b}/branches/{br}/debts/{debt}/payments` | `Tenant\DebtController@addPayment` |

**Models:** `Debt`, `Customer`, `DebtPayment`
**Tables:** `debts`, `customers`, `debt_payments`

---

### 5. Manage Employees (`/businesses/{b}/branches/{br}/employees` → `owner.manage-employees`)
**View:** `resources/views/owner/manage-employees.blade.php`
**JS Controller:** `public/js/manage-employees-controller.js`
**Route:** `GET /businesses/{business}/branches/{branch}/employees` (view)
**New Tenant API Routes (tenant.connection):**
| Action | Route | Controller |
|--------|-------|------------|
| List employees | `GET /api/businesses/{b}/branches/{br}/employees` | `Tenant\EmployeeController@index` |
| Create employee | `POST /api/businesses/{b}/branches/{br}/employees` | `Tenant\EmployeeController@store` |
| Show employee | `GET /api/businesses/{b}/branches/{br}/employees/{employee}` | `Tenant\EmployeeController@show` |
| Update employee | `PUT /api/businesses/{b}/branches/{br}/employees/{employee}` | `Tenant\EmployeeController@update` |
| Delete employee | `DELETE /api/businesses/{b}/branches/{br}/employees/{employee}` | `Tenant\EmployeeController@destroy` |

**Models:** `Employee`, `BranchUserAssignment`, `User`
**Tables:** `employees`, `branch_user_assignments`, `users`

---

### 6. Owner Dashboard (`/dashboard` → `owner.dashboard`)
**View:** `resources/views/owner/dashboard.blade.php`
**Route:** `GET /dashboard` (legacy, store.ownership)
**Controller:** Closure in routes/web.php
**Models:** `Store`
**Tables:** `stores`

---

### 7. Business Management (`/businesses` → `owner.businesses.*`)
**Views:** `resources/views/owner/other-business.blade.php`, etc.
**Routes:** `GET/POST/PUT/DELETE /businesses/*` (global, role:owner,super_dev)
**Controller:** `BusinessController`
**Models:** `Business`, `BusinessMembership`, `User`, `TenantDatabase`
**Tables:** `businesses`, `business_memberships`, `users`, `tenant_databases`

---

### 8. Branch Management (`/businesses/{b}/branches` → `branches.*`)
**Views:** Various
**Routes:** `GET/POST/PUT/DELETE /businesses/{business}/branches/*` (global, role:owner,manager,cashier,super_dev)
**Controller:** `BranchController`
**Models:** `Branch`, `Business`
**Tables:** `branches`, `businesses`

---

## Middleware Chain

### Legacy Routes (`store.ownership`)
```
auth → store.ownership (VerifyStoreOwnership)
```
- **VerifyStoreOwnership**: Validates user access to store, sets `verified_store_id` on request
- Uses `Store` model (central DB)
- Falls back to `user.current_store_id` or session

### New Tenant Routes (`tenant.connection`)
```
auth → tenant.connection (SetTenantConnection)
```
- **SetTenantConnection**: Switches DB connection to tenant database based on `business_id` and `branch_id`
- Reads `tenant_databases` table from central DB
- Sets `database.default` to `tenant` connection dynamically

### Role Middleware
```
role:owner,super_dev
role:owner,manager,cashier,super_dev
```
- **RoleMiddleware**: Checks user role against required roles

---

## Database Schema Mapping

### Central Database (Global)
| Table | Model | Purpose |
|-------|-------|---------|
| `users` | `User` | Authentication, roles, current_store_id |
| `stores` | `Store` | Legacy store model (owner_user_id, name, status) |
| `businesses` | `Business` | Multi-tenant business entities |
| `business_memberships` | `BusinessMembership` | User-Business relationships with roles |
| `tenant_databases` | `TenantDatabase` | Tenant DB connection config per business |

### Tenant Database (Per Business)
| Table | Model | Purpose |
|-------|-------|---------|
| `branches` | `Branch` | Physical locations/stores |
| `branch_user_assignments` | `BranchUserAssignment` | User-Branch role assignments |
| `categories` | `Category` | Product categories |
| `products` | `Product` | Product catalog (tenant-wide) |
| `branch_inventory` | `BranchInventory` | Stock per branch per product |
| `customers` | `Customer` | Customer records |
| `orders` | `Order` | Sales orders |
| `order_items` | `OrderItem` | Order line items |
| `payments` | `Payment` | Payment records |
| `inventory_movements` | `InventoryMovement` | Stock movement log |
| `debts` | `Debt` | Customer debts |
| `debt_payments` | `DebtPayment` | Debt payment records |
| `employees` | `Employee` | Employee records |
| `expenses` | `Expense` | Expense tracking |
| `settings` | `Setting` | Branch settings |
| `market_product_details` | `MarketProductDetail` | Module-specific product data |

---

## Controllers & Their Responsibilities

### Legacy Controllers (Store-based)
| Controller | Methods | Purpose |
|------------|---------|---------|
| `CashierDashboardController` | `items`, `storeItem`, `updateItem`, `destroyItem`, `showItem`, `nextCode`, `categories`, `summary`, `index` | POS item management, dashboard stats |
| `ApiController` | `customers`, `storeCustomer`, `storeOrder`, `storeDebt`, `debts`, `showDebt`, `updateDebt`, `deleteDebt` | Customer, order, debt APIs |

### New Tenant Controllers (Branch-based)
| Controller | Methods | Purpose |
|------------|---------|---------|
| `Tenant\ProductController` | `index`, `store`, `show`, `update`, `destroy`, `nextCode`, `categories` | Product CRUD |
| `Tenant\OrderController` | `index`, `store`, `show`, `update`, `destroy`, `addPayment` | Order management |
| `Tenant\CustomerController` | `index`, `store`, `show`, `update`, `destroy` | Customer management |
| `Tenant\DebtController` | `index`, `store`, `show`, `update`, `destroy`, `addPayment` | Debt management |
| `Tenant\InventoryController` | `index`, `adjust`, `movements` | Inventory adjustments |
| `Tenant\EmployeeController` | `index`, `store`, `show`, `update`, `destroy` | Employee management |
| `Tenant\ExpenseController` | `index`, `store`, `show`, `update`, `destroy` | Expense tracking |
| `Tenant\SettingsController` | `index`, `store`, `update` | Settings |
| `Tenant\ReportController` | `dashboard`, `sales`, `inventory`, `financial`, `dashboardSummary` | Reports |

### Global Controllers
| Controller | Methods | Purpose |
|------------|---------|---------|
| `BusinessController` | `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`, `addMember`, `removeMember`, `updateMember` | Business CRUD + memberships |
| `BranchController` | `index`, `create`, `store`, `show`, `edit`, `update`, `destroy` | Branch CRUD |
| `loginController` | `login`, `logout` | Authentication |

---

## Files That Need Modification for Full Migration

### High Priority (Breaking Legacy Compatibility)
1. **`routes/web.php`** - Legacy routes still used by frontend JS; need to either:
   - Keep legacy routes working (current approach with compatibility layer)
   - Or update all frontend JS to use new tenant routes

2. **`app/Http/Controllers/CashierDashboardController.php`** - Legacy controller with compatibility layer (`ensureBranchExists`)

3. **`app/Http/Controllers/ApiController.php`** - Legacy controller for orders/customers/debts

4. **`app/Http/Middleware/VerifyStoreOwnership.php`** - Uses `Store` model; needs to work with new architecture

### Medium Priority (New Architecture Gaps)
5. **`app/Http/Controllers/Tenant/*`** - New controllers need full implementation and testing

6. **`app/Models/BranchInventory.php`** - Table name mismatch fixed (`branch_inventory` vs `branch_inventories`)

7. **Frontend JS files** - All use legacy `/api/*` URLs; need to be updated for new routes:
   - `manage-items-controller.js` → uses `/api/items`, `/api/categories`
   - `pos-checkout-debt-controller.js` → uses `/api/items`, `/api/categories`, `/api/customers`, `/api/orders`, `/api/debts`
   - `manage-debts-controller.js` → uses `/api/debts`
   - `manage-employees-controller.js` → uses `/api/employees` (already uses relative path)

### Low Priority (Cleanup)
8. **`resources/views/cashier/manage-items.blade.php`** - Has inline JS for settings popup
9. **`resources/views/cashier/pos-checkout.blade.php`** - Has inline JS for settings/calculator
10. **`resources/views/cashier/search-orders.blade.php`** - Debts management view

---

## Test Coverage

### Current Tests (Passing)
- `Tests\Feature\ManageItemsApiTest` - Legacy API item management (3 tests)
- `Tests\Feature\OwnerDashboardTest` - Owner dashboard (1 test)
- `Tests\Feature\ExampleTest` - Basic response (1 test)
- `Tests\Unit\ExampleTest` - Unit test (1 test)

### Missing Tests Needed
- Tenant API controllers (`Tenant\ProductController`, `Tenant\OrderController`, etc.)
- Multi-tenancy isolation (business/branch data separation)
- Role-based access control
- Inventory management (stock adjustments, movements)
- Debt payments
- Business/Branch CRUD

---

## Migration Strategy Recommendations

### Option 1: Maintain Dual Architecture (Current)
- Keep legacy routes working via compatibility layer
- Gradually migrate frontend JS to new tenant routes
- Pros: Zero downtime, gradual migration
- Cons: Code duplication, maintenance burden

### Option 2: Full Migration to Tenant Routes
- Update all frontend JS to use `/api/businesses/{b}/branches/{br}/*`
- Remove legacy routes and compatibility code
- Pros: Clean architecture, single code path
- Cons: Big bang change, requires updating all frontend

### Option 3: API Versioning
- Keep `/api/v1/*` for legacy
- Add `/api/v2/*` for tenant routes
- Deprecate v1 over time

---

## Key Issues Identified

1. **Table Name Mismatch**: `BranchInventory` model expected `branch_inventories` but migration creates `branch_inventory` ✅ FIXED

2. **Legacy Store ID → Branch ID Mapping**: `CashierDashboardController::ensureBranchExists()` creates Branch records on-demand for legacy Store IDs

3. **Frontend JS Hardcoded URLs**: All JS controllers use legacy `/api/*` paths

4. **Missing Tenant Route for Debt Payments**: Legacy `ApiController` doesn't have debt payment endpoint; new `Tenant\DebtController` has it

5. **No Employee API in Legacy**: `manage-employees-controller.js` uses `/api/employees` but no legacy route exists (only tenant route)

6. **Settings API Missing**: No legacy `/api/settings` route; only tenant route exists

7. **Reports API Missing**: No legacy `/api/reports/*` routes; only tenant routes exist
