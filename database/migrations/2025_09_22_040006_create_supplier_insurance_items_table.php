<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierInsuranceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_insurance_items', function (Blueprint $table) {
            $table->id();
            $table -> integer('insurance_id');
            $table -> integer('item_id');
            $table -> double('quantity', 20, 2) -> default(0);
            $table -> double('weight', 20, 2) -> default(0);
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
        Schema::dropIfExists('supplier_insurance_items');
    }
}
