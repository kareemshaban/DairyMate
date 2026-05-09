<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyMilkMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_milk_meals', function (Blueprint $table) {
            $table->id();
            $table -> string("code");
            $table -> integer("weakly_meal_id");
            $table -> integer("type");
            $table -> timestamp("date") -> useCurrent();
            $table -> integer("supplier_id");
            $table -> decimal("buffalo_weight");
            $table -> decimal("bovine_weight");
            $table -> text("notes");
            $table -> decimal("bonus") -> default(0);
            $table -> decimal("total") -> default(0);
            $table -> integer('hasBonus') -> default(0);
            $table -> integer('isManufactured') -> default(0);
            $table -> integer('car_meal_id') -> default(0);
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
        Schema::dropIfExists('daily_milk_meals');
    }
}
