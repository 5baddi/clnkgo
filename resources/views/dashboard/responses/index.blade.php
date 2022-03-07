@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <p>Save time when answering the next request, use a canned response. 🥫</p>
                        <p>These are predetermined responses that you can tailor to answer common requests.</p>
                        <p>You have used <strong>{{ $responses->count() }}</strong> of <strong>10</strong> canned responses in your plan.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4">
          <div class="card-header">
            <h3 class="card-title">Canned Responses</h3>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('dashboard.responses.new') }}" class="btn btn-twitter d-none d-sm-inline-block">
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
                  <th></th>
                </tr>
              </thead>
              <tbody>
                  @if ($responses->count() === 0)
                  <tr>
                    <td colspan="4" class="text-center text-xs text-muted">No canned response found!</td>
                  </tr>
                  @endif
                  @foreach ($responses as $response)
                    <tr>
                        <td>{{ $response->title }}</td>
                        <td>{{ substr($response->content , 0, 20) . '...' ?? '---' }}</td>
                        <td>{{ $response->created_at->format('d/m/Y') }}</td>
                        <td>
                          <a href="#" class="btn btn-twitter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4"></path>
                              <line x1="13.5" y1="6.5" x2="17.5" y2="10.5"></line>
                            </svg>
                            &nbsp;Edit
                          </a>
                          <a href="#" class="btn btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <line x1="4" y1="7" x2="20" y2="7"></line>
                              <line x1="10" y1="11" x2="10" y2="17"></line>
                              <line x1="14" y1="11" x2="14" y2="17"></line>
                              <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                              <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                            </svg>
                            &nbsp;Delete
                          </a>
                        </td>
                    </tr>
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