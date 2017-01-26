<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UseAuth0ColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('password');
            $table->dropColumn('remember_token');

            $table->string('auth0id', 35)->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Nothing to do,
        // the action we do in up cannot be undo.
        Schema::table('users', function ($table) {
            $table->dropColumn('auth0id', 35);

            $table->string('password');
            $table->string('remember_token');
        });
    }
}
