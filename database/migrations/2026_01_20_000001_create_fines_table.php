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
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('borrow_id')->nullable()->constrained('borrows')->onDelete('cascade')->comment('Related borrow record if applicable');
            $table->decimal('amount', 8, 2)->comment('Fine amount');
            $table->string('reason')->comment('Reason for fine (e.g., overdue, damaged)');
            $table->string('status')->default('unpaid')->comment('unpaid, partial, paid');
            $table->decimal('amount_paid', 8, 2)->default(0)->comment('Amount already paid towards fine');
            $table->timestamp('due_date')->nullable()->comment('Date by which fine must be paid');
            $table->timestamp('paid_at')->nullable()->comment('Date when fine was fully paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
