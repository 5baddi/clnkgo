<?php

namespace App\Console\Commands;

use BADDIServices\ClnkGO\App;
use Throwable;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DonatelloZa\RakePlus\RakePlus;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Models\AppSetting;
use BADDIServices\ClnkGO\Models\Tweet;
use Illuminate\Database\Eloquent\Collection;

class UpdateMostUsedKeywords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-most-used-keywords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update most used keywords in requests for the last week';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Start updating most used keywords");
        $startTime = microtime(true);

        try {
            $keywords = collect();

            Tweet::query()
                ->select([Tweet::ID_COLUMN, Tweet::TEXT_COLUMN, Tweet::CREATED_AT_COLUMN])
                ->whereDate(Tweet::CREATED_AT_COLUMN, ">=", Carbon::now()->subWeek())
                ->chunkById(10, function (Collection $tweets) use (&$keywords) {
                    $text = "";

                    $tweets->each(function (Tweet $tweet) use (&$text) {
                        $text .= " {$tweet->text}";
                    });

                    $keywords = $keywords->merge(RakePlus::create($text ?? '')->keywords() ?? []);

                    $keywords = $keywords->filter(function ($value) {
                        return strpos($value, 'mail') === false && ! filter_var($value, FILTER_VALIDATE_EMAIL) && $value !== null && $value !== ' ' && $value !== '' && ! in_array($value, ['pls', '\u', '/', '\\', '’', '\\\\', 'https', '&amp', '-', '_', 'the','to','i','am','is','are','he','she','a','an','and','here','there','can','could','were','has','have','had','been','welcome','of','home','&nbsp;','&ldquo;','words','into','this','there']);
                    });

                    $keywords = $keywords->map(function ($value) {
                        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            return null;
                        }

                        $value = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $value);

                        return strtolower(str_replace(["," ,"." ,";" ,":", "\"", '’', "'", "“","”","(",")", '&amp', "!","?", '#', '@', '\u', '\u', '/', '\\', '\\\\', '-', '_'], '', $value) ?? '');
                    });
                    
                    $keywords = $keywords->filter(function ($value) {
                        return strpos($value, 'mail') === false && ! filter_var($value, FILTER_VALIDATE_EMAIL) && $value !== null && $value !== ' ' && $value !== '' && ! in_array($value, ['pls', '\u', '’', '&amp', 'https', 'the','to','i','am','is','are','he','she','a','an','and','here','there','can','could','were','has','have','had','been','welcome','of','home','&nbsp;','&ldquo;','words','into','this','there']);
                    });
                });

                $keywords = $keywords->unique();

                AppSetting::query()
                    ->updateOrCreate(
                        [AppSetting::KEY_COLUMN => App::APP_MOST_USED_KEYWORDS],
                        [AppSetting::KEY_COLUMN => App::APP_MOST_USED_KEYWORDS, AppSetting::VALUE_COLUMN => json_encode($keywords->toArray())]
                    );
        } catch (Throwable $e) {
            AppLogger::error($e, 'command:mail:app:update-most-used-keywords', ['execution_time' => (microtime(true) - $startTime)]);
            $this->error(sprintf("Error while updating most used keywords: %s", $e->getMessage()));

            return;
        }

        $this->info("Done updating most used keywords");
    }
}