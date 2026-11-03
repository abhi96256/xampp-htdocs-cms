@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop



@section('auth_header', __('Enter Verification Code'))

@section('auth_body')
    <form action="" method="post">
        {{ csrf_field() }}

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="text" name="code" class="form-control "
                   value="" placeholder="Enter Code" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-password {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
			<?php //echo "<pre>";print_r($errors->first('code'));echo "</pre>";?>
             @if($errors->first('code'))
                <div class="invalid-feedback has_error">
                    <strong>{{$errors->first('code')}}</strong>
                </div>
            @endif
        </div>

       

        {{-- Login field --}}
        <div class="row">
            
            <div class="col-5">
                <button type=submit class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-sign-in-alt"></span>
                    Verify
                </button>
            </div>
        </div>

    </form>
<style>
.has_error{
	display:block !important;
}
.login-page, .register-page {
    background: url({{ asset('/imgs/low-angle-view-skyscrapers.jpg')}}) no-repeat !important;
}
</style>
@stop

