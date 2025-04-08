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
            $table->text('perks')->nullable();
            $table->integer('duration');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('image_limit')->default(0);
            $table->integer('reviewer_limit')->default(0);
            $table->integer('quiz_limit')->default(0);
            $table->integer('quiz_questions_limit')->default(0);
            $table->boolean('can_mix_quiz')->default(false);
            $table->integer('mix_quiz_limit')->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promos');
    }
};