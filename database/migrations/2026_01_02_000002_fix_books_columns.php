<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (! Schema::hasColumn('books', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0)->after('price');
            }
            if (! Schema::hasColumn('books', 'rating_count')) {
                $table->integer('rating_count')->default(0)->after('rating');
            }
            if (! Schema::hasColumn('books', 'view_count')) {
                $table->integer('view_count')->default(0)->after('rating_count');
            }
            if (! Schema::hasColumn('books', 'recommended_count')) {
                $table->integer('recommended_count')->default(0)->after('view_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'recommended_count')) {
                $table->dropColumn('recommended_count');
            }
            if (Schema::hasColumn('books', 'view_count')) {
                $table->dropColumn('view_count');
            }
            if (Schema::hasColumn('books', 'rating_count')) {
                $table->dropColumn('rating_count');
            }
            if (Schema::hasColumn('books', 'rating')) {
                $table->dropColumn('rating');
            }
        });
    }
};
