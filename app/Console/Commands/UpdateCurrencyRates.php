<?php

namespace App\Console\Commands;

use App\Models\CurrencyRate;
use App\Repositories\ConfigRepository;
use App\Services\CurrencyService;
use Illuminate\Console\Command;

class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Currency Convert Rate by Taiwan Post (中華郵政)';

    /**
     * @var CurrencyService
     */
    protected $currencyService;

    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * Create a new command instance.
     * @param CurrencyService $currencyService
     */
    public function __construct(CurrencyService $currencyService, ConfigRepository $configRepository)
    {
        parent::__construct();

        $this->currencyService = $currencyService;
        $this->configRepository = $configRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // @todo: use decimal
        $originalRate = CurrencyRate::latestOf('JPY')->rate;

        $this->info("Un-modded rate: {$originalRate}");
        $rate = $originalRate + (float)$this->configRepository->getConfig('currency.jpy_mod');

        $newRate = new CurrencyRate;
        $newRate->currency = 'JPY';
        $newRate->rate = $rate;
        $newRate->save();

        $this->info("Modded rate: {$rate}, updated");
        return 0;
    }
}
