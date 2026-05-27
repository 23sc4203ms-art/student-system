<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'Age')) {
                $table->dropColumn('Age');
            }

            if (Schema::hasColumn('students', 'Course')) {
                $table->dropColumn('Course');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'Age')) {
                $table->unsignedTinyInteger('Age')->nullable()->after('Address');
            }

            if (! Schema::hasColumn('students', 'Course')) {
                $table->string('Course')->nullable()->after('Age');
            }
        });
    }
};
