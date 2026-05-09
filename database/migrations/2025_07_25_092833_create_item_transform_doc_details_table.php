<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTransformDocDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_transform_doc_details', function (Blueprint $table) {
            $table->id();
            $table->integer('doc_id');
            $table->integer('from_item_id');
            $table->integer('to_item_id');
            $table->decimal('quantity');
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
        Schema::dropIfExists('item_transform_doc_details');
    }
}
