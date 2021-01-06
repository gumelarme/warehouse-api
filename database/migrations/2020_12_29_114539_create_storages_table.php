<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storages', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->bigInteger('warehouse_id')->unsigned();
            $table->bigInteger('goods_id')->unsigned();
            $table->timestamps();

            $table->foreign('warehouse_id')
                  ->references('id')
                ->on('warehouses');


            $table->foreign('goods_id')
                  ->references('id')
                ->on('goods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storages');
    }
}
