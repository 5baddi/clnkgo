<div class="row">
    <p class="text-muted">You can link many emails, to use them during sending requests</p>
    <div class="col-6 mb-3">
        <label class="form-label">Email</label>
        <div class="row g-2">
            <div class="col">
                <input type="email" name="new_email" value="{{ old('new_email') }}" class="form-control @if ($errors->has('new_email')) is-invalid @endif" autofocus placeholder="Add your new Email"/>
                @if ($errors->has('new_email'))
                <div class="invalid-feedback">{{ $errors->first('new_email') }}</div>
                @endif
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-white btn-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @if($user->linkedEmails->count() > 0)
    <div class="col-12 mt-2">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter table-mobile-md card-table">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Status</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->linkedEmails as $linkedEmail)
                        <tr>
                            <td>{{ $linkedEmail->email }}</td>
                            <td>
                                @if(! $linkedEmail->isConfirmed())
                                <span class="badge bg-warning me-1"></span> Pending
                                @else
                                <span class="badge bg-success me-1"></span> Confirmed
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('dashboard.account.linked-emails.remove', ['id' => $linkedEmail->id]) }}" class="btn btn-danger" title="Remove" onclick="return confirm('Are you sure you want to remove this linked email?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <line x1="4" y1="7" x2="20" y2="7"></line>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>