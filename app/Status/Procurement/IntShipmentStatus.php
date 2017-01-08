<?php


namespace App\Status\Procurement;


class IntShipmentStatus
{
    const WAITING_ORDER = 300;
    const WAITING_PACKAGE = 301;
    const SHIPPING = 302;
    const CUSTOMS = 303;
    const COMPLETED = 304;
    const RE_SHIP = 330;
    const PROBLEM = 359;
}
