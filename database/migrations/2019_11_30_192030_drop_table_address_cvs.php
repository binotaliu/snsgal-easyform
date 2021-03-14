<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTableAddressCvs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('addresses_cvs');
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
            $table->string('phone');
            $table->string('vendor');
            $table->string('store', 6);

            $table->timestamps();
            $table->softDeletes();
        });
    }
}
