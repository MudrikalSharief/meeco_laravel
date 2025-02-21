<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id('promo_id')->primary();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('perks');
            $table->integer('duration');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('photo_to_text')->nullable();
            $table->integer('photo_limit')->nullable();
            $table->string('reviewer_generator')->nullable();
            $table->integer('reviewer_limit')->nullable();
            $table->string('mock_quiz_generator')->nullable();
            $table->integer('mock_quiz_limit')->nullable();
            $table->string('save_reviewer')->nullable();
            $table->integer('save_reviewer_limit')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    // public function down()
    // {
    //     Schema::dropIfExists('promos');
    // }
};