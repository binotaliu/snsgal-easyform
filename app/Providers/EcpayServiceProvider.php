<?php

namespace App\Providers;

use App\Ecpay\Api\Credential;
use Illuminate\Support\ServiceProvider;

class EcpayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(\App\Ecpay\Api\Credential::class, function ($app) {
            return new Credential(
                $this->app['config']->get('services.ecpay.merchant_id'),
                $this->app['config']->get('services.ecpay.hash_key'),
                $this->app['config']->get('services.ecpay.hash_iv')
            );
        });
    }
}