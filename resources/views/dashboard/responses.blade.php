@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Canned Responses</h3>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('dashboard.responses.new') }}" class="btn btn-twitter d-none d-sm-inline-block">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Add new response
                    </a>
                </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Content</th>
                  <th>Added at</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                  @if ($responses->count() === 0)
                  <tr>
                    <td colspan="5" class="text-center text-xs text-muted">No canned response found!</td>
                  </tr>
                  @endif
                  @foreach ($responses as $response)
                    <tr>
                        <td>{{ $response->title }}</td>
                        <td>{{ substr($response->content , 0, 20) . '...' ?? '---' }}</td>
                        <td>{{ $response->created_at->format('d/m/Y') }}</td>
                        <td class="text-red">UNPAID</td>
                        <td>
                            {{-- <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modal-payment-{{ $commission->id }}">
                                Send payment&nbsp;&nbsp;
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                    <path d="M21 3l-6.5 18a0.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a0.55 .55 0 0 1 0 -1l18 -6.5"></path>
                                </svg>
                            </a> --}}
                        </td>
                    </tr>
                    
                    @include('dashboard.payouts.send-modal', ['commission' => $commission])
                  @endforeach
              </tbody>
            </table>
          </div>
          @if ($responses->count() > 0)
          <div class="card-footer d-flex align-items-center">
            {!! $responses->links('partials.dashboard.paginator') !!}
          </div>
          @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @include('partials.dashboard.scripts.form')
@endsection