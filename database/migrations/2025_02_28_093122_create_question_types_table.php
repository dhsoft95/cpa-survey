<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('question_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed question types
        DB::table('question_types')->insert([
            ['name' => 'Multiple Choice', 'slug' => 'multiple-choice', 'description' => 'Select one option from many'],
            ['name' => 'Checkbox', 'slug' => 'checkbox', 'description' => 'Select multiple options'],
            ['name' => 'Dropdown', 'slug' => 'dropdown', 'description' => 'Select from dropdown list'],
            ['name' => 'Text', 'slug' => 'text', 'description' => 'Short text response'],
            ['name' => 'Textarea', 'slug' => 'textarea', 'description' => 'Long text response'],
            ['name' => 'Likert Scale', 'slug' => 'likert-scale', 'description' => 'Scale from Strongly Disagree to Strongly Agree'],
            ['name' => 'Number', 'slug' => 'number', 'description' => 'Numeric input'],
            ['name' => 'Date', 'slug' => 'date', 'description' => 'Date selection'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_types');
    }
};
