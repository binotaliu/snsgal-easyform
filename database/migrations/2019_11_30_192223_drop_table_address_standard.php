<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTableAddressStandard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('addresses_standard');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('address_cvs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_id')->unsigned()->unique();
            $table->string('receiver');
            $table->integer('postcode');
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
}
