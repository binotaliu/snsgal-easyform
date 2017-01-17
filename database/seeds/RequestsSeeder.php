<?php

use Illuminate\Database\Seeder;

class RequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Eloquent\Shipment\Address\Request::class, 50)->create();
    }
}
