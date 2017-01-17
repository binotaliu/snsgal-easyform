<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Eloquent\Shipment\Address\Request;
use Ramsey\Uuid\Uuid;

class RequestTest extends TestCase
{
    use DatabaseMigrations;

    public function testRw() {
        $expected = 10;

        //Insert 10 rows
        for ($i = 0; $i < $expected; $i++) {
            Request::create([
                'title' => 'test - ' . $i,
                'description' => 'test - ' . $i,
                'address_type' => ($i % 2) ? 'cvs' : 'standard',
                'token' => Uuid::uuid1()
            ]);
        }

        $actual = Request::all()->count();
        $this->assertEquals($expected, $actual);
    }

    public function testUpdate() {
        //Seeding
        $expected = 'UpdAte TeST';

        $request = factory(Request::class)->create();
        $request->title = $expected;
        $request->save();

        $actual = Request::first()->title;
        $this->assertEquals($expected, $actual);
    }

    public function testDelete() {
        //Seeding
        $expected = 10;
        factory(Request::class, $expected)->create();
        $actual = Request::all()->count();
        $this->assertEquals($expected, $actual);

        --$expected;
        Request::first()->delete();
        $actual = Request::all()->count();
        $this->assertEquals($expected, $actual);
    }
}
