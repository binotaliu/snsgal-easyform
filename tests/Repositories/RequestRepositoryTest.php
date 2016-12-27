<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RequestRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var App\Repositories\RequestRepository
     */
    protected $requestRepository;

    public function setUp()
    {
        parent::setUp();

        $this->requestRepository = resolve('App\Repositories\RequestRepository');
    }

    /**
     * @param Int $times
     * @return \App\Eloquent\Address\Request
     */
    public function createRequests($times = 1)
    {
        return factory(App\Eloquent\Address\Request::class, $times)->create();
    }

    public function testCount()
    {
        $expected = 27;
        $this->createRequests($expected);

        $actual = $this->requestRepository->count();
        $this->assertEquals($expected, $actual);
    }

    public function testGetPage()
    {
        // for 17 requests, page 1 have 15 items, page 2 have 2
        $expectedPage1 = 15;
        $expectedPage2 = 2;
        $this->createRequests($expectedPage1 + $expectedPage2);

        $actual = $this->requestRepository->getPage(1)->count();
        $this->assertEquals($expectedPage1, $actual);

        $actual = $this->requestRepository->getPage(2)->count();
        $this->assertEquals($expectedPage2, $actual);
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
        $actual = App\Eloquent\Address\Request::all()->count();

        $this->assertEquals($expected, $actual);
    }

    public function testUpdateProfile()
    {
        $excepted = 'Naganohara';
        $user = factory(\App\User::class)->create();

        $this->requestRepository->updateProfile($user->id, [
            'name' => $excepted,
            'phone' => '0987654321',
            'postcode' => '10748',
            'address' => '3-5-7 Kinoue, Donjina City'
        ]);

        $actual = \App\User::find($user->id)->get()->requestProfile->name;
        $this->assertEquals($expected, $actual);
    }
}
