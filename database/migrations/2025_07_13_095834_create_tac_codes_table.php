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
        Schema::create('tac_codes', function (Blueprint $table) {
            $table->id();
            $table->uuid('transaction_id');
            $table->string('contact'); // email or phone number
            $table->string('code');    // 6-digit TAC
            $table->timestamp('created_at')->nullable(); // only created_at, no updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tac_codes');
    }
};
