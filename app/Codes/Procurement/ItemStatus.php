<?php


namespace App\Codes\Procurement;


use App\Codes\CodesInterface;

class ItemStatus implements CodesInterface
{
    const WAITING_CHECK = 200;
    const WAITING_ORDER = 201;
    const ORDERED = 202;
    const RETURN = 203;
    const RETURNED = 205;
    const PROBLEM = 259;

    /**
     * @return array
     */
    static public function getCodes()
    {
        return [
            self::WAITING_CHECK => trans('codes.procurement.item.waiting_check'),
            self::WAITING_ORDER => trans('codes.procurement.item.waiting_order'),
            self::ORDERED => trans('codes.procurement.item.ordered'),
            self::RETURN => trans('codes.procurement.item.return'),
            self::RETURNED => trans('codes.procurement.item.returned'),
            self::PROBLEM => trans('codes.procurement.item.problem'),
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