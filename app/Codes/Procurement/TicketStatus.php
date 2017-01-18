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
            self::WAITING_CHECK => [
                'name' => trans('codes.procurement.ticket.waiting_check'),
                'color' => 'warning'
            ],
            self::WAITING_FIRST_PAY => [
                'name' => trans('codes.procurement.ticket.waiting_first_pay'),
                'color' => 'info'
            ],
            self::ORDERING => [
                'name' => trans('codes.procurement.ticket.ordering'),
                'color' => 'warning'
            ],
            self::TRANSFERRING => [
                'name' => trans('codes.procurement.ticket.transferring'),
                'color' => 'warning'
            ],
            self::WAITING_LAST_PAY => [
                'name' => trans('codes.procurement.ticket.waiting_last_pay'),
                'color' => 'info'
            ],
            self::SHIPPING => [
                'name' => trans('codes.procurement.ticket.shipping'),
                'color' => 'info'
            ],
            self::COMPLETED => [
                'name' => trans('codes.procurement.ticket.completed'),
                'color' => 'success'
            ],
            self::INVALID => [
                'name' => trans('codes.procurement.ticket.invalid'),
                'color' => 'active'
            ],
            self::DISPUTED => [
                'name' => trans('codes.procurement.ticket.disputed'),
                'color' => 'danger'
            ]
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