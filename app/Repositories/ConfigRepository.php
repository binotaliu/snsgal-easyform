<?php


namespace App\Repositories;


use App\Eloquent\Config;

class ConfigRepository
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $configs = [];

    function __construct(Config $config)
    {
        $this->config = $config;

        // cache
        $configs = $config->all();
        foreach ($configs as $config) {
            $this->configs[$config->key] = unserialize($config->value);
        }
    }

    /**
     * @return array
     */
    function getConfigs()
    {
        return $this->configs;
    }

    function getConfig(string $key)
    {
        return $this->configs[$key];
    }

    function updateConfig(string $key, $value)
    {
        $this->config->whereKey($key)->update([
            'value' => serialize($value)
        ]);
    }

    function addConfig(string $key, $value)
    {
        $this->config->create([
            'key' => $key,
            'value' => serialize($value)
        ]);
    }
}