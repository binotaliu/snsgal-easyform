<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesStandardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses_standard', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_id');

            $table->string('receiver');
            $table->integer('postcode')->unsigined();
            $table->string('county');
            $table->string('city');
            $table->string('address1');
            $table->string('address2');
            $table->string('phone');

            $table->integer('time')->unsigned();

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
        Schema::drop('addresses_standard');
    }
}
