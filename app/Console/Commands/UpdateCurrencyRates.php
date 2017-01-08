<?php

namespace App\Console\Commands;

use App\Repositories\CurrencyRepository;
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
     * @var CurrencyRepository
     */
    protected $currencyRepository;

    /**
     * Create a new command instance.
     */
    public function __construct(CurrencyRepository $currencyRepository)
    {
        parent::__construct();

        $this->currencyRepository = $currencyRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rate = $this->currencyRepository->getLatestRate('JPY');
        $this->currencyRepository->updateRate('JPY', $rate);
        $this->info("Rate: {$rate}");
        return;
    }
}
