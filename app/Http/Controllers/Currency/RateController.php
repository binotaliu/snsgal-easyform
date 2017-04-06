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

    public function list(Request $request) {
        $type = strtolower($request->input('type')) ?? 'json';
        $jpyRate = $this->currencyRepository->getRate('JPY');

        $response = [
            'jpy' => $jpyRate
        ];

        switch ($type) {
            case 'xml':
                return \response()->xml($response);
                break;
            case 'json':
            default:
                return $response;
                break;
        }
    }
}
