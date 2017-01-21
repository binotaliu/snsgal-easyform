<?php

namespace App\Console\Commands;

use App\Repositories\ConfigRepository;
use App\Repositories\CurrencyRepository;
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
     * @var CurrencyRepository
     */
    protected $currencyRepository;

    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * Create a new command instance.
     * @param CurrencyService $currencyService
     * @param CurrencyRepository $currencyRepository
     */
    public function __construct(CurrencyService $currencyService, CurrencyRepository $currencyRepository, ConfigRepository $configRepository)
    {
        parent::__construct();

        $this->currencyService = $currencyService;
        $this->currencyRepository = $currencyRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rate = (float)$this->currencyService->getLatestRate('JPY');
        $this->info("Un-modded rate: {$rate}");
        $rate += $this->configRepository->getConfig('currency.jpy_mod');
        $this->currencyRepository->updateRate('JPY', $rate);
        $this->info("Modded rate: {$rate}, updated");
        return;
    }
}
