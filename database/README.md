# Marketakam database migrations and seeders (draft)

This is a proposed schema package, not a drop-in replacement for your existing migrations. Compare it with your current migrations and ER diagram before using it on any database containing data.

## Architecture
- **Global database:** `users` (existing Laravel migration), `businesses`, `business_memberships`, `tenant_databases`.
- **One tenant database per independent business:** branches, branch-user assignments, categories, products, inventory, orders, payments, customers, employees, debts, debt payments, expenses, settings.
- Each tenant DB has the same schema. Branches are physical locations inside a business.
- Tenant-side `user_id` / `cashier_user_id` values refer to global users and intentionally have no cross-database FK.
- Money is stored as whole IQD integers. Enforce the usual 250-IQD price increments in application validation; the database does not enforce divisibility by 250.
- Quantities use `DECIMAL(14,3)` for weighed products as well as pieces.

## Before running
1. The standard Laravel `users` migration must already have run on the global DB before the `businesses` migration.
2. Run global migrations against the global connection and tenant migrations against each selected tenant connection. This package does not implement tenant provisioning or dynamic connection switching.
3. Do not run both these files and existing migrations that create the same tables without reconciling them first.
4. Back up existing databases. Never use `migrate:fresh` on data you need.
5. Review deletion policies and business rules before production.

## Global migrations
Point the default connection at the global DB and run:

```bash
php artisan migrate --path=database/migrations/global
```

## Tenant migrations
Point the active/default connection at a newly created tenant DB and run:

```bash
php artisan migrate --path=database/migrations/tenant
```

Repeat for each tenant DB. Confirm the active connection before running each command.

## Global demo seeder
Set a local-only password in `.env`:

```dotenv
MARKETAKAM_DEMO_PASSWORD="replace-with-a-long-local-only-password"
```

Then, while connected to the global DB, run:

```bash
php artisan db:seed --class="Database\\Seeders\\Global\\GlobalDatabaseSeeder"
```

This seeder refuses to run outside the `local` environment. It creates `ahmed@example.test` and `dara@example.test` using the configured password, a demo business, memberships, and a placeholder tenant mapping. It does **not** create a MySQL database or configure a tenant connection.

## Tenant demo seeder
After running tenant migrations, point the active/default connection at a tenant DB and run:

```bash
php artisan db:seed --class="Database\\Seeders\\Tenant\\TenantDatabaseSeeder"
```

It seeds two branches, four categories, four products, and sample inventory. To also assign the demo cashier to Main Branch, set `MARKETAKAM_DEMO_CASHIER_USER_ID` to Dara’s actual global `users.id` in the local `.env` before running this seeder. The tenant seeder does not guess global IDs. The sample is intended to be re-runnable.

## Review notes
- `settings` indexes `(branch_id, key)` but does not enforce uniqueness for business-wide settings (`branch_id` NULL). MySQL allows multiple NULL values in a unique index; enforce that rule in application logic or model the scope explicitly.
- Branch assignment is not sufficient by itself: the app must also verify active business membership.
- Additional tables such as suppliers/purchases, cash drawer sessions, refunds, and audit logs are not included because they depend on your required release workflow.
- Demo seeders are for local development only; do not use demo accounts in production.
