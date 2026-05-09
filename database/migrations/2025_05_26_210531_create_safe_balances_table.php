<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSafeBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safe_balances', function (Blueprint $table) {
            $table->id();
            $table->integer('safe_id');
            $table->decimal('opening_balance');
            $table->decimal('income');
            $table->decimal('outcome');
            $table->decimal('balance');
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
        Schema::dropIfExists('safe_balances');
    }
}
