{{-- Extends layout --}}
@extends('frontend.layouts.index')

{{-- CSS Section --}}  
@section('innercss')
<style type="text/css">
	.list-card-image img.item-img {
	    height: 186px;
	    object-fit: cover;
	}
	.single-featured-venue {
		min-height: 325px;
	}
</style>
@endsection
@section('content')
    <form method="POST" enctype="multipart/form-data" action="{{ route('submitTilled') }}" id="tilledForm">
        @csrf
        <div class="row">
            <div class="col-lg-6">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="mb-15 nw_set">
                            <label>Card Number
                                <div id="card-number-element"></div>
                            </label>
                            <label>Card Expiration
                                <div id="card-expiration-element"></div>
                            </label>
                            <label>Card CVV
                                <div id="card-cvv-element"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        const tilled = new Tilled('pk_jRAj6gUpVrTaElVqksMBIgVfKQkNAsyQgpQ1SIJWl0ZwFADNVJt4Kb52qbWk6wXXZHFTAQCdfrvGEo0DEdqXOCnj8ZVIiXPo1076', 'acct_Ah9tVSLzeldtw0bhsVnv0', { sandbox: true, "endpoint":'https://sandbox-api.tilled.com/v1',"log_level":0 });
        const form = tilled.form({
            payment_method_type: 'card',
        }).then(
            function(){
                console.log('here');
                const fieldOptions = {
                    styles: {
                        base: {
                            fontFamily: 'Flexo, Muli, Helvetica Neue, Arial, sans-serif',
                            color: '#304166',
                            fontWeight: '400',
                            fontSize: '16px',
                        },
                        invalid: {
                            ':hover': {
                                textDecoration: 'underline dotted red',
                            },
                        },
                        valid: {
                            color: '#00BDA5',
                        },
                    },
                };
                console.log(form);
                setTimeout(function(){
                    form.createField('cardNumber', fieldOptions).inject('#card-number-element');
                    form.createField('cardExpiry', fieldOptions).inject('#card-expiration-element');
                    form.createField('cardCvv', fieldOptions).inject('#card-cvv-element');
                    form.build();

                    submitButton.on('click', () => {
                        tilled.confirmPayment(payment_intent_client_secret, {
                            payment_method: {
                                form: form,
                                billing_details: {
                                name: 'John Doe', // required
                                address: {
                                    country: 'US', // required
                                    zip: '12345', // required
                                    state: 'ST',
                                    city: 'City',
                                    street: '123 ABC Lane',
                                },
                                email: null,
                                },
                            },
                        })
                        .then(
                            (payment) => {
                                // payment is successful, payment will be an instance of PaymentIntent containing information about the transaction that was craeted
                            },
                            (err) => {
                                // show the error to the customer
                            },
                        );
                    });
                },15000);
            }
        );
        
        // setTimeout(function(){
        // },3000);

        // submitButton is not defined, but imagine the form has a submit button the user would click
    })
</script>
@endsection