<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // Check if the 'reviewers' table doesn't exist before creating it
        if (!Schema::hasTable('reviewers')) {
            Schema::create('reviewers', function (Blueprint $table) {
                $table->id('reviewer_id'); // Primary Key (Auto Increment)
                $table->unsignedBigInteger('user_id');  // Must match `users.user_id`
                $table->unsignedBigInteger('topic_id'); // Must match `topics.topic_id`
                $table->longText('reviewer_about');
                $table->longText('reviewer_text');
                $table->timestamps();

                // Correct Foreign Key References
                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
                $table->foreign('topic_id')->references('topic_id')->on('topics')->onDelete('cascade');
            });
        }
    }

    public function down() {
        Schema::dropIfExists('reviewers');
    }
};
