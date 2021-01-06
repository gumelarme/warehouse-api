<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_outs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('storage_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('quantity');
            $table->string('description', 300)->nullable();
            $table->timestamps();

            $table->foreign('storage_id')
                  ->references('id')
                ->on('storages');

            $table->foreign('user_id')
                  ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_outs');
    }
}
