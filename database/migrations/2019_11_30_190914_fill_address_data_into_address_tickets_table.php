<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Schema;

class FillAddressDataIntoAddressTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('addresses_cvs')->orderByDesc('id')->each(function ($row) {
            DB::table('address_tickets')->where(['id' => $row->request_id])->update([
                'receiver_name' => $row->receiver,
                'receiver_phone' => $row->phone,
                'address' => json_encode([
                    'vendor' => $row->vendor,
                    'store' => $row->store,
                ]),
                'responded_at' => $row->created_at,
            ]);
        }, 10);
        DB::table('addresses_standard')->orderByDesc('id')->each(function ($row) {
            DB::table('address_tickets')->where(['id' => $row->request_id])->update([
                'receiver_name' => $row->receiver,
                'receiver_phone' => $row->phone,
                'address' => json_encode([
                    'postcode' => $row->postcode,
                    'county' => $row->county,
                    'city' => $row->city,
                    'address_1' => $row->address1,
                    'address_2' => $row->address2,
                    'delivery_time' => $row->time,
                ]),
                'responded_at' => $row->created_at,
            ]);
        }, 10);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB
            ::table('address_tickets')
            ->orderByDesc('id')
            ->whereNotNull('responded_at')
            ->each(function ($row) {
                $detail = json_decode($row->address, true);
                if ($row->address_type === 'standard') {
                    DB::table('addresses_standard')->insert([
                        'receiver' => $row->receiver_name,
                        'postcode' => $detail['postcode'],
                        'county' => $detail['county'],
                        'city' => $detail['city'],
                        'address1' => $detail['address_1'],
                        'address2' => $detail['address_2'],
                        'phone' => $row->receiver_phone,
                        'time' => $detail['delivery_time'],
                        'created_at' => $row->responded_at,
                        'updated_at' => $row->responded_at,
                    ]);
                } elseif ($row->address_type === 'cvs') {
                    DB::table('addresses_standard')->insert([
                        'receiver' => $row->receiver_name,
                        'vendor' => $detail['vendor'],
                        'store' => $detail['store'],
                        'phone' => $row->receiver_phone,
                        'created_at' => $row->responded_at,
                        'updated_at' => $row->responded_at,
                    ]);
                }
            }, 10);
    }
}
