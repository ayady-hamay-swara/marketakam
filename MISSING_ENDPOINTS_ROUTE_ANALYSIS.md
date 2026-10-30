# Missing Endpoints & Route Analysis

## 3. Missing Endpoints & Hardcoded/Demo Data

### 3.1 Hardcoded External API URLs (Critical)

**File: `public/js/place-order-controller.js`**
This entire controller uses hardcoded `http://localhost:8080/api/v1/*` endpoints - completely bypassing Laravel backend:

| Frontend Call | Hardcoded URL | Should Use Laravel Route |
|---------------|---------------|--------------------------|
| `loadOrderId()` | `GET http://localhost:8080/api/v1/orders` | `GET /api/orders` (missing) or `GET /api/businesses/{b}/branches/{br}/orders` |
| `loadAllCustomersIds()` | `GET http://localhost:8080/api/v1/customers` | `GET /api/customers` ✅ exists (ApiController@customers) |
| `loadAllCustomersIds()` (detail) | `GET http://localhost:8080/api/v1/customers/{id}` | `GET /api/customers/{id}` (missing) |
| `loadAllItemCodes()` | `GET http://localhost:8080/api/v1/items` | `GET /api/items` ✅ exists (CashierDashboardController@items) |
| `loadAllItemCodes()` (detail) | `GET http://localhost:8080/api/v1/items/{code}` | `GET /api/items/{code}` ✅ exists (CashierDashboardController@showItem) |
| `btnPlaceOrder` click | `POST http://localhost:8080/api/v1/orders` | `POST /api/orders` ✅ exists (ApiController@storeOrder) |

**Missing Legacy Routes Needed:**
- `GET /api/orders` - List orders (for order ID generation)
- `GET /api/customers/{id}` - Get single customer
- `GET /api/orders/{id}` - Get single order

### 3.2 Missing Legacy API Endpoints (Used by Frontend JS)

| Frontend JS | Calls | Route Exists? |
|-------------|-------|---------------|
| `manage-items-controller.js` | `GET /api/items` | ✅ Yes |
| | `GET /api/categories` | ✅ Yes |
| | `GET /api/items/next-code` | ✅ Yes |
| | `POST /api/items` | ✅ Yes |
| | `PUT /api/items/{code}` | ✅ Yes |
| | `DELETE /api/items/{code}` | ✅ Yes |
| | `GET /api/items/{code}` | ✅ Yes |
| `pos-checkout-debt-controller.js` | `GET /api/items` | ✅ Yes |
| | `GET /api/categories` | ✅ Yes |
| | `GET /api/customers` | ✅ Yes |
| | `POST /api/customers` | ✅ Yes |
| | `POST /api/orders` | ✅ Yes |
| | `GET /api/debts` | ✅ Yes |
| | `POST /api/debts` | ✅ Yes |
| | `GET /api/debts/{id}` | ✅ Yes |
| | `PUT /api/debts/{id}` | ✅ Yes |
| | `DELETE /api/debts/{id}` | ✅ Yes |
| `manage-debts-controller.js` | `GET /api/debts` | ✅ Yes |
| | `POST /api/debts` | ✅ Yes |
| | `PUT /api/debts/{id}` | ✅ Yes |
| | `DELETE /api/debts/{id}` | ✅ Yes |
| | `GET /api/debts/{id}` | ✅ Yes |
| | `POST /api/debts/{id}/payments` | ❌ **MISSING** |
| `manage-employees-controller.js` | `GET /api/employees` | ❌ **MISSING** (only tenant route exists) |
| | `POST /api/employees` | ❌ **MISSING** |
| | `PUT /api/employees/{id}` | ❌ **MISSING** |
| | `DELETE /api/employees/{id}` | ❌ **MISSING** |

### 3.3 Missing Tenant API Endpoints

| Feature | Missing Endpoint | Controller |
|---------|------------------|------------|
| Debt Payments | `POST /api/businesses/{b}/branches/{br}/debts/{debt}/payments` | ✅ Exists in Tenant\DebtController |
| Employee Management | All CRUD | ✅ Exists in Tenant\EmployeeController |
| Settings | `GET/POST/PUT /api/businesses/{b}/branches/{br}/settings` | ✅ Exists in Tenant\SettingsController |
| Reports | All report endpoints | ✅ Exists in Tenant\ReportController |
| Expenses | All CRUD | ✅ Exists in Tenant\ExpenseController |
| Inventory Adjustments | `POST /api/businesses/{b}/branches/{br}/inventory/adjust` | ✅ Exists in Tenant\InventoryController |

### 3.4 Hardcoded/Demo Data in Views

**File: `resources/views/profile.blade.php`** (line 295)
```javascript
/* Fallback display name when no authenticated user (mock login) */
```
- Has mock/fallback logic for unauthenticated users

**File: `resources/views/cashier/manage-items.blade.php`**
- Inline settings popup with hardcoded default values
- Category management uses localStorage fallback

**File: `resources/views/cashier/pos-checkout.blade.php`**
- Inline settings/calculator popups with hardcoded defaults
- Stats bar shows hardcoded "IQD 0" values until API loads

**File: `resources/views/cashier/search-orders.blade.php`** (debts management)
- Stats cards show hardcoded "IQD 0" / "0" values
- Empty state illustrations

### 3.5 Demo/Seed Data in Database Seeders

**Files in `database/seeders/`:**
- `UserSeeder.php` - Creates demo users (owner, cashier, manager) with hardcoded passwords
- `StoreSeeder.php` - Creates "Main Store" 
- `ProductSeeder.php` - Creates demo products with hardcoded prices/stock
- `CustomerSeeder.php` - Creates demo customers
- `OrderSeeder.php` / `OrderItemSeeder.php` - Creates demo orders
- `DebtSeeder.php` / `DebtPaymentSeeder.php` - Creates demo debts
- `EmployeeSeeder.php` - Creates demo employees
- `SettingsSeeder.php` - Creates demo settings (store_name: "Main Branch", currency: "IQD")

---

## 4. Duplicate, Unused, Broken, or Inconsistent Routes

### 4.1 Duplicate Routes (Same URI, Different Controllers)

| URI | Controllers | Issue |
|-----|-------------|-------|
| `GET /api/items` | `CashierDashboardController@items` (legacy) | Also: `Tenant\ProductController@index` at `/api/businesses/{b}/branches/{br}/products` |
| `GET /api/categories` | `CashierDashboardController@categories` (legacy) | Also: `Tenant\ProductController@categories` at `/api/businesses/{b}/branches/{br}/categories` |
| `POST /api/orders` | `ApiController@storeOrder` (legacy) | Also: `Tenant\OrderController@store` at `/api/businesses/{b}/branches/{br}/orders` |
| `GET /api/debts` | `ApiController@debts` (legacy) | Also: `Tenant\DebtController@index` at `/api/businesses/{b}/branches/{br}/debts` |
| `POST /api/debts` | `ApiController@storeDebt` (legacy) | Also: `Tenant\DebtController@store` at `/api/businesses/{b}/branches/{br}/debts` |

**Note:** These are intentional for backward compatibility (legacy vs tenant), not true duplicates.

### 4.2 Inconsistent Route Patterns

| Pattern | Legacy | Tenant | Inconsistency |
|---------|--------|--------|---------------|
| Item/Product ID | `/api/items/{code}` (by code) | `/api/.../products/{product}` (by ID) | Different identifier |
| Debt ID | `/api/debts/{id}` (by ID) | `/api/.../debts/{debt}` (by ID) | Consistent |
| Customer ID | `/api/customers` (no single GET) | `/api/.../customers/{customer}` (by ID) | Legacy missing single GET |
| Employee | **No legacy routes** | `/api/.../employees/{employee}` | Legacy completely missing |

### 4.3 Unused/Broken Routes

**Potentially Unused (No Frontend References Found):**
| Route | Controller | Likely Unused? |
|-------|------------|----------------|
| `GET /api/dashboard-summary` | `CashierDashboardController@summary` | Used by POS? Check JS |
| `GET /api/items/next-code` | `CashierDashboardController@nextCode` | Used by manage-items |
| `GET /api/items/{code}` | `CashierDashboardController@showItem` | Used by manage-items |
| `PUT /api/items/{code}` | `CashierDashboardController@updateItem` | Used by manage-items |
| `DELETE /api/items/{code}` | `CashierDashboardController@destroyItem` | Used by manage-items |
| `GET /api/debts/{id}` | `ApiController@showDebt` | Used by manage-debts |
| `PUT /api/debts/{id}` | `ApiController@updateDebt` | Used by manage-debts |
| `DELETE /api/debts/{id}` | `ApiController@deleteDebt` | Used by manage-debts |
| `POST /api/customers` | `ApiController@storeCustomer` | Used by POS |
| `GET /businesses/{business}/branches/{branch}/invoices` | View only | No API, just view |
| `GET /businesses/{business}/branches/{branch}/staff` | View only | No API, just view |
| `GET /businesses/{business}/branches/{branch}/manager` | View only | No API, just view |

**Broken Routes (Middleware Issues):**
- All tenant routes require `tenant.connection` middleware which needs `business` and `branch` route parameters
- Legacy routes require `store.ownership` middleware which uses `Store` model (central DB)
- The two middleware systems are incompatible - can't use both on same request

### 4.4 Route Parameter Inconsistencies

| Route | Parameter Name | Controller Expects |
|-------|----------------|-------------------|
| `/api/businesses/{business}/branches/{branch}/products` | `business`, `branch` | `$request->route('branch')` ✅ |
| `/api/businesses/{business}/branches/{branch}/products/{product}` | `business`, `branch`, `product` | Model binding ✅ |
| `/api/items/{code}` | `code` | `$code` parameter ✅ |
| `/api/debts/{id}` | `id` | `$id` parameter ✅ |

### 4.5 Missing Route Names (Named Routes)

| Route | Has Name? | Used in Views? |
|-------|-----------|----------------|
| `/dashboard` | ✅ `owner.dashboard` | No (direct URL) |
| `/home` | ✅ `home` | Yes (redirect) |
| `/businesses` | ✅ `businesses.index` | Yes |
| `/businesses/{business}/branches` | ✅ `branches.index` | Yes |
| Tenant API routes | ❌ No names | N/A (API only) |
| Legacy API routes | ❌ No names | N/A (API only) |

---

## Summary of Required Actions

### Immediate (Blocking Frontend Functionality)
1. **Fix `place-order-controller.js`** - Replace all `http://localhost:8080/api/v1/*` with Laravel routes
2. **Add missing legacy routes:**
   - `GET /api/orders` (for order ID generation)
   - `GET /api/customers/{id}` (for customer detail in place-order)
   - `GET /api/orders/{id}` (for order detail)
   - `POST /api/debts/{id}/payments` (for debt payments in manage-debts)
   - Full employee CRUD legacy routes (`/api/employees*`)

### Short Term (Architecture Consistency)
3. **Decide on migration strategy:** Keep dual architecture or migrate frontend to tenant routes
4. **Add route names** for all API routes (for maintainability)
5. **Standardize identifier** - use ID for all resources (not code for items, ID for debts)

### Cleanup (Technical Debt)
6. **Remove demo/fallback logic** from views once real backend integration complete
7. **Consolidate seeders** - separate demo data from required system data
8. **Document route ownership** - which frontend uses which routes
