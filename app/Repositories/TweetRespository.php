<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use BADDIServices\SourceeApp\App;
use BADDIServices\SourceeApp\Http\Filters\Tweet\TweetQueryFilter;
use Carbon\Carbon;
use BADDIServices\SourceeApp\Models\Tweet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TweetRespository
{
    public function paginate(TweetQueryFilter $queryFilter): LengthAwarePaginator
    {
        return Tweet::query()
            ->with(["author", "answers"])
            ->filter($queryFilter)
            ->paginate(App::PAGINATION_LIMIT, ['*'], "page", $queryFilter->getPage());
    }
    
    public function paginateByHashtags(array $hashtags = [], ?int $page = null): LengthAwarePaginator
    {
        return Tweet::query()
            ->with(["author"])
            ->whereIn(Tweet::HASHTAG_COLUMN, array_values($hashtags))
            ->orderBy(Tweet::PUBLISHED_AT_COLUMN, 'desc')
            ->paginate(App::PAGINATION_LIMIT, ['*'], 'page', $page);
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

    public function last24hRequests(): int
    {
        return Tweet::query()
            ->whereDate(Tweet::PUBLISHED_AT_COLUMN, '>', Carbon::now()->subHours(24))
            ->whereDate(Tweet::PUBLISHED_AT_COLUMN, '<=', Carbon::now())
            ->whereDate(Tweet::DUE_AT_COLUMN, '>=', Carbon::now())
            ->orWhereNull(Tweet::DUE_AT_COLUMN)
            ->count();
    }
    
    public function liveRequests(): int
    {
        return Tweet::query()
        ->whereDate(Tweet::PUBLISHED_AT_COLUMN, '>', Carbon::now()->subDays(10))
            ->whereDate(Tweet::DUE_AT_COLUMN, '>=', Carbon::now())
            ->orWhereNull(Tweet::DUE_AT_COLUMN)
            ->count();
    }
    
    public function keywordsMatch(array $keywords): int
    {
        $query = Tweet::query();
            // ->whereDate(Tweet::PUBLISHED_AT_COLUMN, '>', Carbon::now()->subDays(10))
            // ->whereDate(Tweet::DUE_AT_COLUMN, '>=', Carbon::now())
            // ->orWhereNull(Tweet::DUE_AT_COLUMN);

        foreach($keywords as $index => $keyword) {
            if ($index === 0) {
                $query = $query->whereRaw(sprintf("LOWER(%s) like ?", Tweet::TEXT_COLUMN), ["%{$keyword}%"]);
            }

            $query = $query->orWhereRaw(sprintf("LOWER(%s) like ?", Tweet::TEXT_COLUMN), ["%{$keyword}%"]);
        }

        return $query->count();
    }
}