<?php

namespace App\Http\Controllers\Currency;

use App\Models\CurrencyRate;
use App\Http\Controllers\Controller;

class RateController extends Controller
{
    public function list() {
        $rate = optional(CurrencyRate::latestOf('JPY'))->rate;

        return [
            'jpy' => $rate,
            'timestamp' => time()
        ];
    }
}
