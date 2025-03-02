<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add winners_count column to surveys table
        Schema::table('surveys', function (Blueprint $table) {
            $table->integer('winners_count')->default(3)->after('ends_at');
        });

        // Add total_score column to survey_responses table
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->integer('total_score')->default(0)->after('is_winner');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove winners_count column from surveys table
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn('winners_count');
        });

        // Remove total_score column from survey_responses table
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn('total_score');
        });
    }
};
