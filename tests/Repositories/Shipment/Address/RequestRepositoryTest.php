<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RequestRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var App\Repositories\Shipment\Address\RequestRepository
     */
    protected $requestRepository;

    public function setUp()
    {
        parent::setUp();

        $this->requestRepository = resolve('App\Repositories\Shipment\Address\RequestRepository');
    }

    /**
     * @param Int $times
     * @return \App\Eloquent\Shipment\Address\Request
     */
    public function createRequests($times = 1)
    {
        return factory(App\Eloquent\Shipment\Address\Request::class, $times)->create();
    }

    public function testGetRequests()
    {
        $expected = 15;
        $this->createRequests($expected);

        $actual = $this->requestRepository->getRequests()->count();
        $this->assertEquals($expected, $actual);
    }

    public function testGetRequest()
    {
        $request = $this->createRequests();
        $expected = $request->title;

        $actual = $this->requestRepository->getRequest($request->token)->title;
        $this->assertEquals($expected, $actual);
    }

    public function testCreateRequest()
    {
        $request = $this->requestRepository->createRequest('Title', 'Description', 'standard');

        $this->assertNotNull($request->title);
    }

    public function testUpdateRequest()
    {
        $expected = 'Awwwwwwwwww';
        $request = $this->createRequests();
        $this->requestRepository->updateRequest($request->token, $expected, $request->description, 123);
        $actual = $this->requestRepository->getRequest($request->token)->title;

        $this->assertEquals($expected, $actual);
    }

    public function testDeleteRequest()
    {
        $expected = 10;
        $this->createRequests($expected);
        $request = $this->createRequests(); // create one more

        $this->requestRepository->removeRequest($request->token);
        $actual = App\Eloquent\Shipment\Address\Request::all()->count();

        $this->assertEquals($expected, $actual);
    }

    public function testUpdateProfile()
    {
        $expected = 'Naganohara';
        $user = factory(\App\Eloquent\User::class)->create();

        $this->requestRepository->updateProfile($user->id, [
            'name' => $expected,
            'phone' => '0987654321',
            'postcode' => '10748',
            'address' => '3-5-7 Kinoue, Donjina City'
        ]);

        $actual = \App\Eloquent\User::find($user->id)->requestProfile->name;
        $this->assertEquals($expected, $actual);
    }
}
