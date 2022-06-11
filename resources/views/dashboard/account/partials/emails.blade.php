<div class="row">
    <p class="text-muted">You can link many emails, to use them during sending requests</p>
    <div class="col-12">
        <div class="form-group mb-2">
            <input type="text" value="{{ $emails ?? '' }}" id="tags-input" class="form-control @if ($errors->has('emails')) is-invalid @endif" autofocus placeholder="Add your emails"/>
            @if ($errors->has('emails'))
            <div class="invalid-feedback">{{ $errors::first('emails') }}</div>
            @endif
        </div>
        <span class="text-muted text-sm" style="color: white !important;">Hit <kbd>ENTER</kbd> or <kbd>comma</kbd> to add an email</span>
    </div>
</div>