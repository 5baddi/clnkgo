<div class="row">
    <div class="card bg-azure-lt">
        <div class="card-body">
          <div class="row">
            <div class="col-auto">
              <span class="avatar rounded">
                  <img src="{{ asset('assets/img/logo.mini.png') }}"/>
              </span>
            </div>
            <div class="col">
              <div class="font-weight-medium">{{ $currentPack ? ucwords($currentPack->name) : "Free Plan" }}</div>
              @if($user->subscription->isTrial())
              <div class="text-muted">Free trial ends <strong>{{ $user->subscription->trial_ends_on->diffForHumans() }}<strong></div>
              @else
              <div class="text-muted">{{ $currentPack->isFixedPrice() ? $currentPack->symbol : '' }}{{ $currentPack->price }}{{ !$currentPack->isFixedPrice() ? '% of revenue share' : ' per month' }}</div>
              @endif
            </div>
          </div>
        </div>
    </div>
</div>