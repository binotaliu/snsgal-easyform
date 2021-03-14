<?php

namespace App\Utils;

class UrlSafeBase64
{
    /**
     * Binary-to-ASCII
     * Encode a binary content into Base64-encoded string
     *
     * @param string $binary
     * @return string|string[]
     */
    public static function btoa(string $binary)
    {
        return str_replace(
            ['+', '/'],
            ['-', '_'],
            rtrim(base64_encode($binary), '=')
        );
    }

    /**
     * Ascii-to-Binary
     * Decode a Base64-encoded string into binary form
     *
     * @param string $ascii
     * @return string|string[]
     */
    public static function atob(string $ascii)
    {
        // restore padding
        switch (strlen($ascii) % 4) {
            /** @noinspection PhpMissingBreakStatementInspection */
            case 2: $ascii .= '=';
            case 3: $ascii .= '=';
        }

        return base64_decode(str_replace(
            ['-', '_'],
            ['+', '/'],
            $ascii
        ));
    }
}