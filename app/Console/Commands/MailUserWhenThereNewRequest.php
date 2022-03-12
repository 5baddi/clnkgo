<?php

namespace App\Console\Commands;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use BADDIServices\SourceeApp\App;
use Illuminate\Support\Facades\Event;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Events\NewRequestMail;
use BADDIServices\SourceeApp\Models\Tweet;
use Illuminate\Database\Eloquent\Collection;

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
                ->select([User::ID_COLUMN, User::EMAIL_COLUMN, User::KEYWORDS_COLUMN])
                ->where(User::IS_SUPERADMIN_COLUMN, false)
                ->chunkById(App::CHUNK_SIZE, function (Collection $users) {
                    $users->each(function (User $user) {
                        $keywords = $user->getKeywords();
                        if (count($keywords) === 0) {
                            return true;
                        }

                        $tweets = Tweet::query()
                            ->whereDate(Tweet::CREATED_AT, ">=", Carbon::now()->subHour())
                            ->where(Tweet::TEXT_COLUMN, "like", "%{$keywords[0]}%");

                        unset($keywords[0]);

                        foreach($keywords as $keyword) {
                            $tweets = $tweets->orWhere(Tweet::TEXT_COLUMN, "like", "%{$keyword}%");
                        }

                        $tweets->get()
                            ->each(function (Tweet $tweet) use ($user) {
                                Event::dispatch(new NewRequestMail($user, $tweet));
                            });
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
