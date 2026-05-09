<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarDailyMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_daily_meals', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->timestamp('date');
            $table->integer('car_meal_id');
            $table->integer('type');
            $table->integer('supplier_id');

            $table->decimal('weight');
            $table->decimal('price');
            $table->decimal('total');
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
        Schema::dropIfExists('car_daily_meals');
    }
}
