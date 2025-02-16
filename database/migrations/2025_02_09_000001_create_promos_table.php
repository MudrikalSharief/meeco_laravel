<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromosTable extends Migration
{
    public function up()
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id('promo_id');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('perks');
            $table->integer('duration');
            $table->longText('features')->nullable();
            $table->text('limitations')->nullable();
            $table->timestamps();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->enum('discount_type', ['percent', 'fixed'])->nullable();
            $table->decimal('percent_discount', 5, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('promos');
    }
}
