<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'current_store_id')) {
                $table->unsignedBigInteger('current_store_id')->nullable()->after('role');
            }
        });

        $ownerId = DB::table('users')->where('role', 'owner')->value('id') ?? 2;

        DB::table('stores')
            ->where('owner_user_id', 1)
            ->update(['owner_user_id' => $ownerId]);

        DB::table('stores')
            ->whereNull('owner_user_id')
            ->update(['owner_user_id' => $ownerId]);

        // Cashier users are seeded via UserSeeder for fresh installs, and this migration
        // only fixes the existing store ownership mapping for the current database.
    }

    public function down(): void
    {
        // no-op; kept intentionally simple
    }
};
