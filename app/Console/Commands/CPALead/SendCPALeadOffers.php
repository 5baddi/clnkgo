<?php

namespace App\Console\Commands\CPALead;

use Throwable;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Domains\NewsService;
use BADDIServices\ClnkGO\Jobs\Marketing\CPALeadOffer;
use BADDIServices\ClnkGO\Models\Marketing\MailingList;

class SendCPALeadOffers extends Command
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
    protected $description = 'Send CPA lead offers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private NewsService $newsService
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
            $articles = $this->newsService->getTopHeadlines();

            $ignoredEmails = MailingList::query()
                ->select([MailingList::EMAIL_COLUMN])
                // ->whereDate(MailingList::SENT_AT_COLUMN, ">=", Carbon::now()->subDay())
                // ->orWhere(MailingList::IS_UNSUBSCRIBED_COLUMN, 1)
                ->where(MailingList::IS_ACTIVE_COLUMN, 1)
                ->get()
                ->pluck([MailingList::EMAIL_COLUMN])
                ->toArray();

            $targetedEmails = MailingList::query()
                ->select([MailingList::EMAIL_COLUMN])
                ->whereNotNull(MailingList::EMAIL_COLUMN)
                ->whereNotIn(MailingList::EMAIL_COLUMN, $ignoredEmails)
                ->get();

            $targetedEmails->chunk(self::CHUNK_SIZE)
                ->each(function (Collection $emails) use ($articles) {
                    $emails->each(function (MailingList $mailingList) use ($articles) {
                        CPALeadOffer::dispatch($mailingList->getEmail(), $articles->random())
                                ->onQueue('cpa')
                                ->delay(120);

                            $this->info(sprintf('Offer sent to %s', $mailingList->getEmail()));
                    });
                });
            
        } catch (Throwable $e) {
            AppLogger::error($e, 'command:cpa:lead-offers', ['execution_time' => (microtime(true) - $startTime)]);
            $this->error(sprintf("Error while fetching CPA lead offers: %s", $e->getMessage()));

            return;
        }

        $this->info("Done fetching CPA lead offers");
    }
}