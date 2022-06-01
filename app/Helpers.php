<?php

use BADDIServices\SourceeApp\App;
use BADDIServices\SourceeApp\Models\AppSetting;
use BADDIServices\SourceeApp\Services\AppSettingService;
use Carbon\Carbon;
use Illuminate\Support\Arr;

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

/**
 * https://ielts.com.au/australia/prepare/article-how-to-write-the-date-correctly
 */
if (! function_exists('extractDate')) {
    function extractDate(string $text): ?string
    {
        $text = strtolower($text);

        $allRegex = [
            "[0-9]{2}\/[0-9]{2}\/[0-9]{4}",
            "[0-9]{2}.[0-9]{2}.[0-9]{4}",
            "[0-9]{2}.[0-9]{2}.[0-9]{2}",
            "[0-9]{2}-[0-9]{2}-[0-9]{4}",
            "[0-9]{4}-[0-9]{2}-[0-9]{1,2}",
            "due by (month|january|february|march|april|may|june|july|august|september|october|november|december) [0-9]{1,2}th, [0-9]{4} at [0-9]{1,2}-[0-9]{2}(pm|am)",
            "due (month|january|february|march|april|may|june|july|august|september|october|november|december) [0-9]{1,2}th, [0-9]{4} at [0-9]{1,2}-[0-9]{2}(pm|am)",
            "due on (month|january|february|march|april|may|june|july|august|september|october|november|december) [0-9]{1,2}th, [0-9]{4} at [0-9]{1,2}-[0-9]{2}(pm|am)",
            "due (month|january|february|march|april|may|june|july|august|september|october|november|december)",
            "due by (month|january|february|march|april|may|june|july|august|september|october|november|december)",
            "(today|tomorrow|next week|sunday|monday|tuesday|wednesday|thursday|friday|saturday) at [0-9]{1,2}-[0-9]{2}(pm|am)",
            "(today|tomorrow|next week|sunday|monday|tuesday|wednesday|thursday|friday|saturday) [0-9]{1,2}th, at [0-9]{1,2}-[0-9]{2}(pm|am)",
            "(today|tomorrow|next week|sunday|monday|tuesday|wednesday|thursday|friday|saturday) [0-9]{1,2}th at [0-9]{1,2}-[0-9]{2}(pm|am)",
            "(today|tomorrow|next week|sunday|monday|tuesday|wednesday|thursday|friday|saturday) [0-9]{1,2} at [0-9]{1,2}-[0-9]{2}(pm|am)",
        ];

        try {
            foreach($allRegex as $regex) {
                preg_match_all("/{$regex}/i", $text, $dateMatches);
                if (! is_null($dateMatches) && isset($dateMatches[0]) && ! is_null(array_key_last($dateMatches[0]))) {
                    $date = $dateMatches[0][array_key_last($dateMatches[0])];
        
                    return Carbon::parse($date)->toDateString();
                }
            }
            
            preg_match_all("/\d{2}\/\d{2}\/\d{4}/i", $text, $dateMatches);
            if (! is_null($dateMatches) && isset($dateMatches[0]) && ! is_null(array_key_last($dateMatches[0]))) {
                $date = $dateMatches[0][array_key_last($dateMatches[0])];
        
                return Carbon::parse($date)->toDateString();
            }
            
            preg_match_all("/this year/i", $text, $yearMatches);
            if (! is_null($yearMatches) && isset($yearMatches[0]) && ! is_null(array_key_last($yearMatches[0]))) {
                $year = $yearMatches[0][array_key_last($yearMatches[0])];
        
                return Carbon::parse("last day of December {$year}")->toDateString();
            }
            
            preg_match_all("/this month|january|february|march|april|may|june|july|august|september|october|november|december/i", $text, $monthMatches);
            if (! is_null($monthMatches) && isset($monthMatches[0]) && ! is_null(array_key_last($monthMatches[0]))) {
                $month = $monthMatches[0][array_key_last($monthMatches[0])];
        
                return Carbon::parse("last day of {$month}")->toDateString();
            }
            
            preg_match_all("/today|tomorrow|next week|sunday|monday|tuesday|wednesday|thursday|friday|saturday/i", $text, $dayMatches);
            if (! is_null($dayMatches) && isset($dayMatches[0]) && ! is_null(array_key_last($dayMatches[0]))) {
                $day = $dayMatches[0][array_key_last($dayMatches[0])];
        
                return Carbon::parse("{$day} 23:59:59")->toDateString();
            }

            return null;
        } catch (Throwable $e) {
            // TODO: implement logger
        }

        return null;
    }
}

if (! function_exists('extractWebsite')) {
    function extractWebsite(string $text): ?string 
    {
        /** @var AppSettingService */
        $appSettingService = app(AppSettingService::class);

        $emailsProviders = $appSettingService->get(AppSetting::EMAILS_PROVIDERS_KEY, App::EMAIL_PROVIDERS);
        $domainName = null;

        try {
            if (! filter_var($text, FILTER_VALIDATE_EMAIL)) {
                preg_match('/(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/im', $text ?? '', $emailMatches);

                if (isset($emailMatches[0]) && filter_var($emailMatches[0], FILTER_VALIDATE_EMAIL)) {
                    $text = $emailMatches[0];
                }
            }

            if (filter_var($text, FILTER_VALIDATE_EMAIL)) {
                $domainsNames = explode('@', $text);
                $parsedDomainName = end($domainsNames);

                $isEmailProvider = array_filter($emailsProviders, function ($value) use ($parsedDomainName) {
                    return strpos($parsedDomainName, $value) !== false;
                });

                if (is_string($parsedDomainName) && ! $isEmailProvider) {
                    $domainName = $parsedDomainName;
                }
            }
            
            preg_match('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $text, $matches);
    
            $domainName = $matches[0] ?? null;

            if (is_string($domainName) && filter_var($domainName, FILTER_VALIDATE_URL)) {
                $parsedDomainName = parse_url($domainName);

                if (Arr::has($parsedDomainName, ['host', 'path'])) {
                    $domainName = $parsedDomainName['host'] . $parsedDomainName['path'];
                }
            }
        } catch (Throwable $e) {
            // TODO: implement logger
        }

        return $domainName;
    }
}