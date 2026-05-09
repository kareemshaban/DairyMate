<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheeseMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheese_meals', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->timestamp('date') -> useCurrent();
            $table->decimal('cheese_price');
            $table->decimal('cream_price');
            $table->decimal('protein_price');
            $table->integer('cheese_milk_meals_id');
            $table->decimal('milk_weight');
            $table->decimal('bovine_price');
            $table->integer('item_id');
            $table->decimal('quantity');
            $table->decimal('weight');
            $table->decimal('disk_weight');
            $table->decimal('disk_cost');
            $table->decimal('productivity');
            $table->decimal('cost_per_cheese_kilo');
            $table->decimal('cream_weight');
            $table->decimal('cream_of_kilo_milk');
            $table->decimal('protein_weight');
            $table->decimal('protein_of_kilo_milk');
            $table->decimal('net');
            $table -> integer('state') -> default(0);
            $table->text('notes') ;
            $table->integer('user_ins') -> default(0);
            $table->integer('user_upd') -> default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cheese_meals');
    }
}
