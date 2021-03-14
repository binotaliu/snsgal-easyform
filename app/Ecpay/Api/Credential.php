<?php

namespace App\Ecpay\Api;

class Credential
{
    protected const STAGE_MERCHANT_IDS = ['20000132', '2000933'];

    protected $merchantId;
    protected $hashKey;
    protected $hashIv;

    public function __construct($merchantId, $hashKey, $hashIv)
    {
        $this->merchantId = $merchantId;
        $this->hashKey = $hashKey;
        $this->hashIv = $hashIv;
    }

    public function isStage(): bool
    {
        return in_array((string)$this->merchantId, self::STAGE_MERCHANT_IDS);
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @return mixed
     */
    public function getHashKey()
    {
        return $this->hashKey;
    }

    /**
     * @return mixed
     */
    public function getHashIv()
    {
        return $this->hashIv;
    }
}
