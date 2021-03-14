<?php

namespace App\Ecpay\Api;

use App\Ecpay\Utils;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Contracts\View\View;

class Request
{
    protected $guzzle;

    /** @var \App\Ecpay\Api\Credential */
    protected $credential;

    protected $data = [];

    public function __construct(Credential $credential, GuzzleClient $guzzle)
    {
        $this->credential = $credential;
        $this->guzzle = $guzzle;
    }

    public function setData($key, $data = null)
    {
        if (is_array($key) && $data === null) {
            $this->data = array_merge($this->data, $key);

            return $this->data;
        }
        if (is_string($key)) {
            return $this->data[$key] = $data;
        }

        throw new \InvalidArgumentException('Argument $key should be a string');
    }

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

    protected function prepareData()
    {
        if ($this->getData('MerchantID') === null) {
            $this->setData('MerchantID', $this->credential->getMerchantId());
        }

        if ($this->getData('CheckMacValue') === null) {
            $this->setData('CheckMacValue', Utils::getCheckMacValue($this->credential, $this->data));
        }
    }

    public function send(string $domain, string $endpoint): Response
    {
        if ($this->credential->isStage()) {
            $domain .= '-stage';
        }

        $this->prepareData();

        // ECPay always returns a 200 response. If it works properly, guzzle should not throw an exception.
        $guzzleResponse = $this->guzzle->post("https://{$domain}.ecpay.com.tw{$endpoint}", ['form_params' => $this->data]);

        return new Response($this->credential, $guzzleResponse->getBody()->getContents());
    }

    public function makeAutoForm(string $domain, string $endpoint): View
    {
        if ($this->credential->isStage()) {
            $domain .= '-stage';
        }

        $this->prepareData();

        // ECPay always returns a 200 response. If it works properly, guzzle should not throw an exception.
        return view('common.auto-form', ['url' => "https://{$domain}.ecpay.com.tw{$endpoint}", 'method' => 'POST', 'data' => $this->data]);
    }
}
