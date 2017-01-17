<?php


namespace App\Services;


use GuzzleHttp\Client as GuzzleClient;

class CurrencyService
{
    /**
     * @var GuzzleClient
     */
    protected $guzzleClient;

    public function __construct(GuzzleClient $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    public function getLatestRate(string $currency = 'JPY')
    {
        // @TODO: multi currency support
        $html = $this->guzzleClient->get('https://ipost.post.gov.tw/webpost/CSController?cmd=POS1002_1&_SYS_ID=B&_MENU_ID=179&_ACTIVE_ID=183')->getBody()->getContents();
        $matches = [];
        preg_match('/日圓([\w\W]+?\/td>){4}[\w\W]+?(\d\.\d{4})/', $html, $matches);
        $rate = $matches[2];
        return $rate;
    }
}