@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')

@endsection

@section('scripts')
  @include('partials.dashboard.scripts.form')
@endsection