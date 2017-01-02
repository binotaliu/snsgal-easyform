<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Eloquent\Address\Request;
use App\Eloquent\Address\Standard as StandardAddress;

class StandardTest extends TestCase
{
    use DatabaseMigrations;

    function createRequest() {
        return factory(Request::class)->states('standard')->create();
    }

    function createAddress($request) {
        return StandardAddress::create([
            'request_id' => $request->id,
            'receiver' => 'Naganohara',
            'postcode' => '12345',
            'county' => 'Takao City',
            'city' => 'Zuoying District',
            'address1' => '7-12-4 Dokonoku',
            'address2' => '',
            'time' => 0,
            'phone' => '886912345678'
        ]);
    }

    function testRw() {
        $expected = 10;

        for ($i = 0; $i < $expected; $i++) {
            $this->createAddress($this->createRequest());
        }

        $actual = StandardAddress::all()->count();
        $this->assertEquals($expected, $actual);
    }

    function testRequestRelation() {
        $request = $this->createRequest();
        $address = $this->createAddress($request);

        $expected = $address->receiver;
        $actual = $request->standard_address->receiver;

        $this->assertEquals($expected, $actual);
    }

    function testAddressRelation() {
        $request = $this->createRequest();
        $address = $this->createAddress($request);

        $expected = $request->token;
        $actual = $address->request->token;

        $this->assertEquals($expected, $actual);
    }
}
