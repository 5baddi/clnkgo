@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@php
    $tab = isset($tab) ? $tab : 'settings';
@endphp

@section('content')
<div class="row row-cards">
    <div class="card-tabs">
        <ul class="nav nav-tabs" style="border-bottom: unset !important;">
            <li class="nav-item" style="border-bottom: 1px solid rgba(98,105,118,.16);">
            <a href="{{ route('dashboard.account', ['tab' => 'settings']) }}" class="nav-link card-title {{ $tab === 'settings' ? 'active' : '' }}">General info</a>
            </li>
            <li class="nav-item" style="border-bottom: 1px solid rgba(98,105,118,.16);">
            <a href="{{ route('dashboard.account', ['tab' => 'password']) }}" class="nav-link card-title {{ $tab === 'password' ? 'active' : '' }}">Account Password</a>
            </li>
            <li class="nav-item" style="border-bottom: 1px solid rgba(98,105,118,.16);">
            <a href="{{ route('dashboard.account', ['tab' => 'emails']) }}" class="nav-link card-title {{ $tab === 'emails' ? 'active' : '' }}">Email preferences</a>
            </li>
            @if(! $user->isSuperAdmin())
            <li class="nav-item" style="border-bottom: 1px solid rgba(98,105,118,.16);">
            <a href="{{ route('dashboard.account', ['tab' => 'plan']) }}" class="nav-link card-title {{ $tab === 'plan' ? 'active' : '' }}">Your plan</a>
            </li>
            @endif
        </ul>
    </div>

    <form action="{{ route('dashboard.account.save', ['tab' => $tab]) }}" method="POST" style="margin-top: 0 !important;" id="main-form">
        @csrf
        <input type="hidden" id="emails" name="emails"/>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    @if ($tab === 'settings')
                    <div class="row">
                        <p class="text-muted">Setup your account, edit profile details</p>
                        <div class="col-6">
                            <label class="form-label">First name</label>
                            <input type="text" name="first_name" class="form-control @if ($errors->has('first_name')) is-invalid @endif" value="{{ old('first_name') ?? ucfirst($user->first_name)  }}" placeholder="Your first name" autofocus/>
                            @if ($errors->has('first_name'))
                            <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                            @endif
                        </div>
                        <div class="col-6">
                            <label class="form-label">First name</label>
                            <input type="text" name="last_name" class="form-control @if ($errors->has('last_name')) is-invalid @endif" value="{{ old('last_name') ?? ucfirst($user->last_name) }}" placeholder="Your last name"/>
                            @if ($errors->has('last_name'))
                            <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control @if ($errors->has('email')) is-invalid @endif" value="{{ old('email') ?? $user->email }}" placeholder="E-mail"/>
                            @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control @if ($errors->has('phone')) is-invalid @endif" value="{{ old('phone') ?? $user->phone }}" placeholder="Your phone number"/>
                            @if ($errors->has('phone'))
                            <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>
                    @elseif ($tab === 'password')
                    <div class="row">
                        <p class="text-muted">Change or set a new password</p>
                        @if ($user->hasPassword())
                        <div class="col-4">
                            <label class="form-label">Current password</label>
                            <input type="password" name="current_password" class="form-control @if ($errors->has('current_password')) is-invalid @endif" placeholder="Current password"/>
                            @if ($errors->has('current_password'))
                            <div class="invalid-feedback">{{ $errors->first('current_password') }}</div>
                            @endif
                        </div>
                        @endif
                        <div class="col-4">
                            <label class="form-label">New password</label>
                            <input type="password" name="password" class="form-control @if ($errors->has('password')) is-invalid @endif" placeholder="New password"/>
                            @if ($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="col-4">
                            <label class="form-label">Confirm new password</label>
                            <input type="password" name="confirm_password" class="form-control @if ($errors->has('confirm_password')) is-invalid @endif" placeholder="Confirm new password"/>
                            @if ($errors->has('confirm_password'))
                            <div class="invalid-feedback">{{ $errors->first('confirm_password') }}</div>
                            @endif
                        </div>
                    </div>
                    @elseif($tab === 'plan' && ! $user->isSuperAdmin())
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
                    @else
                    <div class="row">
                        <p class="text-muted">You can link many emails, to use them during sending requests</p>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <input type="text" value="{{ $emails ?? '' }}" id="tags-input" class="form-control @if ($errors->has('emails')) is-invalid @endif" autofocus placeholder="Add your emails"/>
                                @if ($errors->has('emails'))
                                <div class="invalid-feedback">{{ $errors::first('emails') }}</div>
                                @endif
                            </div>
                            <span class="text-muted text-sm">Hit <kbd>ENTER</kbd> or <kbd>comma</kbd> to add an email</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-end">
                    <div class="d-flex justify-content-end">
                        @if($tab === 'plan' && ! $user->isSuperAdmin())
                        @if(! $user->subscription->isTrial())<a href="{{ route('subscription.cancel') }}" class="btn btn-danger">Cancel subscription</a>@endif
                        <a href="{{ route('dashboard.plan.upgrade') }}" class="btn btn-twitter" style="margin-left: .5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-rotate-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M15 4.55a8 8 0 0 0 -6 14.9m0 -4.45v5h-5"></path>
                                <line x1="18.37" y1="7.16" x2="18.37" y2="7.17"></line>
                                <line x1="13" y1="19.94" x2="13" y2="19.95"></line>
                                <line x1="16.84" y1="18.37" x2="16.84" y2="18.38"></line>
                                <line x1="19.37" y1="15.1" x2="19.37" y2="15.11"></line>
                                <line x1="19.94" y1="11" x2="19.94" y2="11.01"></line>
                            </svg>
                            &nbsp;Upgrade plan
                        </a>
                        @else
                        <button type="submit" class="btn btn-twitter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                                <circle cx="12" cy="14" r="2"></circle>
                                <polyline points="14 4 14 8 8 8 8 4"></polyline>
                            </svg>
                            &nbsp;Save
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('partials.dashboard.scripts.form')
@endsection

@section('script')
    $('document').ready(function() {
        $('#tags-input').tagsinput({
            cancelConfirmKeysOnEmpty: false,
            trimValue: true,
            allowDuplicates: false,
        });

        var tags = $('#tags-input').tagsinput('items');
        if (typeof tags !== 'undefined') {
            $('#tags-count').text(tags.length || 0);

            $('#tags-input').on('itemAdded', function(event) {
                var tags = $('#tags-input').tagsinput('items');
    
                $('#tags-count').text(tags.length || 0);
                $('#emails').val(tags.join(','));
            });
            
            $('#tags-input').on('itemRemoved', function(event) {
                var tags = $('#tags-input').tagsinput('items');
    
                $('#tags-count').text(tags.length || 0);
                $('#emails').val(tags.join(','));
            });
        }
    });
@endsection