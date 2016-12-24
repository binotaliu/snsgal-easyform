<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UniqueRequestIdInAddressesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses_standard', function ($table) {
            $table->unique('request_id');
        });

        Schema::table('addresses_cvs', function ($table) {
            $table->unique('request_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses_cvs', function ($table) {
            $table->dropUnique(['request_id']);
        });

        Schema::table('addresses_standard', function ($table) {
            $table->dropUnique(['request_id']);
        });
    }
}
