<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table -> timestamp('date') -> useCurrent();
            $table -> string('bill_number');
            $table -> integer('supplier_id');
            $table -> integer('safe_id');
            $table -> decimal('amount');
            $table -> decimal('installment_amount');
            $table -> integer('installment_count');
            $table -> timestamp('start_date') -> useCurrent();
            $table -> integer('remaining_installments');
            $table -> integer('paid_installments');
            $table -> text('notes') ;
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
        Schema::dropIfExists('loans');
    }
}
