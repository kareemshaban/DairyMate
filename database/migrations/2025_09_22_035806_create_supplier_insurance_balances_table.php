<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierInsuranceBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_insurance_balances', function (Blueprint $table) {
            $table->id();
            $table -> integer('supplier_id');
            $table -> timestamp('date') -> useCurrent();
            $table -> double('balance', 20, 2) -> default(0);
            $table -> text('notes');
            $table -> integer('state') -> default(0);
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
        Schema::dropIfExists('supplier_insurance_balances');
    }
}
