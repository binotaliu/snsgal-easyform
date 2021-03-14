<?php


namespace App\Ecpay\Enum\Logistics;


use Greg0ire\Enum\AbstractEnum;

/**
 * Class VendorType 物流類型Enum
 */
final class VendorType extends AbstractEnum
{
    /** 超商取貨 */
    const CVS = 'CVS';

    /** 宅配 */
    const HOME = 'Home';
}