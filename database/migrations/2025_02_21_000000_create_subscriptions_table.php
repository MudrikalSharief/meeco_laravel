<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {   
        Schema::dropIfExists('subscriptions');
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id('subscription_id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade'); 
            $table->unsignedBigInteger('promo_id');
            $table->foreign('promo_id')->references('promo_id')->on('promos')->onDelete('cascade'); 
            $table->string('reference_number')->unique()->nullable();
            $table->integer('reviewer_created')->default(0);
            $table->integer('quiz_created')->default(0);
            $table->dateTime('start_date')->nullable(); 
            $table->dateTime('end_date')->nullable(); 
            $table->enum('status', ['active', 'expired','cancelled','limit'])->default('active'); 
            $table->enum('subscription_type', ['Admin Granted', 'Subscribed'])->default('Subscribed'); 
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

        });
    }

    public function down()
    {
        
    }
};