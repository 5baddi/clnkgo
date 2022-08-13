<?php

namespace App\Console\Commands\CPALead;

use Throwable;
use Illuminate\Console\Command;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Domains\CPAleadService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FetchCPALeadOffers extends Command
{
    /** @var int */
    const CHUNK_SIZE = 15;

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
            $offers = $this->CPAleadService->fetchCPALeadOffers($offerType);

            $offers->chunk(self::CHUNK_SIZE)
                ->each(function (Collection $offers) {
                    $offers->each(function (array $offer) {
                        if (! Arr::has($offer, 'creatives', 'title', 'description', 'link', 'campid')) {
                            return true;
                        }

                        // TODO: dipatch send offer mail
                    });

                    sleep(600);
                });
        } catch (Throwable $e) {
            AppLogger::error($e, 'command:cpa:lead-offers', ['execution_time' => (microtime(true) - $startTime)]);
            $this->error(sprintf("Error while fetching CPA lead offers: %s", $e->getMessage()));

            return;
        }

        $this->info("Done fetching CPA lead offers");
    }
}