<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesCvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses_cvs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_id')->unsigned();

            $table->string('receiver');
            $table->string('phone');
            $table->string('vendor'); //Logistics Sub Type
            $table->integer('store')->unsigned(); //Store ID

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('addresses_cvs');
    }
}
