<?php

namespace Database\Seeders;

use App\Models\AddressTicket;
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
        AddressTicket::factory()->count(50)->create();
    }
}
