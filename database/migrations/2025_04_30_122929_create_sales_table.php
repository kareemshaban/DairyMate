<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table -> string("bill_number");
            $table -> timestamp("date") -> useCurrent();
            $table -> integer("client_id");
            $table -> integer("store_id");
            $table -> decimal("total");
            $table -> decimal("discount");
            $table -> decimal("net");
            $table -> text('notes');
            $table -> integer("state") -> default(0);
            $table -> integer("user_ins") -> default(0);
            $table -> integer("user_upd") -> default(0);
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
        Schema::dropIfExists('sales');
    }
}
