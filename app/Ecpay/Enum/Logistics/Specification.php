<?php


namespace App\Ecpay\Enum\Logistics;


use Greg0ire\Enum\AbstractEnum;

/**
 * Class Specification
 *
 * Specification for the box of home delivery
 * 宅配用外箱規格
 */
final class Specification extends AbstractEnum
{
    /** 60公分外箱 */
    const CM60 = '0001'; //60cm

    /** 90公分外箱 */
    const CM90 = '0002'; //90cm

    /** 120公分外箱 */
    const CM120 = '0003'; //120cm

    /** 150公分外箱 */
    const CM150 = '0004'; //150cm
}