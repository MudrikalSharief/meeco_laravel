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
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->timestamp('date_created')->useCurrent();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
            $table->rememberToken();
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
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
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

        // Create Raw Text table
        Schema::create('raw', function (Blueprint $table) {
            $table->id('raw_id');
            $table->unsignedBigInteger('topic_id');
            $table->foreign('topic_id')->references('topic_id')->on('topics')->onDelete('cascade');
            $table->longText('raw_text');
            $table->timestamps();
        });

        // Create Reviewer Text table
        Schema::create('reviewer', function (Blueprint $table) {
            $table->id('reviewer_id');
            $table->unsignedBigInteger('topic_id');
            $table->foreign('topic_id')->references('topic_id')->on('topics')->onDelete('cascade');
            $table->longText('reviewer_text');
            $table->timestamps();
        });

        // Create Questions table
        Schema::create('questions', function (Blueprint $table) {
            $table->id('question_id');
            $table->unsignedBigInteger('topic_id');
            $table->foreign('topic_id')->references('topic_id')->on('topics')->onDelete('cascade');
            $table->text('question_type');
            $table->string('question_title');
            $table->integer('number_of_question');
            $table->integer('score')->default(0);
            $table->timestamps();
        });

        // Create Multiple Choice table (missing in original migration)
        Schema::create('multiple_choice', function (Blueprint $table) {
            $table->id('multiple_choice_id');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('question_id')->on('questions')->onDelete('cascade');
            $table->longText('question_text');
            $table->text('answer');
            $table->text('user_answer');
            $table->text('A');
            $table->text('B');
            $table->text('C');
            $table->text('D');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multiple_choice');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('reviewer');
        Schema::dropIfExists('raw');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('admin_actions');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('promos');
        Schema::dropIfExists('users');
    }
};
