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
use BADDIServices\ClnkGO\Domains\CPALeadService;
use BADDIServices\ClnkGO\Events\Marketing\CPALeadOfferMail;
use BADDIServices\ClnkGO\Jobs\Marketing\CPALeadOffer;
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
    protected $signature = 'cpa:lead-offers';

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
        private CPALeadService $CPALeadService
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

        try {
            $offers = $this->CPALeadService->fetchCPALeadOffers();

            $offers->chunk(self::CHUNK_SIZE)
                ->each(function (Collection $offers) {
                    $offers
                        ->filter(function (array $offer) {
                            return (
                                Arr::has($offer, ['creatives', 'title', 'description', 'link', 'campid', 'category_name', 'amount', 'button_text'])
                                && in_array($offer['category_name'], CPALeadService::SUPPORTED_OFFER_TYPES)
                                && count($offer['creatives'] ?? []) > 0
                                && floatval($offer['amount'] ?? 0) > 0.25
                                && end($offer['creatives']) !== false
                                && isset(end($offer['creatives'])['url'])
                            );
                        })
                        ->sortBy('amount', SORT_DESC)
                        ->each(function (array $offer) {
                            $passedEmails = CPALeadTracking::query()
                                ->select([CPALeadTracking::EMAIL_COLUMN])
                                ->whereDate(CPALeadTracking::SENT_AT_COLUMN, "<", Carbon::now()->subHours(72))
                                // ->orWhere(CPALeadTracking::IS_UNSUBSCRIBED_COLUMN, 1)
                                ->get()
                                ->pluck([CPALeadTracking::EMAIL_COLUMN])
                                ->toArray();
dd($passedEmails);
                            $email = TwitterUser::query()
                                ->select([TwitterUser::EMAIL_COLUMN])
                                ->whereNotNull(TwitterUser::EMAIL_COLUMN)
                                ->whereNotIn(TwitterUser::EMAIL_COLUMN, $passedEmails)
                                ->get()
                                ->pluck([TwitterUser::EMAIL_COLUMN])
                                ->random();

                            if (! is_string($email) || empty($email)) {
                                return true;
                            }

                            CPALeadOffer::dispatch($email, $offer)
                                ->onQueue('cpa')
                                ->delay(120);

                            $this->info(sprintf('Offer ID %d sent to %s', $offer['campid'], $email));

                            sleep(120);
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