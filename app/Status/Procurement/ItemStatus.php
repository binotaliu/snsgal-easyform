<?php


namespace App\Status\Procurement;


class ItemStatus
{
    const WAITING_CHECK = 200;
    const WAITING_ORDER = 201;
    const ORDERED = 202;
    const RETURN = 203;
    const RETURNED = 205;
    const PROBLEM = 259;
}