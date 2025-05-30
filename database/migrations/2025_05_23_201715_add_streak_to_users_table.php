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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username');
            $table->string('ref_code')->nullable()->unique();
            $table->unsignedBigInteger('referrer_id')->nullable()->after('id');
            $table->integer('streak')->default(0)->after('password');
            $table->timestamp('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username');
            $table->dropColumn('streak');
            $table->dropColumn('last_login_date');
            $table->dropColumn('referrer_id');
        });
    }
};
