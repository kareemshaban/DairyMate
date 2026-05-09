<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table -> integer('type');
            $table -> string('name');
            $table -> string('phone');
            $table -> decimal('buffalo_min_limit');
            $table -> decimal('buffalo_max_limit');
            $table -> decimal('bovine_min_limit');
            $table -> decimal('bovine_max_limit');
            $table -> text('address');
            $table -> integer('user_ins') -> default(0);
            $table -> integer('user_upd') -> default(0) ;
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
        Schema::dropIfExists('clients');
    }
}
