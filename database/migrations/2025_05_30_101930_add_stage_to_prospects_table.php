<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->enum('stage', [
                'expand_network',
                'relationship_building',
                'ask_question',
                'qualify_pain',
                'expose_tool',
                'follow_up',
                'close',
            ])->default('expand_network')->after('pain_points');
        });
    }

    public function down()
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropColumn('stage');
        });
    }
};
