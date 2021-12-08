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
                <h2>Forgot password</h2>
                <p>Enter your email address below and we'll send you an email with instructions on how to change
                    your password</p>
                <form action="{{ route('forgot-password')}}" method="post" class="mt-5 mb-4 form">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block submit-btn">Send</button>
                </form>
            </div>
            <div class="new-acc d-flex align-items-center justify-content-center">
                <a href="{{ route('flogin') }}">
                    <p class="text-center m-0">Already an account? Sign in</p>
                </a>
            </div>
        </div>
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