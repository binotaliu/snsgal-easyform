<?php


namespace App\Services\ECPay;


use Binota\ECPay\ECPay;
use Binota\ECPay\Logistics\Ticket as LogisticsTicket;
use ECPayLogistics;

class ECPayLogisticsService
{
    protected $ecpayTicket;

    function __construct()
    {
    }

    public function createTicket(string $id, int $time = 0): LogisticsTicket
    {
        if ($time == 0) $time = time();
        return ECPayLogistics::CreateTicket($id, $time);
    }

    public function printTicket(string $vendor, string $ecpayId, string $ticketId, $validation = ''): string
    {
        $print = ECPayLogistics::PrintTicket();
        $print = $print->setVendor($vendor)
            ->useC2C()
            ->setShipmentId($ticketId)
            ->setEcpayId($ecpayId);
        if (!empty($validation)) {
            $print = $print->setValidation($validation);
        }
        $print = $print->print();
        return $print->makeForm();
    }
}