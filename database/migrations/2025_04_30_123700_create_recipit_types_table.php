<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipitTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipit_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table -> text('description') ;
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
        Schema::dropIfExists('recipit_types');
    }
}
