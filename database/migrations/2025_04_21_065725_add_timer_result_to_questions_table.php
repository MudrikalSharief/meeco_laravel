<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('question_text');
            $table->integer('score');
            $table->integer('timer_result')->nullable()->after('score'); // Timer result in seconds
            $table->timestamps(); // Created and updated timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}

class AddTimerResultToQuestionsTable extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->integer('timer_result')->nullable()->after('score'); // Timer result in seconds
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('timer_result');
        });
    }
}
