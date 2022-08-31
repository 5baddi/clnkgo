<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Services;

use BADDIServices\ClnkGO\Models\Tweet;
use Illuminate\Pagination\LengthAwarePaginator;
use BADDIServices\ClnkGO\Http\Filters\QueryFilter;
use BADDIServices\ClnkGO\Repositories\TweetRespository;

class TweetService extends Service
{
    public function __construct(
        private TweetRespository $tweetRespository
    ) {}

    public function paginate(QueryFilter $queryFilter): LengthAwarePaginator
    {
        return $this->tweetRespository->paginate($queryFilter);
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
    
    public function getAuthorTweetsCount(int $authorId, ?int $ignoreTweetId = null): int
    {
        return $this->tweetRespository
            ->getAuthorTweetsCount($authorId, $ignoreTweetId);
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
            $filteredAttributes->put(Tweet::HASHTAG_COLUMN, strtolower((string) $filteredAttributes->get(Tweet::HASHTAG_COLUMN)));
        }

        return $this->tweetRespository->save($filteredAttributes->toArray());
    }
}