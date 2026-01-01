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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->text('description');
            $table->string('isbn')->unique();
            $table->integer('pages');
            $table->string('publisher');
            $table->date('published_date');
            $table->string('cover_image')->nullable(); // Path to book cover image
            $table->decimal('price', 8, 2);
            $table->decimal('rating', 3, 2)->default(0); // Average rating
            $table->integer('rating_count')->default(0); // Number of ratings
            $table->integer('view_count')->default(0); // For trending
            $table->integer('recommended_count')->default(0); // For recommended
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
