<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Filters\Tweet;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use BADDIServices\SourceeApp\Models\Tweet;
use BADDIServices\SourceeApp\Models\RequestAnswer;
use BADDIServices\SourceeApp\Http\Filters\QueryFilter;
use BADDIServices\SourceeApp\Models\UserFavoriteTweet;

class TweetQueryFilter extends QueryFilter
{
    public function term(?string $term) 
    {
        if (blank($term)) {
            return;
        }

        $this->builder
            ->whereRaw(sprintf("LOWER(%s) like ?", Tweet::TEXT_COLUMN), ["%{$term}%"]);
    }
    
    public function category(?string $category) 
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
    
    public function match(?string $filter) 
    {
        if (blank($filter)) {
            return;
        }

        /** @var AuthManager */
        $authManager = app(AuthManager::class);

        /** @var User */
        $user = $authManager->user();

        if ($filter === "keywords") {
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
}