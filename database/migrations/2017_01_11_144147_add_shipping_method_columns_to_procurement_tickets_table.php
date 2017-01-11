<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingMethodColumnsToProcurementTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procurement_tickets', function ($table) {
            $table->string('local_shipment_method')->after('rate');
            $table->decimal('local_shipment_price', 8, 2)->after('rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procurement_tickets', function ($table) {
            $table->dropColumn('local_shipment_method');
            $table->dropColumn('local_shipment_price');
        });
    }
}
