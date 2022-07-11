<?php

namespace App\Console\Commands;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use BADDIServices\ClnkGO\App;
use Illuminate\Console\Command;
use BADDIServices\ClnkGO\AppLogger;
use Illuminate\Support\Facades\Event;
use BADDIServices\ClnkGO\Models\Tweet;
use Illuminate\Database\Eloquent\Collection;
use BADDIServices\ClnkGO\Events\NewRequestMail;

class MailUserWhenThereNewRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:new-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail to user when there\'s new request by keywords';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Start sending new requets mails");
        $startTime = microtime(true);

        try {
            User::query()
                ->with(['subscription'])
                ->select([User::ID_COLUMN, User::EMAIL_COLUMN, User::KEYWORDS_COLUMN])
                ->where(User::IS_SUPERADMIN_COLUMN, false)
                ->chunkById(App::CHUNK_SIZE, function (Collection $users) {
                    $users->each(function (User $user) {
                        if (! $user->subscription->isActive()) {
                            return true;
                        }

                        $keywords = $user->getKeywords();
                        if (count($keywords) === 0) {
                            return true;
                        }

                        /** @var \Illuminate\Database\Eloquent\Builder */
                        $query = Tweet::query()
                            ->whereDate(Tweet::CREATED_AT_COLUMN, ">=", Carbon::now()->subHour())
                            ->where(Tweet::TEXT_COLUMN, "like", "%{$keywords[0]}%");

                        unset($keywords[0]);

                        foreach($keywords as $keyword) {
                            $query = $query->orWhere(Tweet::TEXT_COLUMN, "like", "%{$keyword}%");
                        }

                        $tweet = $query->get()
                            ->shuffle()
                            ->first();

                        if ($tweet instanceof Tweet) {
                            Event::dispatch(new NewRequestMail($user->getId(), $tweet->getId()));
                        }
                    });
                });
        } catch (Throwable $e) {
            AppLogger::error($e, 'command:mail:new-request', ['execution_time' => (microtime(true) - $startTime)]);
            $this->error(sprintf("Error while sending new requets mails: %s", $e->getMessage()));

            return;
        }

        $this->info("Done sending new requets mails");
    }
}
