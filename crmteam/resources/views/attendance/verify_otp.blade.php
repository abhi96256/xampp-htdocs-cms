@extends('adminlte::page')

@section('title', 'Verify OTP')

@section('content_header')
    <h1>Verify OTP for Attendance</h1>
@stop

@section('content')
    <div class="card px-4 py-4 mt-3 col-md-6 mx-auto">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Show which email the OTP was sent to --}}
        @if(session('attendance_otp_email'))
            <div class="alert alert-info d-flex align-items-center">
                <i class="fas fa-envelope mr-2"></i>
                <span>
                    OTP has been sent to&nbsp;<strong>{{ session('attendance_otp_email') }}</strong>.
                    &nbsp;Please check your inbox.
                </span>
            </div>
        @endif

        <form method="post" action="{{ route('attendance.verify_otp.post') }}">
            @csrf
            <div class="form-group row mb-4">
                <label for="otp" class="col-sm-3 col-form-label">Enter OTP <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="text" name="otp" class="form-control" id="otp" required placeholder="Enter the 6-digit OTP">
                    @error('otp')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-success">Verify and Save</button>
                    <a href="{{ route('attendance.create') }}" class="btn btn-secondary text-white">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@stop
