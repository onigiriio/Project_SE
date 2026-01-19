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
            if (!Schema::hasColumn('users', 'library_card_id')) {
                $table->string('library_card_id')->nullable()->unique()->after('username')->comment('Unique library card identifier');
            }
            if (!Schema::hasColumn('users', 'registration_fee_paid')) {
                $table->boolean('registration_fee_paid')->default(false)->after('membership')->comment('Tracks if registration fee has been paid');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'registration_fee_paid')) {
                $table->dropColumn('registration_fee_paid');
            }
            if (Schema::hasColumn('users', 'library_card_id')) {
                $table->dropColumn('library_card_id');
            }
        });
    }
};
