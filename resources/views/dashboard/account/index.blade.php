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
                    @include('dashboard.account.partials.info')
                    @elseif ($tab === 'password')
                    @include('dashboard.account.partials.password')
                    @elseif($tab === 'plan' && ! $user->isSuperAdmin())
                    @include('dashboard.account.partials.plan')
                    @else
                    @include('dashboard.account.partials.emails')
                    @endif
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-end">
                    <div class="d-flex justify-content-end">
                        @if($tab === 'plan' && ! $user->isSuperAdmin())
                        @if(! $user->subscription->isTrial())<a href="{{ route('subscription.cancel') }}" class="btn btn-danger">Cancel subscription</a>@endif
                        <a href="{{ route('dashboard.plan.upgrade') }}" class="btn btn-clnkgo" style="margin-left: .5rem;">
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
                        <button type="submit" class="btn btn-clnkgo">
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