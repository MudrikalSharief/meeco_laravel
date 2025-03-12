<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert a default promo into the promos table
        DB::table('promos')->insert([
            'name' => 'Free Trial',
            'price' => 0.00,
            'perks' => 'Default perks',
            'duration' => 3,
            'start_date' => now(),
            'end_date' => now()->addDays(90),
            'status' => 'inactive',
            'image_limit' => 2,
            'reviewer_limit' => 2,
            'quiz_limit' => 2,
            'quiz_questions_limit' => 10,
            'can_mix_quiz' => false,
            'mix_quiz_limit' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the default promo from the promos table
        DB::table('promos')->where('name', 'Free Trial')->delete();
    }
};
