<?php


namespace App\Repositories;


use App\Eloquent\Currency\Rate as CurrencyRate;
use GuzzleHttp\Client as GuzzleClient;

class CurrencyRepository
{
    /**
     * @var Client
     */
    protected $guzzleClient;

    /**
     * @var CurrencyRate
     */
    protected $currencyRate;

    public function __construct(CurrencyRate $rate, GuzzleClient $guzzleClient)
    {
        $this->currencyRate = $rate;
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param string $currency
     * @return mixed
     */
    public function getLatestRate(string $currency = 'JPY')
    {
        // @TODO: multi currency support
        $html = $this->guzzleClient->get('https://ipost.post.gov.tw/webpost/CSController?cmd=POS1002_1&_SYS_ID=B&_MENU_ID=179&_ACTIVE_ID=183')->getBody()->getContents();
        $matches = [];
        preg_match('/日圓([\w\W]+?\/td>){4}[\w\W]+?(\d\.\d{4})/', $html, $matches);
        $rate = $matches[2];
        return $rate;
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