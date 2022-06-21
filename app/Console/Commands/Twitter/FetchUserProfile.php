<?php

namespace App\Console\Commands\Twitter;

use Throwable;
use BADDIServices\ClnkGO\App;
use Illuminate\Console\Command;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Models\TwitterUser;
use Illuminate\Support\Collection;
use BADDIServices\ClnkGO\Domains\TwitterService;
use BADDIServices\ClnkGO\Services\TwitterUserService;

class FetchUserProfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitter:fetch-user-profile {--user-id=} {--user-name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch user profile';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private TwitterService $twitterService,
        private TwitterUserService $twitterUserService
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
        $this->info("Start fetching user profile");
        $startTime = microtime(true);
        $userId = $this->option('user-id');
        $userName = $this->option('user-name');

        try {
            if (! in_array(null, [$this->option('user-id'), $this->option('user-name')])) {
                $userProfile = $this->twitterService->fetchUserProfile($userId, $userName);
                $data = $userProfile->filter(fn ($value) => is_string($value));
    
                $this->table(
                    $data->keys()->toArray(),
                    [$data->toArray()],
                );

                $user = TwitterUser::query()
                    ->select([TwitterUser::ID_COLUMN, TwitterUser::USERNAME_COLUMN])
                    ->where(TwitterUser::ID_COLUMN, $userId)
                    ->first();

                if ($user instanceof TwitterUser) {
                    $this->saveUserProfileBanner($user, $userProfile);
                }
    
                $this->info("Done fetching user profile");
    
                return;
            }

            TwitterUser::query()
                ->select([TwitterUser::ID_COLUMN, TwitterUser::USERNAME_COLUMN])
                ->whereNull(TwitterUser::PROFILE_BANNER_URL_COLUMN)
                ->chunkById(App::CHUNK_SIZE, function (Collection $users) {
                    $users->each(function (TwitterUser $user) {
                        $userProfile = $this->twitterService->fetchUserProfile($user->getId(), $user->username);

                        $this->saveUserProfileBanner($user, $userProfile);
                    });
                });
        } catch (Throwable $e) {
            AppLogger::error($e, 'command:twitter:latest-tweets', ['execution_time' => (microtime(true) - $startTime)]);
            $this->error(sprintf("Error while fetching latest tweets: %s", $e->getMessage()));

            return;
        }

        $this->info("Done fetching user profile");
    }

    private function saveUserProfileBanner(TwitterUser $user, Collection $userProfile): void
    {
        if (! $userProfile->has(TwitterUser::PROFILE_BANNER_URL_COLUMN)) {
            return;
        }

        $this->twitterUserService->save([
            TwitterUser::ID_COLUMN                  => $user->getId(),
            TwitterUser::PROFILE_BANNER_URL_COLUMN  => $userProfile->get(TwitterUser::PROFILE_BANNER_URL_COLUMN),
        ]);
    }
}