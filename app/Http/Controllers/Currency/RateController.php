<?php

namespace App\Http\Controllers\Currency;

use App\Repositories\CurrencyRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RateController extends Controller
{
    /** @var CurrencyRepository */
    protected $currencyRepository;

    function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function list() {
        $jpyRate = $this->currencyRepository->getRate('JPY');

        return [
            'jpy' => $jpyRate
        ];
    }
}
