<?php

namespace App\Console\Commands;

use App\Repositories\ConfigRepository;
use Illuminate\Console\Command;

class SetConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'configs:set {key} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set config';

    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * Create a new command instance.
     */
    public function __construct(ConfigRepository $configRepository)
    {
        parent::__construct();

        $this->configRepository = $configRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (array_key_exists($this->argument('key'), $this->configRepository->getConfigs())) {
            $this->configRepository->updateConfig($this->argument('key'), $this->argument('value'));
        } else {
            $this->configRepository->addConfig($this->argument('key'), $this->argument('value'));
        }

        return 0;
    }
}
