<?php


namespace App\Services\ECPay;


use Binota\ECPay\ECPay;
use Binota\ECPay\Logistic\Ticket as LogisticsTicket;

class ECPayLogisticsService
{
    /**
     * @var ECPay
     */
    protected $ecpayClient;

    protected $ecpayTicket;

    function __construct()
    {
        $this->ecpayClient = new ECPay(env('ECPAY_MERCHANTID'), env('ECPAY_HASHKEY'), env('ECPAY_HASHIV'));
    }

    public function createTicket(string $id, int $time = 0): LogisticsTicket
    {
        if ($time == 0) $time = time();
        return $this->ecpayClient->getLogisticFactory()->makeTicket($id, $time);
    }
}