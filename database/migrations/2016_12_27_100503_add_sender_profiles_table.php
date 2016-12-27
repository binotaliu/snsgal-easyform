<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSenderProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sender_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->unique();

            $table->string('name');
            $table->string('phone', 10);
            $table->integer('postcode')->unsigned();
            $table->string('address');

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
        Schema::drop('sender_profiles');
    }
}
