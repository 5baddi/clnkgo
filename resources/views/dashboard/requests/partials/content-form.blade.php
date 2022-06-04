<div class="col-12 mt-2">
    <label class="form-label">Use a canned response</label>
    <select class="form-select" @if($cannedResponses->count() === 0) disabled @else id="canned-responses" @endif>
        @foreach ($cannedResponses as $cannedResponse)
        <option value="{{ $cannedResponse->content }}">{{ $cannedResponse->title }}</option>
        @endforeach
    </select>
    <p class="small text-muted mt-2">
        <a href="{{ route('dashboard.responses.new') }}">Create new canned response</a>
    </p>
</div>
<div class="col-12 mt-2">
    <label class="form-label required">Your response</label>
    <textarea id="direct-content" rows="5" name="content" class="form-control @if($errors->has('content')) is-invalid @endif" placeholder="Write your response here..." required>{{ old('content') ?? ($answer ? $answer->content : '') }}</textarea>
    @if($errors->has('content'))
        <div class="invalid-feedback d-block">
            {{ $errors->first('content') }}
        </div>
    @endif
    <p class="small text-muted mt-2">Reply via Twitter</p>
</div>