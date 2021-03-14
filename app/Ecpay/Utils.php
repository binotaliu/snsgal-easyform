<?php

namespace App\Ecpay;

use App\Ecpay\Api\Credential;

class Utils
{
    protected const DOTNET_URL_ENCODE_CHARS = [
        '%2d' => '-',
        '%5f' => '_',
        '%2e' => '.',
        '%21' => '!',
        '%2a' => '*',
        '%28' => '(',
        '%29' => ')',
    ];

    public static function getCheckMacValue(Credential $credential, array $data)
    {
        $str = collect($data)
            ->sortKeys()
            ->map(function ($v, $k) {
                return "{$k}={$v}";
            })
            ->implode('&');

        $str = "HashKey={$credential->getHashKey()}&{$str}&HashIV={$credential->getHashIv()}";
        $str = urlencode($str);
        $str = strtolower($str);
        $str = str_replace(
            array_keys(self::DOTNET_URL_ENCODE_CHARS),
            array_values(self::DOTNET_URL_ENCODE_CHARS),
            $str
        );

        return strtoupper(md5($str));
    }
}