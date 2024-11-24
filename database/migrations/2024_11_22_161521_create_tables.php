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
        // Create Users table
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // Primary key as unsignedBigInteger
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->timestamp('date_created')->useCurrent();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
            $table->rememberToken();
        });

        // Create Promos table
        Schema::create('promos', function (Blueprint $table) {
            $table->id('promo_id'); // Primary key as unsignedBigInteger
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('perks');
            $table->integer('duration')->comment('Duration in days');
            $table->text('limitations')->nullable();
            $table->timestamps();
        });

        // Create Subscriptions table
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id('subscription_id'); // Primary key as unsignedBigInteger
            $table->unsignedBigInteger('user_id'); // Explicitly define user_id
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('promo_id'); // Explicitly define promo_id
            $table->foreign('promo_id')->references('promo_id')->on('promos')->onDelete('cascade');
            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('end_date')->nullable();
            $table->enum('status', ['active', 'cancelled', 'expired'])->default('active');
            $table->timestamps();
        });

        // Create Admin_Actions table
        Schema::create('admin_actions', function (Blueprint $table) {
            $table->id('action_id');
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('action_type');
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamps();
        });

        // Create Subjects table
        Schema::create('subjects', function (Blueprint $table) {
            $table->id('subject_id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Create Topics table
        Schema::create('topics', function (Blueprint $table) {
            $table->id('topic_id');
            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        // Create Questions table
        Schema::create('questions', function (Blueprint $table) {
            $table->id('question_id');
            $table->unsignedBigInteger('topic_id');
            $table->foreign('topic_id')->references('topic_id')->on('topics')->onDelete('cascade');
            $table->text('question_text');
            $table->text('answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('admin_actions');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('promos');
        Schema::dropIfExists('users');
    }
};
