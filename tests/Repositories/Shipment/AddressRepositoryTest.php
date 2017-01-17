<?php

use App\Eloquent\Shipment\Address\Request;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddressRepositoryTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @var \App\Repositories\Shipment\AddressRepository
     */
    protected $addressRepository;

    public function setUp()
    {
        parent::setUp();
        $this->addressRepository = resolve('\App\Repositories\Shipment\AddressRepository');
    }

    /**
     * @param String $state
     * @return mixed
     */
    public function createRequest(String $state) {
        return factory(Request::class)->states($state)->create();
    }

    /**
     * @param Request $request
     * @return \App\Eloquent\Shipment\Address\Standard
     */
    public function createStandardAddress(Request $request) {
        $request->responded = true;
        $request->save();
        return \App\Eloquent\Shipment\Address\Standard::create([
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

    /**
     * @param Request $request
     * @return \App\Eloquent\Shipment\Address\Cvs
     */
    public function createCvsAddress(Request $request) {
        $request->responded = true;
        $request->save();
        return \App\Eloquent\Shipment\Address\Cvs::create([
            'request_id' => $request->id,
            'receiver' => 'Naganohara Mio',
            'vendor' => 'djmart',
            'store' => '42687',
            'phone' => '886987654321'
        ]);
    }

    public function testGetStandardAddress() {
        $request = $this->createRequest('standard');
        $address = $this->createStandardAddress($request);

        $expected = $address->receiver;
        $actual = $this->addressRepository->getAddress($request->token)->receiver;

        $this->assertEquals($expected, $actual);
    }

    public function testGetCvsAddress() {
        $request = $this->createRequest('cvs');
        $address = $this->createCvsAddress($request);

        $expected = $address->receiver;
        $actual = $this->addressRepository->getAddress($request->token)->receiver;

        $this->assertEquals($expected, $actual);
    }

    public function testCreateStandardAddress() {
        $request = $this->createRequest('standard');
        $expected = 'Donjina Building';
        $this->addressRepository->createAddress($request->token, [
            'receiver' => 'Naganohara Mio',
            'postcode' => '42687',
            'county' => 'Karen County',
            'city' => 'Karen City',
            'address1' => '6-3-12 Cyumuku',
            'address2' => $expected,
            'time' => 0,
            'phone' => '886987654321'
        ]);

        $actual = $request->standard_address->address2;
        $this->assertEquals($expected, $actual);
    }

    public function testCreateCvsAddress() {
        $request = $this->createRequest('cvs');
        $expected = '42687';
        $this->addressRepository->createAddress($request->token, [
            'receiver' => 'Naganohara Mio',
            'vendor' => 'djmart',
            'store' => $expected,
            'phone' => '886987654321'
        ]);

        $actual = $request->cvs_address->store;
        $this->assertEquals($expected, $actual);
    }

    public function testCreateStandardAddressConflicted() {
        $request = $this->createRequest('standard');
        $data = [
            'receiver' => 'Naganohara Mio',
            'postcode' => '42687',
            'county' => 'Karen County',
            'city' => 'Karen City',
            'address1' => '6-3-12 Cyumuku',
            'address2' => 'Donjina Building',
            'time' => 0,
            'phone' => '886987654321'
        ];

        try {
            // should only create on address, since the two addresses have same request_id.
            $this->addressRepository->createAddress($request->token, $data);
            $this->addressRepository->createAddress($request->token, $data); // this will be abort
        } catch (Exception $exception) {
            // nothing to do
        }

        $expected = 1;
        $actual = \App\Eloquent\Shipment\Address\Standard::all()->count();
        $this->assertEquals($expected, $actual);
    }

    public function testCreateCvsAddressConflicted() {
        $request = $this->createRequest('cvs');
        $data = [
            'receiver' => 'Naganohara Mio',
            'vendor' => 'djmart',
            'store' => '42687',
            'phone' => '886987654321'
        ];

        try {
            // should only create on address, since the two addresses have same request_id.
            $this->addressRepository->createAddress($request->token, $data);
            $this->addressRepository->createAddress($request->token, $data); // this will be abort
        } catch (Exception $exception) {
            // nothing to do
        }

        $expected = 1;
        $actual = \App\Eloquent\Shipment\Address\Cvs::all()->count();
        $this->assertEquals($expected, $actual);
    }

    public function testUpdateStandardAddress() {
        $request = $this->createRequest('standard');
        $this->createStandardAddress($request);

        $expected = 'Yamada';
        $this->addressRepository->updateAddress($request->id, [
            'receiver' => $expected
        ]);

        $actual = $request->standard_address->receiver;
        $this->assertEquals($expected, $actual);
    }

    public function testUpdateCvsAddress() {
        $request = $this->createRequest('cvs');
        $this->createCvsAddress($request);

        $expected = 'Yamada';
        $this->addressRepository->updateAddress($request->id, [
            'receiver' => $expected
        ]);

        $actual = $request->cvs_address->receiver;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteStandardAddress() {
        $request = $this->createRequest('standard');
        $this->createStandardAddress($request);

        $this->addressRepository->removeAddress($request->id);

        $expected = 0;
        $actual = \App\Eloquent\Shipment\Address\Standard::all()->count();
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteCvsAddress() {
        $request = $this->createRequest('cvs');
        $this->createCvsAddress($request);

        $this->addressRepository->removeAddress($request->id);

        $expected = 0;
        $actual = \App\Eloquent\Shipment\Address\Cvs::all()->count();
        $this->assertEquals($expected, $actual);
    }
}
