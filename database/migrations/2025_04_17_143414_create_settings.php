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
        Schema::dropIfExists('settings');
        Schema::create('settings', function (Blueprint $table) {
            $table->id('id');
            $table->enum('tf_auth_state', ['on', 'off'])->default('off');
            $table->integer('user_min_char')->default(0);
            $table->integer('admin_min_char')->default(0);
            $table->integer('user_spec_char')->default(0);
            $table->integer('admin_spec_char')->default(0);
            $table->integer('user_min_num')->default(0);
            $table->integer('admin_min_num')->default(0);
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
