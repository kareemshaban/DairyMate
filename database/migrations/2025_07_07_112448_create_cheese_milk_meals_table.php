<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheeseMilkMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheese_milk_meals', function (Blueprint $table) {
            $table->id();
            $table -> integer('daily_milk_meal_id');
            $table -> integer('cheese_meal_id');
            $table -> integer('user_ins') -> default(0);
            $table -> integer('user_upd') -> default(0);
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
        Schema::dropIfExists('cheese_milk_meals');
    }
}
