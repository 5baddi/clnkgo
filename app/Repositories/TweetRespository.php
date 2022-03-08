<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use BADDIServices\SourceeApp\Models\Tweet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TweetRespository
{
    public function paginate(?int $page = null): LengthAwarePaginator
    {
        return Tweet::query()
            ->with(['author'])
            ->orderBy(Tweet::PUBLISHED_AT_COLUMN, 'desc')
            ->paginate(10, ['*'], 'page', $page);
    }
    
    public function paginateByHashtags(array $hashtags = [], ?int $page = null): LengthAwarePaginator
    {
        return Tweet::query()
            ->with(['author'])
            ->whereIn(Tweet::HASHTAG_COLUMN, array_values($hashtags))
            ->orderBy(Tweet::PUBLISHED_AT_COLUMN, 'desc')
            ->paginate(10, ['*'], 'page', $page);
    }
    
    public function all(): Collection
    {
        return Tweet::query()
            ->get();
    }

    public function findById(string $id): ?Tweet
    {
        return Tweet::query()
            ->find($id);
    }
    
    public function getByHashtag(string $hashtag): Collection
    {
        return Tweet::query()
            ->where([
                Tweet::HASHTAG_COLUMN => $hashtag
            ])
            ->get();
    }
    
    public function getHashtags(): Collection
    {
        return Tweet::query()
            ->select([Tweet::HASHTAG_COLUMN])
            ->distinct()
            ->get();
    }

    public function save(array $attributes): Tweet
    {
        return Tweet::query()
            ->updateOrCreate(
                [Tweet::ID_COLUMN => $attributes[Tweet::ID_COLUMN]],
                $attributes
            );
    }

    public function delete(string $id): bool
    {
        return Tweet::query()
            ->find($id)
            ->delete();
    }
}