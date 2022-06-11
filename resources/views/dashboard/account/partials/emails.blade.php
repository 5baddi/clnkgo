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
</div>