<?php

namespace App\Console\Commands;

use App\Repositories\ConfigRepository;
use Illuminate\Console\Command;

class InitialConfigs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'configs:initial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial configs for this application';

    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * Create a new command instance.
     * @param ConfigRepository $configRepository
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
        $this->configRepository->addConfig('procurement.minimum_fee', 40);
    }
}
