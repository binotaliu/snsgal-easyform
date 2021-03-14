<?php


namespace App\Ecpay\Enum\Logistics;


use Greg0ire\Enum\AbstractEnum;

/**
 * Class HomeVendor
 *
 * Vendors for home delivery
 * 宅配廠商 Enum
 */
class HomeVendor extends AbstractEnum
{
    /** T-Cat Takkyubin 黑貓宅急便（統一速達） */
    const TCAT = 'TCAT';

    /** E-Can 宅配通（大嘴鳥） */
    const ECAN = 'ECAN';
}