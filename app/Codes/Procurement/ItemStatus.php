<?php


namespace App\Codes\Procurement;


use App\Codes\CodesInterface;

class ItemStatus implements CodesInterface
{
    const WAITING_CHECK = 200;
    const WAITING_ORDER = 201;
    const ORDERED = 202;
    const COMPLETED = 203;
    const RETURN = 204;
    const RETURNED = 205;
    const PROBLEM = 259;

    /**
     * @return array
     */
    static public function getCodes()
    {
        return [
            self::WAITING_CHECK => [
                'name' => trans('codes.procurement.item.waiting_check'),
                'color' => 'warning'
            ],
            self::WAITING_ORDER => [
                'name' => trans('codes.procurement.item.waiting_order'),
                'color' => 'warning'
            ],
            self::ORDERED => [
                'name' => trans('codes.procurement.item.ordered'),
                'color' => 'primary',
            ],
            self::COMPLETED => [
                'name' => trans('codes.procurement.item.completed'),
                'color' => 'success'
            ],
            self::RETURN => [
                'name' => trans('codes.procurement.item.return'),
                'color' => 'warning',
            ],
            self::RETURNED => [
                'name' => trans('codes.procurement.item.returned'),
                'color' => 'info',
            ],
            self::PROBLEM => [
                'name' => trans('codes.procurement.item.problem'),
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