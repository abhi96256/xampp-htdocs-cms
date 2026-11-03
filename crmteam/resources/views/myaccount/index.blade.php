@extends('adminlte::page')

@section('title', 'My Account')

@section('content_header')
    <h1>My Account</h1>
    @if ($errors->any())
        <div class="border border-danger text-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (Session::has('success'))
        <div class="border border-danger text-danger">
            <ul>
                <li>{{ Session::get('success') }}</li>
            </ul>
        </div>
    @endif
@stop

@section('content')
    <div class="card px-3 py-2">
        <form method="post" action="{{ route('myaccount.update', ['id' => auth()->user()->id]) }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="name">Name</label>
                    <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Full Name"
                    value="{{ auth()->user()->name }}" required readonly>
                </div>
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="username@example.com"
                    value="{{ auth()->user()->email }}" required readonly>
                </div>
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="photo">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/png, image/jpeg">
                </div>
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="photo">Current Photo</label>
                    @if (isset(auth()->user()->photo_url))
                        <img height="42" width="42" src="{{ auth()->user()->photo_url }}" alt='profile photo'
                        class="inline w-9 h-9 pr-1" />
                    @else
                        <span class="border border-red-500">
                            No photo provided.
                        </span>
                    @endif
                </div>
            </div>

            @error('email')
                <div class="border border-danger text-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Update">
                <a class="btn btn-danger ml-3" href="{{ route('dashboard.index') }}">Cancel</a>
            </div>
        </form>

        <hr class="mt-4 mb-4">
        
        <h4>Change Password</h4>
        @if(!session('password_otp_sent'))
        <form method="post" action="{{ route('myaccount.send_password_otp') }}">
            @csrf
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-warning text-dark font-weight-bold">
                    <i class="fas fa-key"></i> Request OTP to Change Password
                </button>
            </div>
        </form>
        @else
        <div class="alert alert-info mt-3">
            An OTP has been sent to your email address ({{ auth()->user()->email }}). Please enter it below.
        </div>
        <form method="post" action="{{ route('myaccount.change_password_with_otp') }}">
            @csrf
            <div class="row">
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="otp">OTP received on Email</label>
                    <input type="text" class="form-control" id="otp" name="otp"
                    placeholder="Enter 6-digit OTP" required>
                </div>
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="password">New Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                    placeholder="New password" required>
                </div>
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="password_confirmation">Confirm New Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirm new password" required>
                </div>
            </div>

            @error('password')
                <div class="border border-danger text-danger mb-3 px-2 py-1">{{ $message }}</div>
            @enderror
            @error('otp')
                <div class="border border-danger text-danger mb-3 px-2 py-1">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Save Password">
                <a class="btn btn-danger ml-3" href="{{ route('dashboard.index') }}">Cancel</a>
            </div>
        </form>
        @endif

    </div>
@stop

@section('css')
@stop

@section('js')
@stop
