<?php

namespace App\Observers\Shipment;

use App\Models\Shipment\AddressTicket;
use App\Utils\UrlSafeBase64;
use Ramsey\Uuid\Uuid;

class AddressRequestObserver
{
    public function creating(AddressTicket $request)
    {
        if ($request->token === null) {
            $request->token = UrlSafeBase64::btoa(Uuid::uuid4()->getBytes());
        }

        $request->address = '{}';
    }
}
