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

    /**
     * ConfigRepository constructor.
     * @param Config $config
     */
    function __construct(Config $config)
    {
        $this->config = $config;

    }

    private function cache()
    {
        // cache
        $configs = $this->config->all();
        foreach ($configs as $config) {
            $this->configs[$config->key] = unserialize($config->value);
        }
    }

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        if (empty($this->configs)) $this->cache();

        return $this->configs;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getConfig(string $key)
    {
        if (empty($this->configs)) $this->cache();

        return $this->configs[$key];
    }

    /**
     * @param string $key
     * @param $value
     * @return bool
     */
    public function updateConfig(string $key, $value): bool
    {
        return $this->config->whereKey($key)->update([
            'value' => serialize($value)
        ]);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function addConfig(string $key, $value)
    {
        $this->config->create([
            'key' => $key,
            'value' => serialize($value)
        ]);
    }
}