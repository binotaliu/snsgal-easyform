<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropProcurementTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('procurement_item_extra_services');
        Schema::drop('procurement_ticket_item_extra_services');
        Schema::drop('procurement_ticket_local_shipment_methods');
        Schema::drop('procurement_ticket_japan_shipment_methods');
        Schema::drop('procurement_item_categories');
        Schema::drop('procurement_ticket_totals');
        Schema::drop('procurement_ticket_japan_shipments');
        Schema::drop('procurement_ticket_items');
        Schema::drop('procurement_tickets');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
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
            $table->decimal('local_shipment_price', 8, 2);
            $table->string('local_shipment_method');
            $table->decimal('total', 9, 2);
            $table->boolean('archived')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('procurement_ticket_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('status');

            $table->integer('category_id')->unsigned();
            $table->integer('ticket_id')->unsigned();
            $table->string('title');
            $table->string('url', 1023);
            $table->decimal('price', 9, 2);
            $table->string('note');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('procurement_ticket_japan_shipments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('ticket_id')->unsigned();

            $table->string('title');
            $table->decimal('price', 8, 2);

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('procurement_ticket_totals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id')->unsigned();
            $table->string('name');
            $table->string('note');
            $table->decimal('price', 9, 2);

            $table->timestamps();
        });
        Schema::create('procurement_item_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->decimal('value', 5, 2);
            $table->integer('lower');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('procurement_ticket_local_shipment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->boolean('show');
            $table->integer('price');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('procurement_ticket_japan_shipment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('price');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('procurement_item_extra_services', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->integer('price');
            $table->boolean('show');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('procurement_ticket_item_extra_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->string('name');
            $table->integer('price');

            $table->timestamps();
        });
    }
}
