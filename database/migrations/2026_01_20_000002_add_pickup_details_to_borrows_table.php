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
        Schema::table('borrows', function (Blueprint $table) {
            $table->date('pickup_date')->nullable()->after('borrowed_at');
            $table->time('pickup_time')->nullable()->after('pickup_date');
            $table->integer('duration_days')->default(14)->after('pickup_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->dropColumn(['pickup_date', 'pickup_time', 'duration_days']);
        });
    }
};
