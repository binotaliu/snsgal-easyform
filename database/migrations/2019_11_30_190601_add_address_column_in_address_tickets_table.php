<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressColumnInAddressTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address_tickets', function (Blueprint $table) {
            $table->json('address')->after('address_type');
            $table->string('receiver_name')->default('')->after('address');
            $table->string('receiver_phone')->default('')->after('receiver_name');

            $table->timestamp('responded_at')->nullable()->after('archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('address_tickets', function (Blueprint $table) {
            $table->dropColumn('responded_at');
            $table->dropColumn('address');
            $table->dropColumn('receiver_phone');
            $table->dropColumn('receiver_name');
        });
    }
}
