<?php


namespace App\Status\Procurement;


class TicketStatus
{
    const WAITING_CHECK = 100;
    const WAITING_FIRST_PAY = 101;
    const ORDERING = 102;
    const TRANSFERRING = 103;
    const WAITING_LAST_PAY = 104;
    const SHIPPING = 105;
    const COMPLETED = 106;
    const INVALID = 150;
    const DISPUTED = 159;
}