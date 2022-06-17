<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Services;

use App\Models\User;
use BADDIServices\ClnkGO\Models\RequestAnswer;
use BADDIServices\ClnkGO\Models\Tweet;
use BADDIServices\ClnkGO\Repositories\RequestAnswerRepository;

class RequestAnswerService extends Service
{
    /** @var RequestAnswerRepository */
    private $requestAnswerRepository;

    public function __construct(RequestAnswerRepository $requestAnswerRepository)
    {
        $this->requestAnswerRepository = $requestAnswerRepository;
    }

    public function findById(string $id): ?RequestAnswer
    {
        return $this->requestAnswerRepository->findById($id);
    }
    
    public function find(User $user, Tweet $tweet): ?RequestAnswer
    {
        return $this->requestAnswerRepository->find($user->getId(), $tweet->getId());
    }

    public function save(array $attributes): RequestAnswer
    {
        $filteredAttributes = collect($attributes)
            ->filter(function ($value) {
                return $value !== null;
            })
            ->only([
                RequestAnswer::USER_ID_COLUMN,
                RequestAnswer::TWEET_ID_COLUMN,
                RequestAnswer::CONTENT_COLUMN,
                RequestAnswer::EMAIL_COLUMN,
                RequestAnswer::SUBJECT_COLUMN,
                RequestAnswer::FROM_COLUMN,
                RequestAnswer::ANSWERED_COLUMN,
                RequestAnswer::MAIL_SENT_AT_COLUMN,
            ]);

        return $this->requestAnswerRepository->save($filteredAttributes->toArray());
    }
}