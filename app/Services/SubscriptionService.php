<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use BADDIServices\ClnkGO\Models\Pack;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use BADDIServices\ClnkGO\Models\Subscription;
use BADDIServices\ClnkGO\Repositories\SubscriptionRepository;
use BADDIServices\ClnkGO\Notifications\Subscription\SubscriptionCancelled;
use BADDIServices\ClnkGO\Events\Subscription\SubscriptionCancelled as SubscriptionCancelledEvent;

class SubscriptionService extends Service
{
    /** @var SubscriptionRepository */
    private $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function loadRelations(Subscription &$subscription): Subscription
    {
        $subscription->load(['user', 'pack']);
        
        return $subscription;
    }
    
    public function findById(string $id): ?Subscription
    {
        return $this->subscriptionRepository->findById($id);
    }
    
    public function findByChargeId(string $chargeId): ?Subscription
    {
        return $this->subscriptionRepository->findByChargeId($chargeId);
    }
    
    public function getUsageBills(): Collection
    {
        return $this->subscriptionRepository->getUsageBills();
    }
    
    public function startTrial(User $user, Pack $pack): Subscription
    {
        return $this->save(
            $user,
            [
                Subscription::PACK_ID_COLUMN        => $pack->getId(),
                Subscription::STATUS_COLUMN         => Subscription::CHARGE_ACCEPTED,
                Subscription::ACTIVATED_ON_COLUMN   => Carbon::now(),
                Subscription::TRIAL_ENDS_ON_COLUMN  => Carbon::now()->addDays(Pack::DEFAULT_TRIAL_DAYS)
            ]
        );
    }
    
    public function save(User $user, array $billing): Subscription
    {
        $billing = collect($billing);

        $billing = $billing->only([
            Subscription::PACK_ID_COLUMN,
            Subscription::CHARGE_ID_COLUMN,
            Subscription::STATUS_COLUMN,
            Subscription::BILLING_ON_COLUMN,
            Subscription::ACTIVATED_ON_COLUMN,
            Subscription::ENDS_ON_COLUMN,
            Subscription::TRIAL_ENDS_ON_COLUMN,
            Subscription::CANCELLED_ON_COLUMN
        ]);

        return $this->subscriptionRepository->save($user->getId(), $billing->toArray());
    }

    public function cancelSubscription(User $user, Subscription $subscription): void
    {
        $this->subscriptionRepository->save(
            $user->getId(),
            [
                Subscription::STATUS_COLUMN         => Subscription::CANCELLED_ON_COLUMN,
                Subscription::ACTIVATED_ON_COLUMN   => null,
                Subscription::TRIAL_ENDS_ON_COLUMN  => null,
                Subscription::ENDS_ON_COLUMN        => null,
                Subscription::CANCELLED_ON_COLUMN   => Carbon::now()
            ]
        );

        $subscription->load('pack');

        $user->notify(new SubscriptionCancelled($subscription));

        Event::dispatch(new SubscriptionCancelledEvent($user->getId(), $subscription->getId()));
    }

    public function paginateWithRelations(?int $page = null): LengthAwarePaginator
    {
        return $this->subscriptionRepository->paginateWithRelations($page);
    }
}