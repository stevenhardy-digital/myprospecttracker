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
        Schema::create('referrer_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->string('month'); // Format: YYYY-MM
            $table->decimal('amount', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrer_fees');
    }
};
