<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use BADDIServices\SourceeApp\Models\Tweet;
use Illuminate\Database\Eloquent\Collection;

class TweetRespository
{
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