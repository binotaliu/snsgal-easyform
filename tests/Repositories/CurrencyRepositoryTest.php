<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CurrencyRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var \App\Repositories\CurrencyRepository
     */
    protected $currencyRepository;

    public function setUp()
    {
        parent::setUp();

        $this->currencyRepository = app('App\Repositories\CurrencyRepository');
    }

    public function testGetLatestRate()
    {
        $rate = $this->currencyRepository->getLatestRate('JPY');

        $this->assertNotNull($rate);
    }

    public function testUpdateRate()
    {
        $expected = 10;
        $rate = $this->currencyRepository->getLatestRate('JPY');

        for ($i = 0; $i < $expected; $i++) {
            $this->currencyRepository->updateRate('JPY', $rate);
        }

        $actual = App\Eloquent\Currency\Rate::all()->count();

        $this->assertEquals($expected, $actual);
    }

    public function testGetRate()
    {
        $expected = 0.8769;
        $rate = $this->currencyRepository->getLatestRate('JPY');
        $this->currencyRepository->updateRate('JPY', $rate);
        $this->currencyRepository->updateRate('JPY', $expected);

        $actual = $this->currencyRepository->getRate('JPY');

        $this->assertEquals($expected, $actual);
    }
}
