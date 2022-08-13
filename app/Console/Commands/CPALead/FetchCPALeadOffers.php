<?php

namespace App\Console\Commands\CPALead;

use Throwable;
use Illuminate\Console\Command;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Domains\CPAleadService;

class FetchCPALeadOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cpa:lead-offers {--offer-type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch CPA lead offers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private CPAleadService $CPAleadService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Start fetching CPA lead offers");
        $startTime = microtime(true);
        $offerType = ! empty($this->option('offer-type')) ? $this->option('offer-type') : CPAleadService::EMAIL_SUMIT_OFFER_TYPE;

        try {
            $this->CPAleadService->fetchCPALeadOffers($offerType);
        } catch (Throwable $e) {
            AppLogger::error($e, 'command:cpa:lead-offers', ['execution_time' => (microtime(true) - $startTime)]);
            $this->error(sprintf("Error while fetching CPA lead offers: %s", $e->getMessage()));

            return;
        }

        $this->info("Done fetching CPA lead offers");
    }
}