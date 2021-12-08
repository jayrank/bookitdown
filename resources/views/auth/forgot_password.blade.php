@extends('layouts.app')

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
        /*width: 450px;*/
        margin: 0 auto;
        padding-left: unset !important;
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
        <h2>Reset your password</h2>
        <p>Enter your registered login email address to receive a secure link to set a new password</p>
        <form action="{{ route('partner-forgot-password') }}" method="post" class="form">
            @csrf
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" class="form-control">
            <input type="submit" class="submit-btn btn" value="Send password reset">
            <p class="reset-pwd">Don't want to reset password? <br><a href="{{ route('login') }}">Go to log in</a></p>
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
        $(document).on('submit', '.form', function(e){
            e.preventDefault();

            $('.submit-btn').attr('disabled', true);

            var self = $(this);
            var url = self.attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'JSON',
                data: self.serialize(),
                success: function(response) {
                    if(response.status) {
                        responseMessages('success',response.message);

                    } else {
                        responseMessages('error',response.message);
                    }
                },
                error: function(response) {
                    responseMessages('error','Something went wrong.')
                },
                complete: function(response) {
                    $('.submit-btn').removeAttr('disabled');
                }
            });
        });
    });
</script>
@endsection