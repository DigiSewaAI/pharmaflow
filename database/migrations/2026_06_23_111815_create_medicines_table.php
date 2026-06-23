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
        Schema::create('medicines', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
    $table->string('batch_number')->nullable();
    $table->decimal('purchase_price', 10, 2)->default(0);
    $table->decimal('selling_price', 10, 2)->default(0);
    $table->integer('quantity')->default(0);
    $table->date('expiry_date')->nullable();
    $table->enum('status', ['in_stock', 'low_stock', 'expired'])->default('in_stock');
    $table->string('barcode')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
