<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Eloquent\Shipment\Address\Request;
use App\Eloquent\Shipment\Address\Cvs as CvsAddress;

class CvsTest extends TestCase
{
    use DatabaseMigrations;

    function createRequest() {
        return factory(Request::class)->states('cvs')->create();
    }

    function createAddress($request) {
        return CvsAddress::create([
            'request_id' => $request->id,
            'receiver' => 'Naganohara',
            'vendor' => 'unimart',
            'store' => '00123',
            'phone' => '886912345678'
        ]);
    }

    function testRw() {
        $expected = 10;

        for ($i = 0; $i < $expected; $i++) {
            $this->createAddress($this->createRequest());
        }

        $actual = CvsAddress::all()->count();
        $this->assertEquals($expected, $actual);
    }

    function testRequestRelation() {
        $request = $this->createRequest();
        $address = $this->createAddress($request);

        $expected = $address->receiver;
        $actual = $request->cvs_address->receiver;

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
