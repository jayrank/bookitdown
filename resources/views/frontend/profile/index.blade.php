{{-- Extends layout --}}
@extends('frontend.layouts.index')

{{-- CSS Section --}}  
@section('innercss')
@endsection

@section('content')
    <style type="text/css">
        .single_address_container {
            background-color: #f2f2f7;
            padding: 12px 16px;
            margin: 5px 0;
        }
        .address_edit, .address_delete {
            font-size: 1.5em;
            cursor: pointer;
        }
    </style>
    <div class="osahan-profile">
        <div class="d-none">
            <div class="bg-primary border-bottom p-3 d-flex align-items-center">
                <a class="toggle togglew toggle-2" href="#"><span></span></a>
                <h4 class="font-weight-bold m-0 text-white">Profile</h4>
            </div>
        </div>
        <!-- profile -->
        <div class="container position-relative">
            <div class="py-5 osahan-profile row">
                <div class="col-md-4 mb-3">
                    <div class="bg-white rounded shadow-sm sticky_sidebar overflow-hidden">
                        <!-- <a href="javascript:void(0)" class=""> -->
                            <div class="d-flex align-items-center p-3">
                                <div class="left mr-3">
                                    @if( !empty($frontUser->image) )
                                        <img src="{{ asset($frontUser->image) }}" alt="salon-img"
                                        class="img-fluid rounded" width="200px" height="auto">
                                    @else
                                        <img alt="#" src="{{ url('public/frontend/img/user1.jpg') }}" class="rounded-circle">
                                    @endif
                                </div>
                                <div class="right">
                                    <h6 class="mb-1 font-weight-bold">{{ !empty($frontUser) ? $frontUser->name.' '.$frontUser->last_name : '' }} <i
                                            class="feather-check-circle text-success"></i></h6>
                                    <p class="text-muted m-0 small">{{ !empty($frontUser) ? $frontUser->email : '' }}</p>
                                </div>
                            </div>
                        <!-- </a> -->
                        <div class="osahan-credits d-flex align-items-center p-3 bg-light">
                            <p class="m-0">Accounts Credits</p>
                            <h5 class="m-0 ml-auto text-primary">$52.25</h5>
                        </div>
                        <!-- profile-details -->
                        <div class="bg-white profile-details">
                            <a data-toggle="modal" data-target="#paycard"
                                class="d-flex w-100 align-items-center border-bottom p-3">
                                <div class="left mr-3">
                                    <h6 class="font-weight-bold mb-1 text-dark">Payment Cards</h6>
                                    <p class="small text-muted m-0">Add a credit or debit card</p>
                                </div>
                                <div class="right ml-auto">
                                    <h6 class="font-weight-bold m-0"><i class="feather-chevron-right"></i></h6>
                                </div>
                            </a>
                            <a class="d-flex w-100 align-items-center border-bottom p-3 open_all_address">
                                <div class="left mr-3">
                                    <h6 class="font-weight-bold mb-1 text-dark">Address</h6>
                                    <p class="small text-muted m-0">Add or remove a delivery address</p>
                                </div>
                                <div class="right ml-auto">
                                    <h6 class="font-weight-bold m-0"><i class="feather-chevron-right"></i></h6>
                                </div>
                            </a>
                            <!-- <a class="d-flex align-items-center border-bottom p-3" data-toggle="modal"
                                data-target="#inviteModal">
                                <div class="left mr-3">
                                    <h6 class="font-weight-bold mb-1">Refer Friends</h6>
                                    <p class="small text-primary m-0">Get $10.00 FREE</p>
                                </div>
                                <div class="right ml-auto">
                                    <h6 class="font-weight-bold m-0"><i class="feather-chevron-right"></i></h6>
                                </div>
                            </a> -->
                            <!-- <a href="javascript:void(0)" class="d-flex w-100 align-items-center border-bottom px-3 py-4">
                                <div class="left mr-3">
                                    <h6 class="font-weight-bold m-0 text-dark"><i
                                            class="feather-truck bg-danger text-white p-2 rounded-circle mr-2"></i>
                                        Delivery Support</h6>
                                </div>
                                <div class="right ml-auto">
                                    <h6 class="font-weight-bold m-0"><i class="feather-chevron-right"></i></h6>
                                </div>
                            </a>
                            <a href="contact-javascript:void(0)" class="d-flex w-100 align-items-center border-bottom px-3 py-4">
                                <div class="left mr-3">
                                    <h6 class="font-weight-bold m-0 text-dark"><i
                                            class="feather-phone bg-primary text-white p-2 rounded-circle mr-2"></i>
                                        Contact</h6>
                                </div>
                                <div class="right ml-auto">
                                    <h6 class="font-weight-bold m-0"><i class="feather-chevron-right"></i></h6>
                                </div>
                            </a> -->
                            <a href="{{ route('website-terms') }}" class="d-flex w-100 align-items-center border-bottom px-3 py-4">
                                <div class="left mr-3">
                                    <h6 class="font-weight-bold m-0 text-dark"><i
                                            class="feather-info bg-success text-white p-2 rounded-circle mr-2"></i> Term
                                        of use</h6>
                                </div>
                                <div class="right ml-auto">
                                    <h6 class="font-weight-bold m-0"><i class="feather-chevron-right"></i></h6>
                                </div>
                            </a>
                            <a href="{{ route('privacy-policy') }}" class="d-flex w-100 align-items-center px-3 py-4">
                                <div class="left mr-3">
                                    <h6 class="font-weight-bold m-0 text-dark"><i
                                            class="feather-lock bg-warning text-white p-2 rounded-circle mr-2"></i>
                                        Privacy policy</h6>
                                </div>
                                <div class="right ml-auto">
                                    <h6 class="font-weight-bold m-0"><i class="feather-chevron-right"></i></h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 mb-3">
                    <div class="rounded shadow-sm p-4 bg-white">
                        <h5 class="mb-4">My account</h5>
                        <div id="edit_profile">
                            <div>
                                <form action="{{ url('updateProfile') }}" method="post">

                                    {!! csrf_field() !!}

                                    <div class="form-group">
                                        <label for="exampleInputName1">First Name</label>
                                        <input name="name" type="text" class="form-control" id="exampleInputName1d" value="{{ !empty($frontUser) ? $frontUser->name : '' }}">
                                        {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">Last Name</label>
                                        <input name="last_name" type="text" class="form-control" id="exampleInputName1" value="{{ !empty($frontUser) ? $frontUser->last_name : '' }}">
                                        {!! $errors->first('last_name', '<span class="text-danger">:message</span>') !!}
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputNumber1">Mobile Number</label>
                                        <input name="mobile" type="number" class="form-control" id="exampleInputNumber1"
                                            value="{{ !empty($frontUser) ? $frontUser->mobile : '' }}">
                                        {!! $errors->first('mobile', '<span class="text-danger">:message</span>') !!}
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email</label>
                                        <input type="email" class="form-control" id="exampleInputEmail1"
                                            value="{{ !empty($frontUser) ? $frontUser->email : '' }}" disabled>
                                        {!! $errors->first('email', '<span class="text-danger">:message</span>') !!}
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                            <div class="additional">
                                <div class="change_password my-3">
                                    <a href="javascript:void(0)"
                                        class="p-3 border rounded bg-white btn d-flex align-items-center" data-toggle="modal" data-target="#changePasswordModal">Change
                                        Password
                                        <i class="feather-arrow-right ml-auto"></i></a>
                                </div>
                                <div class="deactivate_account">
                                    <a href="javascript:void(0)"
                                        class="p-3 border rounded bg-white btn d-flex align-items-center">Deactivate
                                        Account
                                        <i class="feather-arrow-right ml-auto"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- payment modal -->
        <div class="modal fade" id="paycard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Credit/Debit Card</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form type="post" action="{{ url('savePaymentDetail') }}" id="savePaymentDetail">
                        <div class="modal-body">
                            <h6 class="m-0">Add new card</h6>
                            <p class="small">WE ACCEPT <span class="osahan-card ml-2 font-weight-bold">(Master Card / Visa Card / Rupay)</span></p>
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label font-weight-bold small">Card number</label>
                                    <div class="input-group">
                                        <input type="text" name="card_number" placeholder="Card number" id="card_number" class="form-control">
                                        <div class="input-group-append"><button type="button" class="btn btn-outline-secondary"><i class="feather-credit-card"></i></button></div>
                                    </div>
                                </div>
                                <div class="col-md-8 form-group">
                                    <label class="form-label font-weight-bold small">Valid through(MM/YY)
                                    </label>
                                    <input placeholder="Enter Valid through(MM/YY)" name="expiry_date" id="expiry_date" type="text" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="form-label font-weight-bold small">CVV</label>
                                    <input placeholder="CVV" name="cvv" type="number" id="cvv" class="form-control">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label class="form-label font-weight-bold small">Name on card</label>
                                    <input placeholder="Enter Card number" id="name_on_card" name="name_on_card" type="text" class="form-control">
                                </div>
                                <div class="col-md-12 form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="securely_checkout" class="custom-control-input">
                                        <label title="" type="checkbox" for="securely_checkout" class="custom-control-label small pt-1">Securely save this card for a faster checkout next time.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer p-0 border-0">
                            <div class="col-6 m-0 p-0">
                                <button type="button" class="btn border-top btn-lg btn-block" data-dismiss="modal">Close</button>
                            </div>
                            <div class="col-6 m-0 p-0">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <!-- all address modal -->
        <div class="modal fade" id="allAddressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delivery Addresses</h5>
                        <button type="button" class="btn btn-primary float-right add_address_button" >Add</button>
                    </div>
                    <div class="modal-body">
                        <div class="row address_container">
                        </div>
                    </div>
                    <div class="modal-footer p-0 border-0">
                        <div class="col-12 m-0 p-0">
                            <button type="button" class="btn border-top btn-lg btn-block"
                                data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- address modal -->
        <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Delivery Address</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="javascript:void(0)" class="add_address_form">
                            {!! csrf_field() !!}
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label">Delivery Area</label>
                                    <div class="input-group">
                                        <input placeholder="Delivery Area" type="text" class="form-control" name="delivery_area">
                                        <div class="input-group-append"><span
                                                class="btn btn-outline-secondary" style="cursor: default;"><i class="feather-map-pin"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 form-group"><label class="form-label">Complete Address</label><input
                                        placeholder="Complete Address e.g. house number, street name, landmark" type="text"
                                        class="form-control" name="complete_address"></div>
                                <div class="col-md-12 form-group"><label class="form-label">Delivery
                                        Instructions</label><input
                                        placeholder="Delivery Instructions e.g. Opposite Gold Souk Mall" type="text"
                                        class="form-control" name="delivery_instructions"></div>
                                <div class="mb-0 col-md-12 form-group">
                                    <label class="form-label">Nickname</label>
                                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                        <label class="btn btn-outline-secondary active">
                                            <input type="radio" value="home" name="address_type" id="option1" checked> Home
                                        </label>
                                        <label class="btn btn-outline-secondary">
                                            <input type="radio" value="work" name="address_type" id="option2"> Work
                                        </label>
                                        <label class="btn btn-outline-secondary">
                                            <input type="radio" value="other" name="address_type" id="option3"> Other
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer p-0 border-0">
                        <div class="col-6 m-0 p-0">
                            <button type="button" class="btn border-top btn-lg btn-block"
                                data-dismiss="modal">Close</button>
                        </div>
                        <div class="col-6 m-0 p-0">
                            <button type="button" class="btn btn-primary btn-lg btn-block add_address">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- edit address modal -->
        <div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Delivery Address</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="javascript:void(0)" class="edit_address_form">
                            {!! csrf_field() !!}

                            <input type="hidden" name="address_id" class="address_id">
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label">Delivery Area</label>
                                    <div class="input-group">
                                        <input placeholder="Delivery Area" type="text" class="form-control delivery_area" name="delivery_area">
                                        <div class="input-group-append"><button type="button"
                                                class="btn btn-outline-secondary"><i class="feather-map-pin"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 form-group"><label class="form-label">Complete Address</label><input
                                        placeholder="Complete Address e.g. house number, street name, landmark" type="text"
                                        class="form-control complete_address" name="complete_address"></div>
                                <div class="col-md-12 form-group"><label class="form-label">Delivery
                                        Instructions</label><input
                                        placeholder="Delivery Instructions e.g. Opposite Gold Souk Mall" type="text"
                                        class="form-control delivery_instructions" name="delivery_instructions"></div>
                                <div class="mb-0 col-md-12 form-group">
                                    <label class="form-label">Nickname</label>
                                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                        <label class="btn btn-outline-secondary address_type_container active">
                                            <input type="radio" value="home" name="address_type" class="address_type" id="option1" checked> Home
                                        </label>
                                        <label class="btn btn-outline-secondary address_type_container">
                                            <input type="radio" value="work" name="address_type" class="address_type" id="option2"> Work
                                        </label>
                                        <label class="btn btn-outline-secondary address_type_container">
                                            <input type="radio" value="other" name="address_type" class="address_type" id="option3"> Other
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer p-0 border-0">
                        <div class="col-6 m-0 p-0">
                            <button type="button" class="btn border-top btn-lg btn-block"
                                data-dismiss="modal">Close</button>
                        </div>
                        <div class="col-6 m-0 p-0">
                            <button type="button" class="btn btn-primary btn-lg btn-block edit_address">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Invite Modal-->
        <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title font-weight-bold text-dark">Invite</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body py-0">
                        <button class="btn btn-light text-primary btn-sm"><i class="feather-plus"></i></button>
                        <span class="ml-2 smal text-primary">Send an invite to a friend</span>
                        <p class="mt-3 small">Invited friends (2)</p>
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3"><img alt="#" src="img/user1.jpg" class="img-fluid rounded-circle"></div>
                            <div>
                                <p class="small font-weight-bold text-dark mb-0">Kate Simpson</p>
                                <P class="mb-0 small">katesimpson@outbook.com</P>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3"><img alt="#" src="img/user2.png" class="img-fluid rounded-circle"></div>
                            <div>
                                <p class="small font-weight-bold text-dark mb-0">Andrew Smith</p>
                                <P class="mb-0 small">andrewsmith@ui8.com</P>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                    </div>
                </div>
            </div>
        </div>


        <!-- change password modal -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="javascript:void(0)" class="change_password_form">
                            {!! csrf_field() !!}
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label">Current Password</label>
                                    <input placeholder="Current Password" type="password" class="form-control" name="current_password">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label">New Password</label>
                                    <input placeholder="New Password" type="password" class="form-control" name="new_password">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label">Confirm Password</label>
                                    <input placeholder="Confirm Password" type="password" class="form-control" name="confirm_password">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer p-0 border-0">
                        <div class="col-6 m-0 p-0">
                            <button type="button" class="btn border-top btn-lg btn-block"
                                data-dismiss="modal">Close</button>
                        </div>
                        <div class="col-6 m-0 p-0">
                            <button type="button" class="btn btn-primary btn-lg btn-block update_password">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
@section('scripts')
<script type="text/javascript">
    $('#card_number').mask('0000 0000 0000 0000');
    $('#cvv').mask('000');
    $('#expiry_date').mask('00/00');
    $(document).ready(function(){
        $(document).on('click','.open_all_address', function(){
            get_all_addresses();           
        });

        $(document).on('click','.add_address', function(){

            var form_data = $('.add_address_form').serialize();

            $.ajax({
                url: '{{ url("addAddress") }}',
                type: 'POST',
                dataType: 'JSON',
                data: form_data,
                success: function(response) {
                    if(response.status) {
                        responseMessages('success', response.message);

                        $('.add_address_form').find("input[type=text], textarea").val("");

                        $('#addressModal').modal('hide');
                    } else {
                        responseMessages('error', response.message);
                    }
                },
                error: function(response) {
                    responseMessages('error', 'Something went wrong. Please reload and try again.');
                },
                complete: function(response) {

                }
            });
            
        });

        $(document).on('click','.address_delete', function(){
            var self = $(this);
            var address_id = self.closest('.address_action_container').find('.get_address_id').text();

            $.ajax({
                url: '{{ url("deleteAddress") }}',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    "_token": "{{ csrf_token() }}",
                    address_id: address_id
                },
                success: function(response) {
                    if(response.status) {
                        self.closest('.single_address_container').remove();

                        if($('.single_address_container').length == 0) {
                            $('.address_container').html('<i>No addresses added.</i>');
                        }
                        responseMessages('success', response.message);
                    } else {
                        responseMessages('error', response.message);
                    }
                },
                error: function(response) {
                    responseMessages('error', 'Something went wrong. Please reload and try again.');
                },
                complete: function(response) {

                }
            });
            
        });

        $(document).on('click','.add_address_button',function(){
            $('#allAddressModal').modal('hide');
            $('#addressModal').modal('show');
        });

        $(document).on('click','.address_edit', function(){
            $('.address_id').val( $(this).closest('.address_action_container').find('.get_address_id').text() );
            $('.delivery_area').val( $(this).closest('.address_action_container').find('.get_delivery_area').text() );
            $('.complete_address').val( $(this).closest('.address_action_container').find('.get_complete_address').text() );
            $('.delivery_instructions').val( $(this).closest('.address_action_container').find('.get_delivery_instructions').text() );

            var previous_checked = $('.address_type:checked');
            var current_checked = $('.address_type[value='+$(this).closest('.address_action_container').find('.get_address_type').text()+']');

            previous_checked.closest('.address_type_container').removeClass('active');
            previous_checked.prop('checked', false);

            current_checked.closest('.address_type_container').addClass('active');
            current_checked.prop('checked', true);

            $('#allAddressModal').modal('hide');
            $('#editAddressModal').modal('show');
        });

        $(document).on('click','.edit_address', function(){

            var form_data = $('.edit_address_form').serialize();

            $.ajax({
                url: '{{ url("updateAddress") }}',
                type: 'POST',
                dataType: 'JSON',
                data: form_data,
                success: function(response) {
                    if(response.status) {
                        responseMessages('success', response.message);

                        $('.edit_address_form').find("input[type=text], textarea").val("");

                        $('#editAddressModal').modal('hide');

                        get_all_addresses();
                    } else {
                        responseMessages('error', response.message);
                    }
                },
                error: function(response) {
                    responseMessages('error', 'Something went wrong. Please reload and try again.');
                },
                complete: function(response) {

                }
            });
            
        });

        $(document).on('click','.update_password', function(){

            var form_data = $('.change_password_form').serialize();

            $.ajax({
                url: '{{ url("changePassword") }}',
                type: 'POST',
                dataType: 'JSON',
                data: form_data,
                success: function(response) {
                    if(response.status) {
                        responseMessages('success', response.message);

                        $('.change_password_form').find("input[type=password]").val("");

                        $('#changePasswordModal').modal('hide');
                    } else {
                        responseMessages('error', response.message);
                    }
                },
                error: function(response) {
                    responseMessages('error', 'Something went wrong. Please reload and try again.');
                },
                complete: function(response) {

                }
            });
            
        });

        $('#changePasswordModal').on('hidden.bs.modal', function () {
            $('.change_password_form').find("input[type=password]").val("");
        });

        $('#addressModal').on('hidden.bs.modal', function() {
            $('.add_address_form').find("input[type=text], textarea").val("");
        });
    });

    function get_all_addresses() {

        $.ajax({
            url: '{{ url("getAddresses") }}',
            success: function(response) {
                if(response.status) {
                    var content = '';

                    if(response.data.length > 0) {
                        for( index = 0; index < response.data.length; index++ ) {

                            content += '<div class="col-12 single_address_container">';
                                content += '<div class="float-left">';
                                    content += '<h5>'+response.data[index].address_type.toUpperCase()+'</h5>';
                                    content += '<span>'+response.data[index].delivery_area+'</span>';
                                content += '</div>';
                                content += '<div class="float-right address_action_container">';

                                    content += '<span style="display: none;" class="get_address_id">'+response.data[index].id+'</span>';
                                    content += '<span style="display: none;" class="get_address_type">'+response.data[index].address_type+'</span>';
                                    content += '<span style="display: none;" class="get_delivery_area">'+response.data[index].delivery_area+'</span>';
                                    content += '<span style="display: none;" class="get_complete_address">'+response.data[index].complete_address+'</span>';
                                    content += '<span style="display: none;" class="get_delivery_instructions">'+response.data[index].delivery_instructions+'</span>';

                                    content += '<i class="address_edit m-2 feather-edit"></i>';

                                    content += '<i class="address_delete m-2 feather-trash-2"></i>';
                                content += '</div>';
                            content += '</div>';
                        }
                    } else {
                        content += '<i>No addresses added.</i>';
                    }

                    $('.address_container').html(content);

                    $('#allAddressModal').modal('show');
                } else {
                    responseMessages('error', response.message);
                }
            },
            error: function(response) {
                responseMessages('error', 'Something went wrong. Please reload and try again.');
            },
            complete: function(response) {

            }
        });
    }
    $('#savePaymentDetail').validate({
        rules:{
            card_number:{
                required:true
            },
            expiry_date:{
              required:true  
            },
            cvv:{
              required:true  
            },
            name_on_card:{
              required:true  
            }
        },
        submitHandler: function(form) {
            $.ajaxSetup({
               headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
            });
            $.ajax({
                type:'post',
                url:$('#savePaymentDetail').attr('action'),
                data:$('#savePaymentDetail').serialize(),
                success:function(resp){
                    if(resp.status == true){
                        responseMessages('success', resp.message);
                        // document.getElementById("savePaymentDetail").reset();
                        $('#paycard').modal('hide');
                    }else{
                        responseMessages('error', resp.message);
                    }
                }
            });
        }
    });
</script>
@endsection