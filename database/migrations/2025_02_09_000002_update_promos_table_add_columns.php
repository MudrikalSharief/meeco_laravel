<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePromosTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->string('photo_to_text', 255)->nullable()->after('status');
            $table->integer('photo_limit')->nullable()->after('photo_to_text');
            $table->string('reviewer_generator', 255)->nullable()->after('photo_limit');
            $table->integer('reviewer_limit')->nullable()->after('reviewer_generator');
            $table->string('mock_quiz_generator', 255)->nullable()->after('reviewer_limit');
            $table->integer('mock_quiz_limit')->nullable()->after('mock_quiz_generator');
            $table->string('save_reviewer', 255)->nullable()->after('mock_quiz_limit');
            $table->integer('save_reviewer_limit')->nullable()->after('save_reviewer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn([
                'photo_to_text',
                'photo_limit',
                'reviewer_generator',
                'reviewer_limit',
                'mock_quiz_generator',
                'mock_quiz_limit',
                'save_reviewer',
                'save_reviewer_limit',
            ]);
        });
    }
}