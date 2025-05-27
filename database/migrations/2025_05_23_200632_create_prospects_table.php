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
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('social_handle')->nullable();
            $table->text('pain_points')->nullable();
            $table->enum('stage', [
                'expand_network',
                'relationship_building',
                'ask_question',
                'qualify_pain',
                'expose_tool',
                'follow_up',
                'close',
            ])->default('expand_network');
            $table->date('last_contacted')->nullable();
            $table->date('next_follow_up')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};
