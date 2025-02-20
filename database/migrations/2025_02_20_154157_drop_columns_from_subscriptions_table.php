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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'photo_to_text',
                'reviewer_generator',
                'mock_quiz_generator',
                'save_reviewer',
                'download_reviewer',
                'discount_type',
                'percent_discount'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('photo_to_text')->nullable();
            $table->string('reviewer_generator')->nullable();
            $table->string('mock_quiz_generator')->nullable();
            $table->string('save_reviewer')->nullable();
            $table->string('download_reviewer')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('percent_discount', 5, 2)->nullable();
        });
    }
};
