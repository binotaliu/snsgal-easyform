<?php


namespace App\Codes;


interface CodesInterface
{
    /**
     * @return mixed
     */
    static public function getCodes();

    /**
     * @param int $code
     * @return string
     */
    static public function getCode(int $code);
}