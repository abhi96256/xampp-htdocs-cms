@extends('adminlte::page')
@section('title', 'Send Agreement')

@section('content_header')
<div class="row align-items-center">
    <div class="col"><h1>📨 Send New Agreement</h1></div>
    <div class="col text-right">
        <a href="{{ route('agreements.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow">
            <div class="card-header" style="background:#1a1a2e; color:#fff;">
                <strong>📋 Allotment Letter Details</strong>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('agreements.send') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label><strong>Select User <span class="text-danger">*</span></strong></label>
                        <select name="user_id" class="form-control js-example-basic-single" required>
                            <option value="">-- Select User --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $u->email ?? 'No email' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Only users with email addresses can receive agreements.</small>
                    </div>

                    <div class="form-group">
                        <label><strong>Agreement / Allotment Type <span class="text-danger">*</span></strong></label>
                        <select name="agreement_type" class="form-control" required>
                            <option value="">-- Select Type --</option>
                            <option value="salary" {{ old('agreement_type') === 'salary' ? 'selected' : '' }}>💰 Salary Based Employee</option>
                            <option value="commission" {{ old('agreement_type') === 'commission' ? 'selected' : '' }}>🤝 Commission Based Employee</option>
                            <option value="channel_partner" {{ old('agreement_type') === 'channel_partner' ? 'selected' : '' }}>🔵 Channel Partner</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><strong><i class="fas fa-file mr-1"></i> Upload Document <small class="text-muted">(Optional)</small></strong></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="document" id="docFile" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                            <label class="custom-file-label" for="docFile">Choose file...</label>
                        </div>
                        <small class="text-muted">Allowed: PDF, DOC, DOCX, PNG, JPG. We'll attach it directly in their email!</small>
                    </div>

                    <div class="alert alert-info py-2" style="font-size:13px;">
                        <i class="fas fa-info-circle"></i>
                        An email with a <strong>6-digit OTP</strong> and a secure signing link will be sent to the user's email.
                        The OTP will be valid for <strong>24 hours</strong>.
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-success btn-lg btn-block">
                            <i class="fas fa-paper-plane"></i> Send Agreement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="{{ asset('js/s2.js') }}"></script>
<script>
// Show filename in custom file input
document.querySelectorAll('.custom-file-input').forEach(function(input) {
    input.addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file...';
        var label = e.target.nextElementSibling;
        label.textContent = fileName;
    });
});
</script>
@stop
