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
        $print = $print->vendor($vendor . 'C2C')
            ->ecpayId($ecpayId)
            ->shipmentId($ticketId);
        if (!empty($validation)) {
            $print = $print->validation($validation);
        }
        $print = $print->print();
        return $print->makeForm();
    }
}