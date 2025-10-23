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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // ID guna UUID
            $table->string('item'); // Nama item
            $table->decimal('amount', 10, 2); // Harga asal
            $table->decimal('fee', 10, 2)->nullable(); // Fee eskro (2.5%)
            $table->unsignedBigInteger('buyer_id')->nullable(); // Buyer (nullable sebab belum tahu siapa)
            $table->unsignedBigInteger('seller_id')->nullable(); // Seller
            $table->string('status')->default('pending'); // Status transaksi
            $table->json('meta')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
