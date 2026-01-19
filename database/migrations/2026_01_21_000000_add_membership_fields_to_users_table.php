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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'membership_duration')) {
                $table->integer('membership_duration')->nullable()->default(null)->after('membership_date');
            }
            if (!Schema::hasColumn('users', 'membership_expiry')) {
                $table->timestamp('membership_expiry')->nullable()->default(null)->after('membership_duration');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'membership_expiry')) {
                $table->dropColumn('membership_expiry');
            }
            if (Schema::hasColumn('users', 'membership_duration')) {
                $table->dropColumn('membership_duration');
            }
        });
    }
};
