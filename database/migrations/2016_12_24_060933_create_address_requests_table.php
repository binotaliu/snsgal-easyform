<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->string('token', 36)->unique(); //Token is an RFC 4122 UUID, so it is a 36 characters string. e4eaaaf2-d142-11e1-b3e4-080027620cdd

            //Polymorphic relate to address
            $table->string('address_type'); //Standard or Convini
            $table->integer('address_id')->unsigned()->nullable();

            $table->timestamp('expired_at');
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
        Schema::drop('address_requests');
    }
}
