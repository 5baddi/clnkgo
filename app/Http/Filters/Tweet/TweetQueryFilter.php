<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Filters\Tweet;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use BADDIServices\ClnkGO\Models\Tweet;
use BADDIServices\ClnkGO\Models\RequestAnswer;
use BADDIServices\ClnkGO\Http\Filters\QueryFilter;
use BADDIServices\ClnkGO\Models\TwitterUser;
use BADDIServices\ClnkGO\Models\UserFavoriteTweet;

class TweetQueryFilter extends QueryFilter
{
    public function author(?int $authorId = null) 
    {
        if (is_null($authorId)) {
            return;
        }

        $this->builder
            ->where(Tweet::AUTHOR_ID_COLUMN, $authorId);
    }
    
    public function term(?string $term = null) 
    {
        if (blank($term)) {
            return;
        }

        $this->builder
            ->whereRaw(sprintf("LOWER(%s) like ?", Tweet::TEXT_COLUMN), ["%{$term}%"])
            ->orWhereRaw(sprintf("LOWER(%s) like ?", Tweet::EMAIL_COLUMN), ["%{$term}%"])
            ->orWhereHas("author", function ($author) use ($term) {
                return $author
                    ->whereRaw(sprintf("LOWER(%s) like ?", TwitterUser::EMAIL_COLUMN), ["%{$term}%"])
                    ->orWhereRaw(sprintf("LOWER(%s) like ?", TwitterUser::NAME_COLUMN), ["%{$term}%"])
                    ->orWhereRaw(sprintf("LOWER(%s) like ?", TwitterUser::DESCRIPTION_COLUMN), ["%{$term}%"])
                    ->orWhereRaw(sprintf("LOWER(%s) like ?", TwitterUser::WEBSITE_COLUMN), ["%{$term}%"]);
            });
    }
    
    public function category(?string $category = null) 
    {
        if (blank($category) || $category === "all") {
            return;
        }

        $categories = explode(',', $category);

        foreach($categories as $index => $word) {
            if ($index === 0) {
                $this->builder
                    ->whereRaw(sprintf("LOWER(%s) like ?", Tweet::TEXT_COLUMN), ["%{$word}%"]);

                continue;
            }

            $this->builder
                ->orWhereRaw(sprintf("LOWER(%s) like ?", Tweet::TEXT_COLUMN), ["%{$word}%"]);
        }
    }
    
    public function match(?string $filter = null) 
    {
        if (blank($filter)) {
            return;
        }

        /** @var AuthManager */
        $authManager = app(AuthManager::class);

        /** @var User */
        $user = $authManager->user();

        if ($filter === "keywords") {
            $this->filterByUserKeywords($user);
        }
        
        if ($filter === "answered") {
            $this->builder->whereHas("answers", function ($answer) use ($user) {
                return $answer
                    ->where(RequestAnswer::USER_ID_COLUMN, $user->getId());
            });
        }

        if ($filter === "bookmarked") {
            $this->builder->whereIn(Tweet::ID_COLUMN, $user->favorites->pluck(UserFavoriteTweet::TWEET_ID_COLUMN)->toArray());
        }
    }

    public function getDefaultSortField(): string
    {
        return "-published_at";
    }

    public function sort(?string $value = null) 
    {
        if ($value === "last24hrs") {
            return $this->builder
                ->whereDate(Tweet::PUBLISHED_AT_COLUMN, '>', Carbon::now()->subHours(24))
                ->whereDate(Tweet::PUBLISHED_AT_COLUMN, '<=', Carbon::now())
                ->where(function ($query) {
                    $query->whereDate(Tweet::DUE_AT_COLUMN, '>=', Carbon::now())
                        ->orWhereNull(Tweet::DUE_AT_COLUMN);
                })
                ->orderBy(Tweet::PUBLISHED_AT_COLUMN, 'DESC');
        }
        
        if ($value === "keywordmatch") {
             /** @var AuthManager */
            $authManager = app(AuthManager::class);

            /** @var User */
            $user = $authManager->user();
            
            return $this->filterByUserKeywords($user);
        }

        return parent::sort($value);
    }

    private function filterByUserKeywords(User $user)
    {
        $keywords = $user->getKeywords() ?? [];

        $this->builder
            ->whereDate(Tweet::PUBLISHED_AT_COLUMN, '>', Carbon::now()->subDays(10))
            ->where(function ($query) {
                $query->whereDate(Tweet::DUE_AT_COLUMN, '>=', Carbon::now())
                    ->orWhereNull(Tweet::DUE_AT_COLUMN);
            });

        foreach($keywords as $index => $keyword) {
            if ($index === 0) {
                $this->builder
                    ->whereRaw(sprintf("LOWER(%s) like ?", Tweet::TEXT_COLUMN), ["%{$keyword}%"]);
            }

            $this->builder
                ->orWhereRaw(sprintf("LOWER(%s) like ?", Tweet::TEXT_COLUMN), ["%{$keyword}%"]);
        }

        $this->builder
            ->orderBy(Tweet::PUBLISHED_AT_COLUMN, 'DESC');
    }
}