<?php

use App\Eloquent\Procurement\Ticket\ShipmentMethod;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShipmentMethodRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var \App\Repositories\Procurement\Ticket\ShipmentMethodRepository
     */
    protected $shipmentMethodRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->shipmentMethodRepository = app('App\Repositories\Procurement\Ticket\ShipmentMethodRepository');
    }

    /**
     * @return ShipmentMethod\Japan
     */
    public function createJapanShipment()
    {
        return factory(ShipmentMethod\Japan::class)->create();
    }

    /**
     * @return ShipmentMethod\Local
     */
    public function createLocalShipment()
    {
        return factory(ShipmentMethod\Local::class)->create();
    }

    public function testGetJapanShipments()
    {
        $expected = 10;
        for ($i = 0; $i < $expected; $i++) {
            $this->createJapanShipment();
        }
        $actual = $this->shipmentMethodRepository->getJapanShipments()->count();

        $this->assertEquals($expected, $actual);
    }

    public function testGetLocalShipments()
    {
        $expected = 10;
        for ($i = 0; $i < $expected; $i++) {
            $this->createLocalShipment();
        }
        $actual = $this->shipmentMethodRepository->getLocalShipments()->count();

        $this->assertEquals($expected, $actual);
    }

    public function testAddJapanShipment()
    {
        $expected = 'Getchu';
        $method = $this->shipmentMethodRepository->addJapanShipment($expected, 490);
        $actual = ShipmentMethod\Japan::find($method->id)->name;

        $this->assertEquals($expected, $actual);
    }

    public function testAddLocalShipment()
    {
        $expected = 'CVS';
        $method = $this->shipmentMethodRepository->addLocalShipment('cvs', $expected, 60, true);
        $actual = ShipmentMethod\Local::find($method->id)->name;

        $this->assertEquals($expected, $actual);
    }

    public function testUpdateJapanShipment()
    {
        $expected = 'Amazon';
        $method = $this->createJapanShipment();
        $this->shipmentMethodRepository->updateJapanShipment($method->id, $expected, $method->price);
        $actual = ShipmentMethod\Japan::find($method->id)->name;

        $this->assertEquals($expected, $actual);
    }

    public function testUpdateLocalShipment()
    {
        $expected = 'CVS';
        $method = $this->createLocalShipment();
        $this->shipmentMethodRepository->updateLocalShipment($method->id, 'cvs', $expected, 60, true);
        $actual = ShipmentMethod\Local::find($method->id)->name;

        $this->assertEquals($expected, $actual);
    }

    public function removeJapanShipment()
    {
        $expected = 10;
        $method = null;
        for ($i = 0; $i <= $expected; $i++) {
            $method = $this->createJapanShipment();
        }
        $this->shipmentMethodRepository->removeJapanShipment($method->id);

        $actual = $this->shipmentMethodRepository->getJapanShipments()->count();

        $this->assertEquals($expected, $actual);
    }

    public function removeLocalShipment()
    {
        $expected = 10;
        $method = null;
        for ($i = 0; $i <= $expected; $i++) {
            $method = $this->createLocalShipment();
        }
        $this->shipmentMethodRepository->removeLocalShipment($method->id);

        $actual = $this->shipmentMethodRepository->getLocalShipments()->count();

        $this->assertEquals($expected, $actual);
    }
}
