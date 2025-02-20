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
            $table->text('question');
            $table->json('upload')->nullable(); // To handle multiple pictures
            $table->timestamp('date_created')->useCurrent();
            $table->timestamp('last_post')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};
