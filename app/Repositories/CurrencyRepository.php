<?php


namespace App\Repositories;


use App\Eloquent\Currency\Rate as CurrencyRate;
use App\Services\CurrencyService;

class CurrencyRepository
{
    /**
     * @var CurrencyRate
     */
    protected $currencyRate;

    /**
     * @var CurrencyService
     */
    protected $currencyService;

    public function __construct(CurrencyRate $rate, CurrencyService $currencyService)
    {
        $this->currencyRate = $rate;
        $this->currencyService = $currencyService;
    }

    /**
     * @param string $currency
     * @param float $rate
     * @return CurrencyRate
     */
    public function updateRate(string $currency, float $rate)
    {
        /** @var CurrencyRate $newRate */
        $newRate = new $this->currencyRate();
        $newRate->currency = $currency;
        $newRate->rate = $rate;
        $newRate->save();

        return $newRate;
    }

    /**
     * @param string $currency
     * @return CurrencyRate
     */
    public function getRate(string $currency)
    {
        $rate = $this->currencyRate->whereCurrency($currency)->orderBy('id', 'desc')->first();
        return $rate->rate;
    }
}