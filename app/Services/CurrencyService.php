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
        //$html = $this->guzzleClient->get('https://ipost.post.gov.tw/webpost/CSController?cmd=POS1002_1&_SYS_ID=B&_MENU_ID=179&_ACTIVE_ID=183')->getBody()->getContents();
        //{"header":{"InputVOClass":"com.systex.jbranch.app.server.post.vo.EB060300InputVO","TxnCode":"EB060300","BizCode":"query","StampTime":true,"SupvPwd":"","TXN_DATA":{},"SupvID":"","CustID":"","ApplicationID":"","REQUEST_ID":"","ClientTransaction":true,"DevMode":false,"SectionID":"esoaf"},"body":{"pageCount":50}}
        $response = $this->guzzleClient->request('POST', 'https://ipost.post.gov.tw/pst/EsoafDispatcher', [
            'body' => '{"header":{"InputVOClass":"com.systex.jbranch.app.server.post.vo.EB060300InputVO","TxnCode":"EB060300","BizCode":"query","StampTime":true,"SupvPwd":"","TXN_DATA":{},"SupvID":"","CustID":"","ApplicationID":"","REQUEST_ID":"","ClientTransaction":true,"DevMode":false,"SectionID":"esoaf"},"body":{"pageCount":50}}'
        ]);
        $json = json_decode($response->getBody()->getContents());

        $rate = $json[0]->body->host_rs->ISSUE_RATE_CASH_JP;
        return $rate;
    }
}