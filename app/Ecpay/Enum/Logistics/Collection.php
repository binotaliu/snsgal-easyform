<?php


namespace App\Ecpay\Enum\Logistics;


use Greg0ire\Enum\AbstractEnum;

/**
 * Class Collection
 *
 * Collection, set collect or don't collect
 * 代收款Enum
 */
class Collection extends AbstractEnum
{
    /** 代收 */
    const YES = 'Y';

    /** 不代收 */
    const NO = 'N';
}