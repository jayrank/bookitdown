{{-- Extends layout --}}
@extends('frontend.layouts.guestLayout')

{{-- CSS Section --}}  
@section('innercss')
@endsection

@section('content')
<style type="text/css">
    body {
        margin: 0;
        padding: 0;
        background-color: #f2f2f7;
    }
    h1, h2, h3, h4, h5, h6 {
        margin: 0;
        padding: 0;
    }
    .wrapper {
        width: 450px;
        margin: 0 auto;
    }
    .logo {
        font-size: 25px;
        padding: 10px;
    }
    .logo a {
        text-decoration: none;
        display: block;
        text-align: center;
        color: #000;
        font-weight: bold;
        font-family: 'Trebuchet MS', sans-serif;
    }
    .logo p {
        font-size: 17px;
        color: #878ca0;
        text-align: center;
        font-family: Helvetica, sans-serif;
    }
    .new-pass-box {
        width: 90%;
        margin: 0 auto;
        background-color: #fff;
        border-radius: 10px;
        padding: 30px 20px;
        font-family: Tahoma, sans-serif;
    }
    .new-pass-box h2 {
        font-size: 28px;
        font-weight: 900;
        line-height: unset;
    }
    .new-pass-box p {
        color: #878ca0;
        font-size: 16px;
        margin-top: 3%;
    }
    /*.wrapper form input {
        width: 95%;
        height: 25px;
        margin: 15px 0px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #9ea3b6;
        outline: none;
    }*/
    .wrapper form input[type="submit"] {
        width: 100%;
        height: 45px;
        margin: 20px 0px 0px 0px;
        padding: 10px;
        border-radius: 5px;
        border: none;
        font-weight: bold;
        color: #fff;
        cursor: pointer;
        background-color: #209ae8;
    }
    .wrapper form a {
        font-family: Tahoma, sans-serif;
        text-decoration: none;
        color: #209ae8;
    }
    .wrapper form a:hover {
        text-decoration: underline;
    }
    .copyright {
        position: relative;
        bottom: 1px;
        top: 100px;
    }
    .copyright p {
        text-align: center;
    }
    .copyright a {
        text-decoration: none;
        color: #209ae8;
    }
    .reset-pwd {
        text-align: center;
    }
    @media only screen and (max-width: 576px) {
        .wrapper {
            width: 80%;
        }
    }
    @media only screen and (max-width: 425px) {
        .wrapper {
            width: 90%;
        }
        .new-pass-box h2 {
            font-size: 24px;
        }
    }
</style>
<div class="wrapper">
    <div class="logo">
        <a href="#" target="_blank">
            {{ config('app.name', 'Laravel') }}
        </a>
        <!-- <p></p> -->
    </div>
    <div class="new-pass-box">
        <h2>Enter new password</h2>
        <p>Securely access your account by creating a new log in password</p>
        <form action="{{ route('partner-reset-password', ['token' => $token, 'email' => urlencode($email)])}}" method="post">
            @csrf
            <label for="pwd">New Password</label>
            <input type="password" id="pwd" name="password" class="form-control">
            <label for="new-pwd">Confirm your new password</label>
            <input type="password" id="new-pwd" name="password_confirmation" class="form-control">
            <input type="submit" class="submit-btn btn" value="Change my password">
            <p>Having trouble? <a href="{{ route('partner-forgot-password') }}">Reset your password again</a></p>
        </form>
    </div>
    <div class="copyright">
        <!-- <p>&copy; 2021</p> -->
    </div>
</div>
@endsection
@section('scripts')

<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
@endsection