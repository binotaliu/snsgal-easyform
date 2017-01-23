<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShipmentTicketIdColumnInAddressRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address_requests', function ($table) {
            $table->integer('shipment_status')->nullable()->after('exported');
            $table->string('shipment_ticket_id', 20)->nullable()->after('exported');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('address_requests', function ($table) {
            $table->dropColumn('shipment_status');
            $table->dropColumn('shipment_ticket_id');
        });
    }
}
