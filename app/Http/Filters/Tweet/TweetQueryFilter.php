<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Filters\Tweet;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use BADDIServices\SourceeApp\Models\Tweet;
use BADDIServices\SourceeApp\Models\RequestAnswer;
use BADDIServices\SourceeApp\Http\Filters\QueryFilter;
use BADDIServices\SourceeApp\Models\UserFavoriteTweet;

class TweetQueryFilter extends QueryFilter
{
    public function term(?string $term = null) 
    {
        if (blank($term)) {
            return;
        }

        $this->builder
            ->whereRaw(sprintf("LOWER(%s) like ?", Tweet::TEXT_COLUMN), ["%{$term}%"]);
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
                    ->where(RequestAnswer::USER_ID_COLUMN, $user->getId())
                    ->first();
            });
        }

        if ($filter === "bookmarked") {
            $this->builder->whereHas("favorite", function ($favorite) use ($user) {
                return $favorite
                    ->where(UserFavoriteTweet::USER_ID_COLUMN, $user->getId())
                    ->first();
            });
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
                ->whereDate(Tweet::DUE_AT_COLUMN, '>=', Carbon::now())
                ->orWhereNull(Tweet::DUE_AT_COLUMN);
        }
        
        if ($value === "keywordmatch") {
             /** @var AuthManager */
            $authManager = app(AuthManager::class);

            /** @var User */
            $user = $authManager->user();
            
            $this->filterByUserKeywords($user);
        }

        return parent::sort($value);
    }

    private function filterByUserKeywords(User $user)
    {
        $keywords = $user->getKeywords() ?? [];

        foreach($keywords as $index => $keyword) {
            if ($index === 0) {
                $this->builder
                    ->whereRaw(sprintf("LOWER(%s) like ?", Tweet::TEXT_COLUMN), ["%{$keyword}%"]);
            }

            $this->builder
                ->orWhereRaw(sprintf("LOWER(%s) like ?", Tweet::TEXT_COLUMN), ["%{$keyword}%"]);
        }
    }
}