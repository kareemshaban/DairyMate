<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipits', function (Blueprint $table) {
            $table->id();
            $table -> timestamp('date') -> useCurrent();
            $table -> string('bill_number');
            $table -> integer('supplier_id');
            $table -> decimal('amount');
            $table -> integer('payment_method');
            $table -> integer('safe_id');
            $table -> text('notes');
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
        Schema::dropIfExists('recipits');
    }
}
