{{-- Extends layout --}}
@extends('frontend.layouts.guestLayout')

{{-- CSS Section --}}  
@section('innercss')
@endsection

@section('content')

<div class="osahan-signup login-page">
    <img alt="bg-img" class="bg-img" src="{{ asset('frontend/img/bg.jpg') }}" />
    <div class="d-flex align-items-center justify-content-center flex-column vh-100">
        <div class="px-5 col-md-6 ml-auto">
            <div class="px-5 col-10 mx-auto">
                <h2>Set New Password</h2>
                <form action="{{ route('reset-password', ['token' => $token, 'email' => urlencode($email)])}}" method="post" class="mt-5 mb-4">
                    @csrf
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                            aria-describedby="emailHelp">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Send</button>
                </form>
            </div>
            <div class="new-acc d-flex align-items-center justify-content-center">
                <a href="login.html">
                    <p class="text-center m-0">Already an account? <a href="{{ route('fsignup') }}">Sign in</a></p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
@endsection