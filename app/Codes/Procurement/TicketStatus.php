<?php


namespace App\Codes\Procurement;


use App\Codes\CodesInterface;

class TicketStatus implements CodesInterface
{
    const WAITING_CHECK = 100;
    const WAITING_FIRST_PAY = 101;
    const ORDERING = 102;
    const TRANSFERRING = 103;
    const WAITING_LAST_PAY = 104;
    const SHIPPING = 105;
    const COMPLETED = 106;
    const INVALID = 150;
    const DISPUTED = 159;

    /**
     * @return array
     */
    static public function getCodes()
    {
        return [
            self::WAITING_CHECK => trans('codes.procurement.ticket.waiting_check'),
            self::WAITING_FIRST_PAY => trans('codes.procurement.ticket.waiting_first_pay'),
            self::ORDERING => trans('codes.procurement.ticket.ordering'),
            self::TRANSFERRING => trans('codes.procurement.ticket.transferring'),
            self::WAITING_LAST_PAY => trans('codes.procurement.ticket.waiting_last_pay'),
            self::SHIPPING => trans('codes.procurement.ticket.shipping'),
            self::COMPLETED => trans('codes.procurement.ticket.completed'),
            self::INVALID => trans('codes.procurement.ticket.invalid'),
            self::DISPUTED => trans('codes.procurement.ticket.disputed')
        ];
    }

    /**
     * @param int $code
     * @return string
     */
    static public function getCode(int $code)
    {
        return self::getCodes()[$code];
    }
}