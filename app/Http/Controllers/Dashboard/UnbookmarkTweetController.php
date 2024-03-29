<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard;

use BADDIServices\ClnkGO\Http\Controllers\DashboardController;
use BADDIServices\ClnkGO\Models\UserFavoriteTweet;


class UnbookmarkTweetController extends DashboardController
{
    public function __invoke(string $id)
    {
        UserFavoriteTweet::query()
            ->where(
                [
                    UserFavoriteTweet::USER_ID_COLUMN   => $this->user->getId(),
                    UserFavoriteTweet::TWEET_ID_COLUMN  => $id,
                ]
            )
            ->delete();

        return redirect()->back();
    }
}