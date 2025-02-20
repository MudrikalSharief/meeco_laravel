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
        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn([
                'features',
                'limitations',
                'created_at',
                'updated_at',
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
        Schema::table('promos', function (Blueprint $table) {
            $table->text('features')->nullable();
            $table->text('limitations')->nullable();
            $table->timestamps(); // This will add both created_at and updated_at columns
            $table->string('discount_type')->nullable();
            $table->decimal('percent_discount', 5, 2)->nullable();
        });
    }
};
