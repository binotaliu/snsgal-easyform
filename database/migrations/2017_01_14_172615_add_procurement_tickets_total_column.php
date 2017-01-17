<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProcurementTicketsTotalColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procurement_tickets', function (Blueprint $ticket) {
            $ticket->decimal('total', 9, 2)->after('local_shipment_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procurement_tickets', function (Blueprint $ticket) {
            $ticket->dropColumn('total');
        });
    }
}
