@extends('layouts.admin')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
    <div class="row row-cards mb-4">
        <div class="col-12">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-blue text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mailbox"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 21v-6.5a3.5 3.5 0 0 0 -7 0v6.5h18v-6a4 4 0 0 0 -4 -4h-10.5"></path>
                                        <path d="M12 11v-8h4l2 2l-2 2h-4"></path>
                                        <path d="M6 15h1"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ number_format($verifiedEmails, 0, ',', ' ') }} Email
                                </div>
                                <div class="text-muted">
                                    {{ number_format($unverifiedEmails, 0, ',', ' ') }} waiting for verification
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Verified</th>
                                <th>Added at</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($emails->count() === 0)
                                <tr>
                                    <td colspan="5" class="text-center text-xs text-muted">No data</td>
                                </tr>
                            @endif
                            @foreach ($emails as $email)
                                <tr>
                                    <td>{{ ucwords($email->name ?? '') }}</td>
                                    <td>{{ $email->email }}</td>
                                    <td>{{ $email->is_active }}</td>
                                    <td>{{ $email->created_at?->format('d/m/Y') }}</td>
                                    {{-- <td>{{ $email->last_login ? $email->last_login->format('d/m/Y H:m') : '---' }}</td> --}}
                                    {{-- <td>{{ ucwords($subscription->store->name) }}</td>
                        <td>{{ ucwords($subscription->client->getFullName()) }}</td>
                        <td>{{ ucwords($subscription->pack->name) }}</td>
                        <td>{{ $subscription->pack->isFixedPrice() ? $subscription->pack->symbol : '' }}{{ $subscription->pack->price }}{{ !$subscription->pack->isFixedPrice() ? '%' : '' }}</td>
                        <td>{{ ucfirst($subscription->status) }}</td>
                        <td>{{ $subscription->activated_on->format('d/m/Y') }}</td> --}}
                                    {{-- <td align="center">
                            <a href="#" class="btn btn-dark" title="Reset password" data-bs-toggle="modal" data-bs-target="#modal-password-{{ $email->id }}">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-key" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="8" cy="15" r="4"></circle>
                                <line x1="10.85" y1="12.15" x2="19" y2="4"></line>
                                <line x1="18" y1="5" x2="20" y2="7"></line>
                                <line x1="15" y1="8" x2="17" y2="10"></line>
                              </svg>
                            </a>
                            <form action="{{ route('admin.clients.restrict', ['id' => $email->id]) }}" method="POST" style="display: inline;">
                              @csrf
                              @if (!$email->isBanned())
                              <button class="btn btn-danger" title="Ban">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                                  <circle cx="12" cy="16" r="1"></circle>
                                  <path d="M8 11v-4a4 4 0 0 1 8 0v4"></path>
                                </svg>
                              </button>
                              @else
                              <button type="submit" class="btn btn-dark" title="Cancel the ban">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock-open" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                                  <circle cx="12" cy="16" r="1"></circle>
                                  <path d="M8 11v-5a4 4 0 0 1 8 0"></path>
                                </svg>
                              </button>
                              @endif
                            </form>
                        </td> --}}
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($emails->count() > 0)
                    <div class="card-footer d-flex align-items-center">
                        {!! $emails->links('partials.admin.paginator') !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
