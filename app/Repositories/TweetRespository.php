<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use BADDIServices\SourceeApp\App;
use BADDIServices\SourceeApp\Models\Tweet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TweetRespository
{
    public function search(string $sort = 'desc', ?string $term = null, array $keywords = [], ?bool $withAnswers = false): Collection
    {
        $relations = ['author'];

        if ($withAnswers === true) {
            $relations[] = 'answers';
        }

        $query = Tweet::query()
            ->with($relations);

        if ($term !== null && strlen($term) > 0) {
            $query = $query->where(Tweet::TEXT_COLUMN, 'like', "%{$term}%");
        } else {
            if (count($keywords) > 0) {
                $query = $query->where(Tweet::TEXT_COLUMN, 'like', "%{$keywords[0]}%");

                unset($keywords[0]);
            }
        }

        if (count($keywords) > 0) {
            foreach($keywords as $keyword) {
                $query = $query->orWhere(Tweet::TEXT_COLUMN, 'like', "%{$keyword}%");
            }
        }

        $query->orderBy(Tweet::PUBLISHED_AT_COLUMN, $sort === 'asc' ? 'asc' : 'desc');

        return $query->get();
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
            ->with(['author', 'media'])
            ->find($id);
    }
    
    public function count(): int
    {
        return Tweet::query()
            ->count();
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