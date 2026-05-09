<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTransformDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_transform_docs', function (Blueprint $table) {
            $table->id();
            $table -> string('bill_number');
            $table -> timestamp('date');
            $table -> integer('from_store');
            $table -> integer('to_store');
            $table -> text('notes');
            $table -> integer('state');
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
        Schema::dropIfExists('item_transform_docs');
    }
}
