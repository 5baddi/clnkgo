<?php

namespace App\Console\Commands\CPALead;

use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use BADDIServices\ClnkGO\AppLogger;
use Illuminate\Support\Facades\Event;
use BADDIServices\ClnkGO\Models\TwitterUser;
use BADDIServices\ClnkGO\Domains\CPAleadService;
use BADDIServices\ClnkGO\Events\Marketing\CPALeadOfferMail;
use BADDIServices\ClnkGO\Models\CPALeadTracking;

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

    /** @var string|null */
    private $offerType;

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
        $this->offerType = ! empty($this->option('offer-type')) ? $this->option('offer-type') : CPAleadService::EMAIL_SUMIT_OFFER_TYPE;

        try {
            $offers = $this->CPAleadService->fetchCPALeadOffers($this->offerType);

            $offers->chunk(self::CHUNK_SIZE)
                ->each(function (Collection $offers) {
                    $offers->each(function (array $offer) {
                        if (! Arr::has($offer, 'creatives', 'title', 'description', 'link', 'campid', 'category_name', 'amount', 'button_text')) {
                            return true;
                        }

                        if (! empty($this->offerType) && $offer['category_name'] !== $this->offerType) {
                            return true;
                        }

                        if (count($offer['creatives'] ?? []) === 0) {
                            return true;
                        }

                        if (floatval($offer['amount'] ?? 0) < 0.25) {
                            return true;
                        }

                        // $sentEmails = CPALeadTracking::query()
                        //     ->select([CPALeadTracking::EMAIL_COLUMN])
                        //     ->whereDate(CPALeadTracking::SENT_AT_COLUMN, "<", Carbon::now()->startOfDay()->toDateTime())
                        //     ->where(CPALeadTracking::IS_UNSUBSCRIBED_COLUMN, 1)
                        //     ->get()
                        //     ->pluck([CPALeadTracking::EMAIL_COLUMN])
                        //     ->toArray();

                        // $emails = TwitterUser::query()
                        //     ->select([TwitterUser::EMAIL_COLUMN])
                        //     ->whereNotNull(TwitterUser::EMAIL_COLUMN)
                        //     ->get();

                        // TODO: dipatch send offer mail
                        Event::dispatch(new CPALeadOfferMail('clnkgo@baddi.info', $offer));;
                    });

                    // sleep(600);
                });
        } catch (Throwable $e) {
            AppLogger::error($e, 'command:cpa:lead-offers', ['execution_time' => (microtime(true) - $startTime)]);
            $this->error(sprintf("Error while fetching CPA lead offers: %s", $e->getMessage()));

            return;
        }

        $this->info("Done fetching CPA lead offers");
    }
}