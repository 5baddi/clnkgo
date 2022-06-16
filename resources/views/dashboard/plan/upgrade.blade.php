@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
@include('dashboard.plan.partials.pricing-cards')
@endsection

@section('scripts')
<script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&vault=true&intent=subscription"></script>
@endsection