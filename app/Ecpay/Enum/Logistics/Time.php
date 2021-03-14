<?php

namespace App\Ecpay\Enum\Logistics;


use Binota\ECPay\Exceptions\ECPaySDKException;
use Greg0ire\Enum\AbstractEnum;

/**
 * Class Time
 *
 * Time Enum for home delivery
 * 宅配用時間Enum
 */
final class Time extends AbstractEnum
{
    /** 早上（8時至12時） */
    const MORNING = 0b0001;

    /** 下午（12時至17時） */
    const NOON = 0b0010;

    /** 晚上（17時至20時） */
    const NIGHT = 0b0100;

    /** 深夜（20時至21時） */
    const LATE_NIGHT = 0b1000;

    /** 所有時段（即 MORNING | NOON | NIGHT | LATE_NIGHT 使用 OR 運算） */
    const ALL = 0b1111;

    /**
     * Convert to the format ECPay required 將時間格式轉換為綠界科技 API 所使用之格式
     * @param int $time
     * @return string
     * @throws ECPaySDKException 無效的時間
     */
    static public function getEcpay(int $time): string
    {
        if ($time > self::ALL || $time <= 0) throw new ECPaySDKException('Invalid time: ' . $time);

        switch ($time) {
            case self::MORNING:
                return '1';
            case self::NOON:
                return '2';
            case self::NIGHT:
                return '3';
            case self::ALL:
                return '4';
            case self::LATE_NIGHT:
                return '5';
            case self::MORNING | self::NOON:
                return '12';
            case self::MORNING | self::NIGHT:
                return '13';
            case self::NOON | self::NIGHT:
                return '23';
            default:
                throw new ECPaySDKException('Invalid time: ' . $time);
        }
    }
}