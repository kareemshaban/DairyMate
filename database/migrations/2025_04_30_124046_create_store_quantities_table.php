<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_quantities', function (Blueprint $table) {
            $table->id();
            $table -> integer('store_id');
            $table -> integer('item_id');
            $table -> decimal('opening_quantity');
            $table -> decimal('quantity_in');
            $table -> decimal('quantity_out');
            $table -> decimal('balance');
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
        Schema::dropIfExists('store_quantities');
    }
}
