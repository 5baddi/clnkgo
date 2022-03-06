<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

use App\Models\User;
use BADDIServices\SourceeApp\Models\Pack;
use Illuminate\Database\Eloquent\Collection;
use BADDIServices\SourceeApp\Models\Subscription;
use BADDIServices\SourceeApp\Repositories\PackRepository;

class PackService extends Service
{
    /** @var PackRepository */
    private $packRepository;

    public function __construct(PackRepository $packRepository)
    {
        $this->packRepository = $packRepository;
    }

    public function all(): Collection
    {
        return $this->packRepository->all();
    }
    
    public function findById(string $id): Pack
    {
        return $this->packRepository->findById($id);
    }

    public function loadCurrentPack(User $user): ?Pack
    {
        $user->load('subscription');

        /** @var Subscription */
        $subscription = $user->subscription;

        if (!is_null($subscription)) {
            $subscription->load('pack');

            return $subscription->pack;
        }

        return null;
    }
}