<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_meals', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->timestamp('date');
            $table->integer('car_id');
            $table->decimal('tota_weight');
            $table -> decimal('weight_difference');
            $table->integer('state');
            $table->integer('weakly_meal_id');
            $table->text('notes');
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
        Schema::dropIfExists('car_meals');
    }
}
