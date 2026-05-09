<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeaklyMilkMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weakly_milk_meals', function (Blueprint $table) {
            $table->id();
            $table -> timestamp('start_date') -> useCurrent();
            $table -> timestamp('end_date') -> useCurrent();
            $table -> string('code');
            $table -> integer('state');
            $table -> decimal('price_buffalo');
            $table -> decimal('price_bovine');
            $table -> decimal('total_buffalo_weight');
            $table -> decimal('total_bovine_weight');
            $table -> decimal('total_money');
            $table -> integer('number_of_daily_meals');
            $table -> text('notes');
            $table -> timestamp('post_date') -> useCurrent();
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
        Schema::dropIfExists('weakly_milk_meals');
    }
}
