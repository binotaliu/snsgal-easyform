<?php


namespace App\Ecpay\Enum\Logistics;

use Binota\ECPay\Exceptions\ECPaySDKException;
use App\Ecpay\Enum\Logistics\CVSVendor;
use App\Ecpay\Enum\Logistics\HomeVendor;
use Greg0ire\Enum\AbstractEnum;

/**
 * Class LogisticsType
 *
 * Logistics vendors, = LogisticsSubType in ECPay's Manual
 * 物流業者 Enum，= 綠界手冊裡的 LogisticsSubType 物流子類型
 */
final class Vendor extends AbstractEnum
{
    /** 7-11 統一超商 */
    const UNIMART = CVSVendor::UNIMART;

    /** FamilyMart 全家便利商店 */
    const FAMI = CVSVendor::FAMI;

    /** Hi-Life 萊爾富 */
    const HILIFE = CVSVendor::HILIFE;

    /** T-Cat Takkyubin 黑貓宅急便（統一速達） */
    const TCAT = HomeVendor::TCAT;

    /** E-Can 宅配通（大嘴鳥） */
    const ECAN = HomeVendor::ECAN;

    /** 超商取貨的業者列表 */
    const CVS_LIST = [self::UNIMART, self::FAMI, self::HILIFE];

    /** 宅配的業者列表 */
    const HOME_LIST = [self::TCAT, self::ECAN];

    /** 所有類型的業者列表 */
    const ALL_LIST = [self::UNIMART, self::FAMI, self::HILIFE, self::TCAT, self::ECAN];

    /**
     * Convert to C2C mode 取得指定業者的 C2C 型別
     * @param string $vendor
     * @return string
     * @throws ECPaySDKException Given has no C2C mode or unknown vendor 該業者無C2C形態或未知的業者
     */
    static public function getC2C(string $vendor)
    {
        if (!in_array($vendor, self::CVS_LIST)) throw new ECPaySDKException('Given vendor has no C2C mode: ' . $vendor);
        if (!self::isValidValue($vendor)) throw new ECPaySDKException('Unknown vendor: ' . $vendor);

        return $vendor . 'C2C';
    }
}