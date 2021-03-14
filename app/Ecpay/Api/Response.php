<?php

namespace App\Ecpay\Api;

use App\Ecpay\Utils;
use Illuminate\Support\Arr;
use function GuzzleHttp\Psr7\parse_query;

class Response
{
    protected $response;

    protected $code;
    protected $errorMessage = '';
    protected $data = [];

    protected $isValid = false;

    public function __construct(Credential $credential, string $responseBody)
    {
        $this->response = $responseBody;

        [$this->code, $data] = explode('|', $responseBody, 2);

        if ($this->code !== '1') {
            $this->errorMessage = $data;
            return;
        }

        $this->data = parse_query($data);

        if (!array_key_exists('CheckMacValue', $this->data)) {
            $this->isValid = false;
        }

        $receivedHash = $this->data['CheckMacValue'];
        $expectedHash = Utils::getCheckMacValue($credential, Arr::except($this->data, 'CheckMacValue'));

        $this->isValid = $receivedHash === $expectedHash;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string|null $key
     * @param null $default
     * @return mixed
     */
    public function getData(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->data;
        }

        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }
}
