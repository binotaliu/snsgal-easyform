<?php

namespace App\Ecpay\Api;

final class Credential
{
    protected const STAGE_MERCHANT_IDS = ['20000132', '2000933'];

    protected string $merchantId;
    protected string $hashKey;
    protected string $hashIv;

    public function __construct(string $merchantId, string $hashKey, string $hashIv)
    {
        $this->merchantId = $merchantId;
        $this->hashKey = $hashKey;
        $this->hashIv = $hashIv;
    }

    public function isStage(): bool
    {
        return in_array((string)$this->merchantId, self::STAGE_MERCHANT_IDS);
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getHashKey(): string
    {
        return $this->hashKey;
    }

    public function getHashIv(): string
    {
        return $this->hashIv;
    }
}
