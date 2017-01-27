<?php


namespace App\Codes\Procurement;


use Greg0ire\Enum\AbstractEnum;

class IntShipmentStatus extends AbstractEnum
{
    const WAITING_ORDER = 300;
    const WAITING_PACKAGE = 301;
    const SHIPPING = 302;
    const CUSTOMS = 303;
    const COMPLETED = 304;
    const RE_SHIP = 330;
    const PROBLEM = 359;
}
