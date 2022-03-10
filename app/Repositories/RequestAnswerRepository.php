<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use BADDIServices\SourceeApp\Models\RequestAnswer;
use Illuminate\Database\Eloquent\Collection;

class RequestAnswerRepository
{
    public function all(): Collection
    {
        return RequestAnswer::query()
            ->get();
    }

    public function findById(string $id): ?RequestAnswer
    {
        return RequestAnswer::query()
            ->find($id);
    }
    
    public function find(string $userId, string $tweetId): ?RequestAnswer
    {
        return RequestAnswer::query()
            ->where(
                [
                    RequestAnswer::USER_ID_COLUMN  => $userId,
                    RequestAnswer::TWEET_ID_COLUMN => $tweetId
                ]
            )
            ->first();
    }

    public function save(array $attributes): RequestAnswer
    {
        return RequestAnswer::query()
            ->updateOrCreate(
                [
                    RequestAnswer::TWEET_ID_COLUMN  => $attributes[RequestAnswer::TWEET_ID_COLUMN],
                    RequestAnswer::USER_ID_COLUMN   => $attributes[RequestAnswer::USER_ID_COLUMN]
                ],
                $attributes
            );
    }

    public function delete(string $id): bool
    {
        return RequestAnswer::query()
            ->find($id)
            ->delete();
    }
}