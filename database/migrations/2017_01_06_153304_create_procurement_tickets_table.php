<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcurementTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_tickets', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('status');

            $table->string('token', 36)->unique();

            $table->string('name');
            $table->string('email');
            $table->string('contact');
            $table->string('note');

            $table->decimal('rate', 7, 5);

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
        Schema::drop('procurement_tickets');
    }
}
