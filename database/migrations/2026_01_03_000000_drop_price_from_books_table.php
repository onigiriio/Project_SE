<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('books', 'price')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('price');
            });
        }
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->default(0.00)->after('cover_image');
        });
    }
};
