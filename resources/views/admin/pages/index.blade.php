@extends('layouts.admin')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="card">
          <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
              <thead>
                <tr>
                  <th>Slug</th>
                  <th>Title</th>
                  <th>Main menu</th>
                  <th>Published at</th>
                  <th>Updated at</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @if ($pages->total() === 0)
                <tr>
                  <td colspan="6" class="text-center text-xs text-muted">No page found.</td>
                </tr>
                @endif
                @foreach ($pages as $page)
                  <tr>
                    <td>{{ $page->getSlug() }}</td>
                    <td>{{ ucwords($page->getTitle()) }}</td>
                      {{-- <td>{{ ucwords($subscription->pack->name) }}</td>
                      <td>{{ $subscription->pack->isFixedPrice() ? $subscription->pack->symbol : '' }}{{ $subscription->pack->price }}{{ !$subscription->pack->isFixedPrice() ? '%' : '' }}</td>
                      <td>{{ ucfirst($subscription->status) }}</td>
                      <td>{{ $subscription->activated_on->format('d/m/Y') }}</td>
                      <td>{{ $subscription->store->connected_at->format('d/m/Y') }}</td>
                      <td align="center">
                          <a href="{{ route('admin.stores.view', ['store' => $subscription->store->id]) }}" class="btn btn-dark" title="Visit the store" target="_blank">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <circle cx="12" cy="12" r="2"></circle>
                                  <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                              </svg>
                          </a>
                          @if ($subscription->store->isEnabled())
                          <form action="{{ route('admin.stores.disable', ['store' => $subscription->store->id]) }}" method="POST" style="display: inline;">
                          @csrf
                          <button class="btn btn-danger" title="Disable store">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                              <circle cx="12" cy="16" r="1"></circle>
                              <path d="M8 11v-4a4 4 0 0 1 8 0v4"></path>
                            </svg>
                          </button>
                          </form>
                          @else
                          <form action="{{ route('admin.stores.enable', ['store' => $subscription->store->id]) }}" method="POST" style="display: inline;">
                          @csrf
                          <button type="submit" class="btn btn-dark" title="Enable store">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock-open" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                              <circle cx="12" cy="16" r="1"></circle>
                              <path d="M8 11v-5a4 4 0 0 1 8 0"></path>
                            </svg>
                          </button>
                          </form>
                          @endif
                      </td> --}}
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @if ($pages->total() > 0)
          <div class="card-footer d-flex align-items-center">
            {!! $pages->links('partials.dashboard.paginator') !!}
          </div>
          @endif
        </div>
    </div>
</div>

<div class="row mt-4">
  <div class="col-12 text-end">
    <div class="d-flex">
      <a href="{{ route('admin.pages.create') }}" class="btn btn-ghost-dark ms-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
          <rect x="4" y="4" width="16" height="16" rx="2"></rect>
          <line x1="9" y1="12" x2="15" y2="12"></line>
          <line x1="12" y1="9" x2="12" y2="15"></line>
        </svg>
        &nbsp;Create new page
      </a>
    </div>
  </div>
</div>
@endsection

@section('scripts')
    @include('partials.dashboard.scripts.form')
@endsection