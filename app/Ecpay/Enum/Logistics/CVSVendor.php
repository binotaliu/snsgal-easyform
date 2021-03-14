<?php


namespace App\Ecpay\Enum\Logistics;


use Greg0ire\Enum\AbstractEnum;

/**
 * Class CVSVendor
 *
 * Vendors for CVS delivery
 * 超商配送Enum
 */
class CVSVendor extends AbstractEnum
{
    /** 7-11 統一超商 */
    const UNIMART = 'UNIMART';

    /** FamilyMart 全家便利商店 */
    const FAMI = 'FAMI';

    /** Hi-Life 萊爾富 */
    const HILIFE = 'HILIFE';
}