<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE posts MODIFY created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP');
            DB::statement('ALTER TABLE posts MODIFY updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE posts MODIFY created_at TIMESTAMP NULL DEFAULT NULL');
            DB::statement('ALTER TABLE posts MODIFY updated_at TIMESTAMP NULL DEFAULT NULL');
        }
    }
};
