<?php

namespace App\Observers;

use App\Models\AddressTicket;
use App\Utils\UrlSafeBase64;
use Ramsey\Uuid\Uuid;

class AddressRequestObserver
{
    public function creating(\App\Models\AddressTicket $request)
    {
        if ($request->token === null) {
            $request->token = UrlSafeBase64::btoa(Uuid::uuid4()->getBytes());
        }

        $request->address = '{}';
    }
}
