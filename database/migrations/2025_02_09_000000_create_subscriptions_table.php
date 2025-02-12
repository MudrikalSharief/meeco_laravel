<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('pricing', 8, 2);
            $table->string('duration');
            $table->enum('photo_to_text', ['unlimited', 'limited']);
            $table->enum('reviewer_generator', ['unlimited', 'limited']);
            $table->enum('mock_quiz_generator', ['unlimited', 'limited']);
            $table->enum('save_reviewer', ['unlimited', 'limited']);
            $table->enum('download_reviewer', ['unlimited', 'limited']);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('discount_type', ['percent', 'fixed']);
            $table->decimal('percent_discount', 5, 2)->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}