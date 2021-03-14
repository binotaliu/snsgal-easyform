<?php


namespace App\Ecpay\Enum\Logistics;


use Greg0ire\Enum\AbstractEnum;

/**
 * Class Temperature
 *
 * Temperature Enum for home delivery
 * 宅配溫層Enum
 */
final class Temperature extends AbstractEnum
{
    /** 常溫 */
    const NORMAL = '0001';

    /** 冷藏 */
    const REFRIGERATION = '0002'; //冷藏

    /** 冷凍 */
    const FREEZE = '0003'; //冷凍
}