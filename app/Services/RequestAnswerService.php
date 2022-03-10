<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

use BADDIServices\SourceeApp\Models\RequestAnswer;
use BADDIServices\SourceeApp\Repositories\RequestAnswerRepository;

class RequestAnswerService extends Service
{
    /** @var TwitterUserRespository */
    private $requestAnswerRepository;

    public function __construct(RequestAnswerRepository $requestAnswerRepository)
    {
        $this->requestAnswerRepository = $requestAnswerRepository;
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
                RequestAnswer::ANSWERED_COLUMN,
                RequestAnswer::MAIL_SENT_AT_COLUMN,
            ]);

        return $this->requestAnswerRepository->save($filteredAttributes->toArray());
    }
}