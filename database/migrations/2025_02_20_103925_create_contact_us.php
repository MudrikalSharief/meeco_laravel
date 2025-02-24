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
        Schema::create('contact_us', function (Blueprint $table) {
            $table->id('ticket_id');
            $table->string('ticket_reference')->unique();
            $table->string('email');
            $table->enum('category', ['Login Issue', 'Conversion Problem', 'Reveiwer Problem', 'Quiz Problem', 'Others']);
            $table->string('subject');
            $table->text('question');
            $table->json('upload')->nullable(); // To handle multiple pictures
            $table->enum('status', ['Pending', 'Responded', 'Closed'])->default('Pending');
            $table->timestamp('date_created')->useCurrent();
            $table->timestamp('last_post')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();
        });

        Schema::create('replies', function (Blueprint $table) {
            $table->id('reply_id');
            $table->foreignId('ticket_id')->constrained('contact_us', 'ticket_id')->onDelete('cascade');
            $table->text('reply_user_question');
            $table->json('reply_user_upload')->nullable();
            $table->timestamps();
        });

        Schema::create('admin_replies', function (Blueprint $table) {
            $table->id('admin_reply_id');
            $table->foreignId('ticket_id')->constrained('contact_us', 'ticket_id')->onDelete('cascade');
            $table->text('reply_admin_question');
            $table->json('reply_admin_upload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_replies');
        Schema::dropIfExists('replies');
        Schema::dropIfExists('contact_us');
    }
};
