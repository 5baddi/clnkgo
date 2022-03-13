<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

use App\Models\User;
use BADDIServices\SourceeApp\App;
use BADDIServices\SourceeApp\Models\RequestAnswer;
use BADDIServices\SourceeApp\Models\Tweet;
use BADDIServices\SourceeApp\Models\UserFavoriteTweet;
use Illuminate\Pagination\LengthAwarePaginator;
use BADDIServices\SourceeApp\Repositories\TweetRespository;

class TweetService extends Service
{
    /** @var TweetRespository */
    private $tweetRespository;

    public function __construct(TweetRespository $tweetRespository)
    {
        $this->tweetRespository = $tweetRespository;
    }

    public function paginate(?int $page = null, ?string $term = null, ?string $sort = null, ?string $category = null, ?string $filter = null, ?User $user = null): LengthAwarePaginator
    {
        $category = strtolower($category);

        $tweets = $this->tweetRespository->search(
            $sort === 'oldest' ? 'asc' : 'desc',
            $term,
            $category !== 'all' ? explode(',', $category) : [],
            $filter === 'keyword' && $user instanceof User ? $user->getKeywords() : [],
            $filter === 'answered'
        );

        $tweets = $tweets->filter(function ($tweet) {
            return ! is_null($tweet->author);
        });

        if ($filter === 'answered' && $user instanceof User) {
            $tweets = $tweets->filter(function ($tweet) use ($user) {
                if ($tweet->answers->count() === 0) {
                    return false;
                }

                return ($tweet->answers
                    ->where(RequestAnswer::TWEET_ID_COLUMN, $tweet->getId())
                    ->where(RequestAnswer::USER_ID_COLUMN, $user->getId())
                    ->first() instanceof RequestAnswer
                );
            });
        }
        
        if ($filter === 'bookmarked' && $user instanceof User) {
            $tweets = $tweets->filter(function ($tweet) use ($user) {
                if ($user->favorite->count() === 0) {
                    return false;
                }

                return ($user->favorite
                    ->where(UserFavoriteTweet::TWEET_ID_COLUMN, $tweet->getId())
                    ->where(UserFavoriteTweet::USER_ID_COLUMN, $user->getId())
                    ->first() instanceof UserFavoriteTweet
                );
            });
        }

        return new LengthAwarePaginator($tweets->forPage($page, App::PAGINATION_LIMIT), $tweets->count(), App::PAGINATION_LIMIT, $page);
    }
    
    public function paginateByHashtags(array $hashtags, ?int $page = null): LengthAwarePaginator
    {
        return $this->tweetRespository->paginateByHashtags($hashtags, $page);
    }

    public function getHashtags(): array
    {
        return $this->tweetRespository
            ->getHashtags()
            ->pluck([Tweet::HASHTAG_COLUMN])
            ->toArray();
    }
    
    public function findById(string $id): ?Tweet
    {
        return $this->tweetRespository
            ->findById($id);
    }
    
    public function count(): int
    {
        return $this->tweetRespository
            ->count();
    }
    
    public function countOfLast24Hours(): int
    {
        return $this->tweetRespository
            ->count();
    }

    public function save(string $hashtag, array $attributes): Tweet
    {
        $attributes[Tweet::HASHTAG_COLUMN] = $hashtag;

        $filteredAttributes = collect($attributes)
            ->filter(function ($value) {
                return $value !== null;
            })
            ->only([
                Tweet::ID_COLUMN,
                Tweet::URL_COLUMN,
                Tweet::HASHTAG_COLUMN,
                Tweet::AUTHOR_ID_COLUMN,
                Tweet::TEXT_COLUMN,
                Tweet::SOURCE_COLUMN,
                Tweet::LANG_COLUMN,
                Tweet::PUBLIC_METRICS_COLUMN,
                Tweet::ENTITIES_COLUMN,
                Tweet::POSSIBLY_SENSITIVE_COLUMN,
                Tweet::PUBLISHED_AT_COLUMN,
                Tweet::WITHHELD_COLUMN,
                Tweet::ATTACHMENTS_COLUMN,
                Tweet::REFERENCED_TWEETS_COLUMN,
                Tweet::IN_REPLY_TO_USER_ID_COLUMN,
                Tweet::CONTEXT_ANNOTATIONS_COLUMN,
                Tweet::GEO_COLUMN,
                Tweet::DUE_AT_COLUMN,
                Tweet::EMAIL_COLUMN,
            ]);

        if ($filteredAttributes->has(Tweet::HASHTAG_COLUMN)) {
            $filteredAttributes->put(Tweet::HASHTAG_COLUMN, strtolower($filteredAttributes->get(Tweet::HASHTAG_COLUMN)));
        }

        return $this->tweetRespository->save($filteredAttributes->toArray());
    }
}