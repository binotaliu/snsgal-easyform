<?php


namespace App\Ecpay\Enum\Logistics;


use Greg0ire\Enum\AbstractEnum;

/**
 * Class Distance
 *
 * Distance when using home delivery
 * 宅配用距離
 */
final class Distance extends AbstractEnum
{
    /** 同縣市 */
    const SAME = '00'; //同縣市

    /** 外縣市 */
    const OTHER = '01'; //外縣市

    /** 離島 */
    const ISLAND = '02'; //離島
}