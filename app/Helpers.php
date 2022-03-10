<?php

use Carbon\Carbon;

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

/**
 * https://ielts.com.au/australia/prepare/article-how-to-write-the-date-correctly
 */
function extractDate(string $text): ?string
{
    $text = strtolower($text);

    $allRegex = [
        "/[0-9]{2}/[0-9]{2}/[0-9]{4}/i",
        "/[0-9]{2}.[0-9]{2}.[0-9]{4}/i",
        "/[0-9]{2}.[0-9]{2}.[0-9]{2}/i",
        "/[0-9]{2}-[0-9]{2}-[0-9]{4}/i",
    ];

    foreach($allRegex as $regex) {
        preg_match_all($regex, $text, $dateMatches);
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
}