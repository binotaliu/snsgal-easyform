<?php

namespace App\Ecpay\Api;

use App\Ecpay\Utils;
use Illuminate\Support\Arr;

final class Response
{
    protected string $response;

    protected string $code;
    protected string $errorMessage = '';
    protected array $data = [];

    protected bool $isValid = false;

    public function __construct(Credential $credential, string $responseBody)
    {
        $this->response = $responseBody;

        [$this->code, $data] = explode('|', $responseBody, 2);

        if ($this->code !== '1') {
            $this->errorMessage = $data;
            return;
        }

        parse_str($data, $this->data);

        if (!array_key_exists('CheckMacValue', $this->data)) {
            $this->isValid = false;
        }

        $receivedHash = $this->data['CheckMacValue'];
        $expectedHash = Utils::getCheckMacValue($credential, Arr::except($this->data, 'CheckMacValue'));

        $this->isValid = $receivedHash === $expectedHash;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getData(?string $key = null, $default = null): mixed
    {
        if ($key === null) {
            return $this->data;
        }

        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $default;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }
}
