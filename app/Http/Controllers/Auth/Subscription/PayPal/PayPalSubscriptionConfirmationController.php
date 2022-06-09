<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Auth\Subscription\PayPal;

use Throwable;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Models\Pack;
use BADDIServices\SourceeApp\Entities\Alert;
use BADDIServices\SourceeApp\Events\Subscription\SubscriptionActivated as SubscriptionSubscriptionActivated;
use Symfony\Component\HttpFoundation\Response;
use BADDIServices\SourceeApp\Models\Subscription;
use BADDIServices\SourceeApp\Services\PackService;
use BADDIServices\SourceeApp\Services\SubscriptionService;
use BADDIServices\SourceeApp\Notifications\Subscription\SubscriptionActivated;
use BADDIServices\SourceeApp\Http\Controllers\DashboardController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;

class PayPalSubscriptionConfirmationController extends DashboardController
{
    /** @var PackService */
    private $packService;
    
    /** @var SubscriptionService */
    private $subscriptionService;

    public function __construct(PackService $packService, SubscriptionService $subscriptionService)
    {
        parent::__construct();

        $this->packService = $packService;
        $this->subscriptionService = $subscriptionService;
    }

    public function __invoke(string $packId)
    {
        try {
            $pack = $this->packService->findById($packId);
            abort_unless($pack instanceof Pack, Response::HTTP_NOT_FOUND, 'No pack selected');

            $subscription = $this->subscriptionService->save(
                $this->user,
                [
                    Subscription::PACK_ID_COLUMN        => $pack->getId(),
                    Subscription::STATUS_COLUMN         => Subscription::CHARGE_ACCEPTED,
                    Subscription::BILLING_ON_COLUMN     => Carbon::now(),
                    Subscription::ACTIVATED_ON_COLUMN   => Carbon::now(),
                    Subscription::ENDS_ON_COLUMN        => $pack->interval === 'month' ? Carbon::now()->addMonth(1) : Carbon::now()->addYear(1),
                    Subscription::TRIAL_ENDS_ON_COLUMN  => null,
                    Subscription::CANCELLED_ON_COLUMN   => null
                ]
            );

            $subscription->load(['user', 'pack']);
            $this->user->notify(new SubscriptionActivated($subscription));

            Event::dispatch(new SubscriptionSubscriptionActivated($this->user, $subscription));

            return redirect()
                ->route('dashboard')
                ->with(
                    'alert',
                    new  Alert(ucwords($pack->name) . ' plan activated successfully', 'success')
                );
        } catch (Throwable $e) {
            AppLogger::error($e, 'client:confirm-subscription', ['pack' => $packId]);

            return redirect()
                ->route('dashboard.plan.upgrade')
                ->with(
                    'alert',
                    new Alert('Internal server error')
                ); 
        }
    }
}