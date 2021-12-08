{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')
    <link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />

    <style>

    </style>
@endsection

@section('content')

    <div class="container-fluid p-0">
        <div class="my-custom-body-wrapper">
            <!-- <div class="my-custom-header">
                <div class="p-4 d-flex justify-content-between align-items-center border-bottom">
                    <span class="d-flex">
                        <p class="cursor-pointer m-0 px-6" onclick="location.href='{{ route('showconForm') }}'"><i class="text-dark fa fa-times icon-lg"></i> </p>
                        <p class="cursor-pointer text-blue previous" onclick="nextPrev(-1)"><i class="border-left mx-4"></i>Previous</p>
                    </span>
                    <span class="text-center">
                        <p class="m-0">steps <span class="steps">1</span> of 2</p>
                        <h1 class="font-weight-bolder main-title">Add sections to your consultation form</h1>
                    </span>
                    <div id="addbtn">
                        <button class="btn btn-lg btn-primary next-step" onclick="nextPrev(1)">Next Step</button>
                        <button class="btn btn-lg btn-primary" id="fullsave">Save</button>
                    </div>
                </div>
            </div> -->
            <div class="my-custom-header bg-white"> 
                <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
                    <div style="display: flex; justify-content: flex-start; align-items: center;">
                        <a type="button" class="close" data-dismiss="modal" aria-label="Close" style="top:unset;left: 0;opacity: 1;font-size: 1.75rem;" onclick="window.history.back();">
                            <span aria-hidden="true" class="la la-times"></span>
                        </a>
                        <p class="h6 cursor-pointer mb-0 text-blue previous" onclick="nextPrev(-1)"><i class="border-left mx-4"></i>Previous</p>
                    </div>
                    <div style="flex-grow: 1; text-align: center;">
                        <h6 class="m-0">steps <span class="steps">1</span> of 2</h6>
                        <h2 class="font-weight-bolder main-title">Add sections to your consultation form</h2>
                    </div>
                    <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
                        <button class="btn btn-lg btn-primary next-step" onclick="nextPrev(1)">Next Step</button>
                        <button class="btn btn-lg btn-primary" id="fullsave">Save</button>
                    </div>
                </div>
            </div> 
            <div class="my-custom-body">
                <div class="container-fluid">

                    <div class="add-voucher-tab">
                        <div class="row">
                            <div class="col-12 col-md-2 p-0 border-right"
                                style="height: calc(100vh - 90px);overflow-y: scroll;overflow-x: hidden;">
                                <div class="">
                                    <h3 class="font-weight-bolder text-uppercase my-3">Sections</h3>
                                    <div class="card shadow-sm w-50 mx-auto mb-4 cursor-pointer showclientmodal add"
                                        data-toggle="modal" @if(isset($conform->client->id)) "" @else data-target="#addClientDetailModal" @endif>
                                        <div class="card-body px-4 py-6">
                                            <div class="mx-auto" style="height: 32px;width: 32px;">
                                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M16 3.872a2.743 2.743 0 012.737 2.737v2.48h6.741a2.65 2.65 0 012.645 2.483l.005.167v13.74a2.65 2.65 0 01-2.482 2.644l-.168.005H6.522a2.65 2.65 0 01-2.645-2.482l-.005-.168V11.74a2.65 2.65 0 012.482-2.645l.168-.005h6.74l.001-2.48a2.747 2.747 0 012.393-2.714l.172-.018zm-2.737 6.517H6.522a1.35 1.35 0 00-1.344 1.22l-.006.13v13.74a1.35 1.35 0 001.22 1.343l.13.006h18.956a1.35 1.35 0 001.344-1.22l.006-.13V11.74a1.35 1.35 0 00-1.22-1.344l-.13-.006h-6.741v2.087h-5.474V10.39zm.694 4.961c.91 0 1.65.739 1.65 1.65v4.26a1.65 1.65 0 01-1.65 1.65H9.696a1.65 1.65 0 01-1.65-1.65V17c0-.911.738-1.65 1.65-1.65zm9.347 5.217a.65.65 0 01.096 1.293l-.096.007H19.13a.65.65 0 01-.096-1.293l.096-.007h4.174zm-9.347-3.917H9.696a.35.35 0 00-.35.35v4.26c0 .194.156.35.35.35h4.26a.35.35 0 00.35-.35V17a.35.35 0 00-.35-.35zm9.347-.257a.65.65 0 01.096 1.293l-.096.007H19.13a.65.65 0 01-.096-1.292l.096-.008h4.174zM16.01 5.171l-.111.004a1.444 1.444 0 00-1.335 1.434v4.567h2.873l.001-4.567c0-.743-.574-1.36-1.3-1.43l-.128-.008z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="font-weight-bolder text-center m-0">Client details</p>
                                        </div>
                                    </div>
                                    <!--div class="card shadow-sm w-50 mx-auto mb-4">
                                        <div class="card-body px-4 py-6">
                                            <span class="badge badge-info px-2 py-1 rounded-0"
                                                style="position: absolute;top:0;right:0">
                                                soon
                                            </span>
                                            <div class="mx-auto" style="height: 32px;width: 32px;">
                                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M20.154 5.163v4.878h5.81a2.35 2.35 0 012.345 2.19l.005.16v12.861a2.35 2.35 0 01-2.35 2.35H6.516a2.35 2.35 0 01-2.35-2.35v-12.86a2.35 2.35 0 012.35-2.35l5.81-.001V5.163h7.828zm5.81 6.178H6.516c-.58 0-1.05.47-1.05 1.05v12.861c0 .58.47 1.05 1.05 1.05h19.448c.58 0 1.05-.47 1.05-1.05v-12.86c0-.58-.47-1.05-1.05-1.05zM16.24 14.92a.65.65 0 01.643.554l.007.096v2.601h2.614a.65.65 0 01.096 1.294l-.096.007-2.614-.001v2.603a.65.65 0 01-1.293.096l-.007-.096V19.47h-2.614a.65.65 0 01-.096-1.292l.096-.007 2.614-.001V15.57a.65.65 0 01.65-.65zm2.614-8.457h-5.228v3.578h5.228V6.463z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="font-weight-bolder text-muted text-center m-0">Medical history</p>
                                        </div>
                                    </div>
                                    <div class="card shadow-sm w-50 mx-auto mb-4">
                                        <div class="card-body px-4 py-6">
                                            <span class="badge badge-info px-2 py-1 rounded-0"
                                                style="position: absolute;top:0;right:0">
                                                soon
                                            </span> 
                                            <div class="mx-auto" style="height: 32px;width: 32px;">
                                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M13.495 19.673l.055.09 2.459 4.937c.59 1.194-.112 2.527-1.472 2.97-1.24.403-2.651-.047-3.26-1.064l-.077-.142-3.074-6.119a.65.65 0 011.107-.674l.055.09 3.074 6.12c.253.509 1.058.786 1.773.554.608-.199.884-.66.745-1.07l-.036-.087-2.458-4.935a.65.65 0 011.109-.67zM23.279 4v18.19l-8.483-4.135h-6.17c-2.485 0-4.514-2.102-4.621-4.74L4 13.096l.005-.221c.104-2.562 2.021-4.62 4.41-4.735l.212-.005h6.17L23.278 4zm-1.301 2.079l-6.882 3.355h-6.47c-1.7 0-3.14 1.422-3.309 3.267l-.013.2-.004.194c0 1.973 1.411 3.552 3.146 3.655l.18.005h6.47l6.882 3.354V6.079zm3.229 4.299c1.434 0 2.577 1.226 2.577 2.717 0 1.49-1.143 2.717-2.577 2.717a.65.65 0 01-.096-1.293l.096-.007c.695 0 1.277-.625 1.277-1.417 0-.746-.516-1.343-1.156-1.41l-.121-.007a.65.65 0 110-1.3z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="font-weight-bolder text-muted text-center m-0">Marketing preferences</p>
                                        </div>
                                    </div-->
                                    <hr>
                                    <div class="card shadow-sm w-50 mx-auto mb-4 cursor-pointer" data-toggle="modal"
                                        data-target="#addCustomSectionModal">
                                        <div class="card-body px-4 py-6">
                                            <div class="mx-auto" style="height: 32px;width: 32px;">
                                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M16 3l4.326 8.56L30 12.93l-7 6.662L24.652 29 16 24.558 7.348 29 9 19.593l-7-6.662 9.674-1.372L16 3zm3.472 9.751L16 5.882l-3.472 6.869-7.686 1.09 5.559 5.291L9.08 26.65 16 23.096l6.919 3.553-1.32-7.517 5.558-5.291-7.685-1.09z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="font-weight-bolder text-center m-0">
                                                Custom Section
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-10 bg-content"
                                style="height: calc(100vh - 90px);overflow-y: scroll;overflow-x: hidden;">
                                <div class="" style="display:none;">
                                    <div class="card w-50 mx-auto my-6" data-toggle="modal"
                                        data-target="#addNewSectionModal">
                                        <div class="rounded cursor-pointer"
                                            style="background-color: #e5f1ff;border: 1px solid #037aff !important;">
                                            <div class="mx-auto my-10" style="width: 200px;">
                                                <img alt="drag-img" src="{{ asset('/assets/images/drag.png') }}"
                                                    width="100%" />
                                            </div>
                                            <h2 class="text-center font-weight-bolder">Add your first section</h2>
                                            <h6 class="text-center mb-4">Drag and drop or <span class="text-blue">click
                                                    here</span> to add a
                                                section</h6>
                                        </div>
                                    </div>
                                </div>
                                <div id="ClientInfoShow" class="my-3">
                                    <ul class="nav nav-pills round-nav mx-auto" id="myTab1" role="tablist"
                                        style="width:240px">
                                        <li class="nav-item font-weight-bolder">
                                            <a class="nav-link active" id="builder-tab-1" data-toggle="tab" href="#builder">
                                                <span class="nav-text">Builder</span>
                                            </a>
                                        </li>
                                        <li class="nav-item font-weight-bolder" id="showpr">
                                            <a class="nav-link" id="preview-tab-1"  data-toggle="tab" href="#preview"
                                                aria-controls="preview">
                                                <span class="nav-text">Preview</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent1">
                                        <div class="tab-pane fade active show" id="builder" role="tabpanel" aria-labelledby="builder-tab-1">
                                            <div class="card w-70 mx-auto mt-16 customse ">
                                                <div id="appenddata">
                                                    @php
                                                        foreach ($conform->qna as $item) {
                                                            $showdata[] = $item;
                                                        }
                                                        $showdata[] = $conform->client;
                                                        $sortdata = collect($showdata)
                                                            ->sortBy('section_id')
                                                            ->toArray();
                                                        $groupdata = collect($sortdata)
                                                            ->groupBy('section_id')
                                                            ->toArray();
                                                        $total = count($groupdata);
                                                        $seid = [];

                                                    @endphp
                                                    @foreach ($groupdata as $value)
                                                        

                                                        @if (isset($value[0]['section_title']))
                                                            @php
                                                                array_push($seid,$value[0]['section_id']);
                                                            @endphp
                                                            <div id="builderclient" role="tabpanel"
                                                                aria-labelledby="builder-tab-1">
                                                                <div class="my-card-lable-purple"
                                                                    style="position: relative;max-width: 35%;">
                                                                    Section <span
                                                                        class="setp1">{{ $value[0]['section_id'] }}</span>
                                                                    of <span class="setp2">{{ $total }}</span>
                                                                    Client details
                                                                </div>
                                                                <div class="card-header d-flex justify-content-between">
                                                                    <div>
                                                                        <h3><span
                                                                                class="title"></span>{{ $value[0]['section_title'] }}
                                                                        </h3>
                                                                        <span
                                                                            class="m-0 text-dakr-50 sectionDes">{{ $value[0]['section_des'] }}</span>
                                                                    </div>
                                                                    <div class="dropdown dropdown-inline">
                                                                        <a href="#"
                                                                            class="btn btn-clean text-dark btn-sm btn-icon"
                                                                            data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i
                                                                                class="ki ki-bold-more-ver text-dark"></i>
                                                                        </a>
                                                                        <div
                                                                            class="dropdown-menu dropdown-menu-right text-center">
                                                                            <ul class="navi navi-hover">
                                                                                <li class="navi-item edit"
                                                                                    data-toggle="modal"
                                                                                    data-target="#addClientDetailModal">
                                                                                    <a class="navi-link">
                                                                                        <span class="navi-text"> Edit
                                                                                        </span>
                                                                                    </a>
                                                                                </li>
                                                                                <li class="navi-item">
                                                                                    <a class="navi-link"
                                                                                        id="deleteClinemo">
                                                                                        <span
                                                                                            class="navi-text text-danger">
                                                                                            Delete </span>
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-group fnameshow" @if ($value[0]['first_name'] == 1) style="display: block" @else style="display: none" @endif>
                                                                        <label class="font-weight-bolder">
                                                                            First name
                                                                        </label>
                                                                        <input type="text" disabled
                                                                            class="form-control">
                                                                    </div>

                                                                    <div class="form-group lnameshow" @if ($value[0]['last_name'] == 1) style="display: block" @else style="display: none" @endif>
                                                                        <label class="font-weight-bolder">
                                                                            Last name
                                                                        </label>
                                                                        <input type="text" disabled
                                                                            class="form-control">
                                                                    </div>

                                                                    <div class="form-group emailshow" @if ($value[0]['email'] == 1) style="display: block" @else style="display: none" @endif>
                                                                        <label class="font-weight-bolder">
                                                                            Email
                                                                        </label>
                                                                        <input type="email" disabled
                                                                            class="form-control">
                                                                    </div>

                                                                    <div class="form-group showbirthday" @if ($value[0]['birthday'] == 1) style="display: block" @else style="display: none" @endif>
                                                                        <label class="font-weight-bolder">
                                                                            Brithday
                                                                        </label>
                                                                        <input type="date" disabled
                                                                            class="form-control">
                                                                    </div>

																	<div class="form-group showmobile" @if ($value[0]['mobile'] == 1) style="display: block" @else style="display: none" @endif>
                                                                        <label class="font-weight-bolder">
                                                                            Country Code
                                                                        </label>
                                                                        <select class="form-control" name="country_code_select" id="country_code_select">
																		@if($Country)
																			@foreach($Country as $CountryData)
																				<option value="{{ $CountryData['phonecode'] }}" @if($CountryData['phonecode'] == $value[0]['country_code']) selected @endif>{{ $CountryData['name'] }} +{{ $CountryData['phonecode'] }}</option>
																			@endforeach
																		@endif	
																		</select>
                                                                    </div>

                                                                    <div class="form-group showmobile" @if ($value[0]['mobile'] == 1) style="display: block" @else style="display: none" @endif>
                                                                        <label class="font-weight-bolder">
                                                                            Mobile Number
                                                                        </label>
                                                                        <input type="text" disabled class="form-control">
                                                                    </div>

                                                                    <div class="form-group showgender" @if ($value[0]['gender']) ) style="display: block" @else style="display: none" @endif>
                                                                        <label class="font-weight-bolder">
                                                                            Gender
                                                                        </label>
                                                                        <select readonly class="form-control">
																			<option value="">Please select</option>
																			<option value="Male">Male</option>
																			<option value="Female">Female</option>
																			<option value="Other">Other</option>
																			<option value="I dont want to share">I dont want to share</option>
																		</select>
                                                                    </div>

                                                                    <div class="form-group showaddress" @if ($value[0]['address'] == 1) style="display: block" @else style="display: none" @endif>
                                                                        <label class="font-weight-bolder">
                                                                            Address
                                                                        </label>
                                                                        <textarea rows="4" disabled
                                                                            class="form-control"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if (isset($value[0]['title']))
                                                        
                                                            @php array_push($seid,$value[0]['section_id']); $max = count($value)==1 ? 0 : count($value)-1 ; @endphp
                                                            @for ($i = 0; $i <= $max; $i++)
                                                                @if ($i == 0)
                                                                    <div id="custom{{ $value[0]['section_id'] }}" class="sectiondata">
                                                                        <form name="qnaform{{ $value[0]['section_id'] }}" id="qnaform">
                                                                            <div class="my-card-lable-orange " style="position: relative;max-width: 35%;">
                                                                                Section <span
                                                                                    class="cusStep1">{{ $value[0]['section_id'] }}</span>
                                                                                of <span
                                                                                    class="cusStep2">{{ $total }}</span>:
                                                                                Custom section
                                                                            </div>
                                                                            <div class="card-header d-flex justify-content-between">
                                                                                <div>
                                                                                    <h3 id="cusshowTitle">
                                                                                        {{ $value[0]['title'] }}
                                                                                    </h3><input type="hidden" name="title[]"
                                                                                        value="{{ $value[0]['title'] }}">
                                                                                    <p class="m-0 text-dakr-50" id="cusDes">
                                                                                        {{ $value[0]['des'] }}</p><input
                                                                                        type="hidden" name="des[]"
                                                                                        value="{{ $value[0]['des'] }}">
                                                                                </div>
                                                                                <input type="hidden" name="sectionid[]"
                                                                                    id="sectionid"
                                                                                    value="{{ $value[0]['section_id'] }}">
                                                                                <div class="dropdown dropdown-inline">
                                                                                    <a href="#"
                                                                                        class="btn btn-clean text-dark btn-sm btn-icon"
                                                                                        data-toggle="dropdown"
                                                                                        aria-haspopup="true"
                                                                                        aria-expanded="false">
                                                                                        <i
                                                                                            class="ki ki-bold-more-ver text-dark"></i>
                                                                                    </a>
                                                                                    <div
                                                                                        class="dropdown-menu dropdown-menu-right text-center">
                                                                                        <ul class="navi navi-hover">
                                                                                            <li class="navi-item">
                                                                                                <a href=""
                                                                                                    class="navi-link">
                                                                                                    <span
                                                                                                        class="navi-text">Edit</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li class="navi-item">
                                                                                                <a class="navi-link">
                                                                                                    <span
                                                                                                        class="navi-text text-danger"
                                                                                                        id="deleteCustom"
                                                                                                        data-id="{{ $value[0]['section_id'] }}">Delete</span>
                                                                                                </a>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-body showdata">

                                                                                <div class="card" style="display: none;">
                                                                                    <div class="card-body bg-content">
                                                                                        <div class="d-flex justify-content-between ">
                                                                                            <div class="form-group mr-2 w-100">
                                                                                                <label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label>
                                                                                                <select class="form-control dropDownId" data-id="{{ $value[0]['section_id'] }}" tabindex="null">
                                                                                                    <option data-icon="fas fa-grip-lines" @if($value[$i]['answer_type']=='shortAnswer') {{ 'selected' }} @endif value="shortAnswer">
                                                                                                        Short answer
                                                                                                    </option> 
                                                                                                    <option @if($value[$i]['answer_type']=='longAnswer') {{ 'selected' }} @endif data-icon="fas fa-align-left" value="longAnswer">
                                                                                                        Long answer</option>
                                                                                                    <option data-icon="far fa-dot-circle" value="singleAnswer" @if($value[$i]['answer_type']=='singleAnswer') {{ 'selected' }} @endif >
                                                                                                        Single answer
                                                                                                    </option>
                                                                                                    <option @if($value[$i]['answer_type']=='singleCheckbox') {{ 'selected' }} @endif data-icon="far fa-check-square" value="singleCheckbox">
                                                                                                        Singlecheckbox
                                                                                                    </option>
                                                                                                    <option data-icon="fas fa-tasks" value="multipleCheckbox" @if($value[$i]['answer_type']=='multipleCheckbox') {{ 'selected' }} @endif>
                                                                                                        Multiplechoice
                                                                                                    </option>
                                                                                                    <option data-icon="far fa-caret-square-down" value="dropdown" @if($value[$i]['answer_type']=='dropdown') {{ 'selected' }} @endif>
                                                                                                        Drop-down</option>
                                                                                                    <option data-icon="fas fa-adjust" value="yesOrNo" @if($value[$i]['answer_type']=='yesOrNo') {{ 'selected' }} @endif>Yes
                                                                                                        or No</option>
                                                                                                    <option data-icon="far fa-font-case" value="des" @if($value[$i]['answer_type']=='des') {{ 'selected' }} @endif >
                                                                                                        Description text
                                                                                                    </option>
                                                                                                </select>
                                                                                            </div>

                                                                                            <div
                                                                                                class="form-group ml-2 w-100">
                                                                                                <label
                                                                                                    class="font-weight-bolder"
                                                                                                    for="exampleSelect1">Question</label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    id="question"
                                                                                                    placeholder="New question copy" value="{{ $value[$i]['question'] }}">
                                                                                                <span
                                                                                                    class="navi-text text-danger"
                                                                                                    id="error"></span>
                                                                                            </div>

                                                                                        </div>
                                                                                        <div class="inputappend{{ $value[0]['section_id'] }}"></div>
                                                                                        <hr>
                                                                                        <div
                                                                                            class="d-flex flex-wrap justify-content-between align-items-center">
                                                                                            <div class="">
                                                                                                <div
                                                                                                    class="form-group mb-0">
                                                                                                    <div class="switch switch-sm switch-icon switch-success"
                                                                                                        style="line-height: 28px;">
                                                                                                        <label
                                                                                                            class="d-flex align-item-center font-weight-bolder">
                                                                                                            <input type="checkbox" @if($value[$i]['required']==1) checked="checked" @endif id="required">
                                                                                                            <span></span>&nbsp;Required
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="d-flex align-items-center">
                                                                                                <span
                                                                                                    class="border-right p-3">
                                                                                                    <i
                                                                                                        class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i>
                                                                                                    <i
                                                                                                        class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i>
                                                                                                </span>
                                                                                                <span
                                                                                                    class="border-right p-3">
                                                                                                    <button type="button" class="mx-2 btn btn-sm btn-white copyNewQnaCus"><i class="mx-1 far fa-clone fa-2x"></i></button>
                                                                                                    <button type="button" class="mx-2 btn btn-sm btn-white deleteNewQnaCus"><i class="mx-1 fas fa-trash fa-2x text-danger"></i></button>
                                                                                                </span>
                                                                                                <span class="p-3">
                                                                                                    <button type="button"
                                                                                                        class="mx-2 btn btn-sm btn-white closeqna"><i
                                                                                                            class="p-0 fa fa-times"></i></button>
                                                                                                    <button type="button"
                                                                                                        class="mx-2 btn btn-sm btn-primary saveques"><i
                                                                                                            class="p-0 fas fa-check"></i></button>
                                                                                                </span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group showedit">

                                                                                    @if ($value[$i]['answer_type'] == 'des')
                                                                    
                                                                                    @else
                                                                                        <label class="font-weight-bolder">{{ $value[$i]['question'] }}</label>
                                                                                    @endif
                                                                                    <input type="hidden" class="form-control" name="required[]" value="{{ $value[$i]['required'] }}">
                                                                                    <input type="hidden" class="form-control" name="answer_type[]" value="{{ $value[$i]['answer_type'] }}">
                                                                                    @if($value[$i]['answer_type'] == 'des')
                                                                                        <input type="hidden" name="question[]" value="null" class="form-control">
                                                                                    @else
                                                                                        <input type="hidden" class="form-control" name="question[]" value="{{ $value[$i]['question'] }}">
                                                                                    @endif
                                                                    
                                                                    
                                                                                    @if ($value[$i]['answer_type'] == 'shortAnswer')
                                                                                        <input type="text" class="form-control" name="ans1" value="input">
                                                                                    @endif
                                                                                    @if ($value[$i]['answer_type'] == 'longAnswer')
                                                                                        <textarea rows="3" class="form-control" name="ans2"></textarea>
                                                                                    @endif

                                                                                    @if ($value[$i]['answer_type'] == 'singleAnswer')

                                                                                        <div class="radio-list">
                                                                                            @php 
                                                                                                $max1 = count(json_decode($value[$i]['3ans']))==1 ? 0 : count(json_decode($value[$i]['3ans']))-1 ;
                                                                                            @endphp
                                                                                            @for ($v = 0; $v <= $max1; $v++)
                                                                                                @php
                                                                                                    $ansdata = json_decode($value[$i]['3ans'])[$v];
                                                                                                @endphp

                                                                                                <label class="radio">
                                                                                                <input type="radio" value="{{ $ansdata }}" >
                                                                                                <input type="hidden" name="ans3[]" value="{{ $ansdata }}" >
                                                                                                <span></span>{{ $ansdata }}</label>
                                                                                            @endfor
                                                                                        </div>
                                                                                    @endif
                                                                                    @if ($value[$i]['answer_type'] == 'singleCheckbox')
                                                                                        <input type="checkbox" class="form-control" name="ans4">
                                                                                    @endif
                                                                                    @if ($value[$i]['answer_type'] == 'multipleCheckbox')
                                                                                        <div class="checkbox-list">
                                                                                            @php
                                                                                                $max1 = count(json_decode($value[$i]['5ans']))==1 ? 0 : count(json_decode($value[$i]['5ans']))-1 ;
                                                                                            @endphp
                                                                                            @for ($v = 0; $v <= $max1; $v++)
                                                                                                @php $ansdata = json_decode($value[$i]['5ans'])[$v]; @endphp
                                                                        
                                                                                                <label class="checkbox">
                                                                                                <input type="checkbox" value="{{ $ansdata }}" >
                                                                                                <input type="hidden" name="ans5[]" value="{{ $ansdata }}" >
                                                                                                <span></span>{{ $ansdata }}</label>
                                                                                            @endfor
                                                                                        </div>
                                                                                    @endif
                                                                                    @if ($value[$i]['answer_type'] == 'dropdown')
                                                                                        <div class="dropdown">
                                                                                        <select class="custom-select" name="6ans" >
                                                                                        <option disable>Choose...</option>
                                                                                        @php 
                                                                                            $max1 = count(json_decode($value[$i]['6ans']))==1 ? 0 : count(json_decode($value[$i]['6ans']))-1 ;
                                                                                        @endphp
                                                                                        @for ($v = 0; $v <= $max1; $v++)
                                                                                                @php $ansdata = json_decode($value[$i]['6ans'])[$v]; @endphp
                                                                                            <option selected value="{{  $ansdata  }}">{{  $ansdata  }}</option>
                                                                                        @endfor
                                                                                        </select>

                                                                                        @for ($v = 1; $v <= $max1; $v++)
                                                                                            @php $ansdata = json_decode($value[$i]['6ans'])[$v]; @endphp
                                                                                            <input type="hidden" name="ans6[]" value="{{  $ansdata  }}" >
                                                                                        @endfor
                                                                                        </div>
                                                                                    @endif
                                                                                    @if ($value[$i]['answer_type'] == 'yesOrNo')
                                                                                        <div class="form-group ml-2 w-100 d-flex extra-time">
                                                                                        <label class="option m-3">
                                                                                        <span class="option-control">
                                                                                        <span class="radio">
                                                                                        <input type="radio" value="Yes">
                                                                                        <input type="hidden"  name="ans7[]" value="Yes">
                                                                                        <span></span>
                                                                                        </span>
                                                                                        </span>
                                                                                        <span class="option-label">
                                                                                        <span class="option-head">
                                                                                        <span class="option-title">Yes</span>
                                                                                        </span>
                                                                                        </span>
                                                                                        </label>
                                                                                        <label class="option m-3">
                                                                                        <span class="option-control">
                                                                                        <span class="radio">
                                                                                        <input type="radio" value="No">
                                                                                        <input type="hidden" name="ans7[]" value="No">
                                                                                        <span></span>
                                                                                        </span>
                                                                                        </span>
                                                                                        <span class="option-label">
                                                                                        <span class="option-head">
                                                                                        <span class="option-title">No</span>
                                                                                        </span>
                                                                                        </span>
                                                                                        </label>
                                                                                        </div>
                                                                                    @endif
                                                                                    @if ($value[$i]['answer_type'] == 'des')
                                                                                        <textarea rows="3" class="form-control" name="ans8">{{ $value[$i]['8ans'] }}</textarea>
                                                                                    @endif
                                                                    
                                                                                </div>
                                                                            </div>
                                                                            <div id="addnewqnainsec">
                                                                @endif
                                                                                    @if ($i >= 1)
                                                                                        <div class="card-body showdata">
                                                                                            <div class="card" style="display: none;">
                                                                                                <div class="card-body bg-content">
                                                                                                    <div class="d-flex justify-content-between ">
                                                                                                        <div class="form-group mr-2 w-100">
                                                                                                            <label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label>
                                                                                                            <select class="form-control dropDownId" data-id="{{ $value[0]['section_id'] }}" tabindex="null">
                                                                                                                <option data-icon="fas fa-grip-lines" @if($value[$i]['answer_type']=='shortAnswer') {{ 'selected' }} @endif value="shortAnswer">
                                                                                                                    Short answer
                                                                                                                </option> 
                                                                                                                <option @if($value[$i]['answer_type']=='longAnswer') {{ 'selected' }} @endif data-icon="fas fa-align-left" value="longAnswer">
                                                                                                                    Long answer</option>
                                                                                                                <option data-icon="far fa-dot-circle" value="singleAnswer" @if($value[$i]['answer_type']=='singleAnswer') {{ 'selected' }} @endif >
                                                                                                                    Single answer
                                                                                                                </option>
                                                                                                                <option @if($value[$i]['answer_type']=='singleCheckbox') {{ 'selected' }} @endif data-icon="far fa-check-square" value="singleCheckbox">
                                                                                                                    Singlecheckbox
                                                                                                                </option>
                                                                                                                <option data-icon="fas fa-tasks" value="multipleCheckbox" @if($value[$i]['answer_type']=='multipleCheckbox') {{ 'selected' }} @endif>
                                                                                                                    Multiplechoice
                                                                                                                </option>
                                                                                                                <option data-icon="far fa-caret-square-down" value="dropdown" @if($value[$i]['answer_type']=='dropdown') {{ 'selected' }} @endif>
                                                                                                                    Drop-down</option>
                                                                                                                <option data-icon="fas fa-adjust" value="yesOrNo" @if($value[$i]['answer_type']=='yesOrNo') {{ 'selected' }} @endif>Yes
                                                                                                                    or No</option>
                                                                                                                <option data-icon="far fa-font-case" value="des" @if($value[$i]['answer_type']=='des') {{ 'selected' }} @endif >
                                                                                                                    Description text
                                                                                                                </option>
                                                                                                            </select>
                                                                                                        </div>

                                                                                                        <div class="form-group ml-2 w-100">
                                                                                                            <label class="font-weight-bolder" for="exampleSelect1">Question</label>
                                                                                                            <input type="text" class="form-control" id="question" placeholder="New question copy" value="{{ $value[$i]['question'] }}">
                                                                                                        </div>

                                                                                                    </div>
                                                                                                    <div class="inputappend{{ $value[$i]['section_id'] }}"></div>
                                                                                                    <hr>
                                                                                                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                                                                                                        <div class="">
                                                                                                            <div class="form-group mb-0">
                                                                                                                <div class="switch switch-sm switch-icon switch-success"
                                                                                                                    style="line-height: 28px;">
                                                                                                                    <label
                                                                                                                        class="d-flex align-item-center font-weight-bolder">
                                                                                                                        <input type="checkbox" @if($value[$i]['required']==1) checked="checked" @endif id="required">
                                                                                                                        <span></span>&nbsp;Required
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="d-flex align-items-center">
                                                                                                            <span
                                                                                                                class="border-right p-3">
                                                                                                                <i
                                                                                                                    class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i>
                                                                                                                <i
                                                                                                                    class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i>
                                                                                                            </span>
                                                                                                            <span
                                                                                                                class="border-right p-3">
                                                                                                                <button type="button" class="mx-2 btn btn-sm btn-white copyNewQnaCus"><i
                                                                                                                    class="mx-1 far fa-clone fa-2x"></i></button>
                                                                                                                    <button type="button" class="mx-2 btn btn-sm btn-white deleteNewQnaCus"><i
                                                                                                                    class="mx-1 fas fa-trash fa-2x text-danger"></i></button>
                                                                                                            </span>
                                                                                                            <span class="p-3">
                                                                                                                <button
                                                                                                                    type="button"
                                                                                                                    class="mx-2 btn btn-sm btn-white closeqna"><i
                                                                                                                        class="p-0 fa fa-times"></i></button>
                                                                                                                <button
                                                                                                                    type="button"
                                                                                                                    class="mx-2 btn btn-sm btn-primary saveques"><i
                                                                                                                        class="p-0 fas fa-check"></i></button>
                                                                                                            </span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="form-group showedit">
                                                                                                <form name="qnaform{{ $value[0]['section_id'] }}" id="qnaform">

                                                                                                @if ($value[$i]['answer_type'] == 'des')
                                                                                
                                                                                                @else
                                                                                                    <label class="font-weight-bolder">{{ $value[$i]['question'] }}</label>
                                                                                                @endif
                                                                                                <input type="hidden" name="title[]"value="{{ $value[0]['title'] }}">
                                                                                                <input type="hidden" name="des[]" value="{{ $value[0]['des'] }}">
                                                                                                <input type="hidden" name="sectionid[]" id="sectionid" value="{{ $value[0]['section_id'] }}">
                                                                                                <input type="hidden" class="form-control" name="required[]" value="{{ $value[$i]['required'] }}">
                                                                                                <input type="hidden" class="form-control" name="answer_type[]" value="{{ $value[$i]['answer_type'] }}">
                                                                                                @if($value[$i]['answer_type'] == 'des')
                                                                                                    <input type="hidden" name="question[]" value="null" class="form-control">
                                                                                                @else
                                                                                                    <input type="hidden" class="form-control" name="question[]" value="{{ $value[$i]['question'] }}">
                                                                                                @endif
                                                                                
                                                                                
                                                                                                @if ($value[$i]['answer_type'] == 'shortAnswer')
                                                                                                    <input type="text" class="form-control" name="ans1" value="input">
                                                                                                @endif
                                                                                                @if ($value[$i]['answer_type'] == 'longAnswer')
                                                                                                    <textarea rows="3" class="form-control" name="ans2"></textarea>
                                                                                                @endif

                                                                                                @if ($value[$i]['answer_type'] == 'singleAnswer')

                                                                                                    <div class="radio-list">
                                                                                                        @php 
                                                                                                            $max1 = count(json_decode($value[$i]['3ans']))==1 ? 0 : count(json_decode($value[$i]['3ans']))-1 ;
                                                                                                        @endphp
                                                                                                        @for ($v = 0; $v <= $max1; $v++)
                                                                                                            @php
                                                                                                                $ansdata = json_decode($value[$i]['3ans'])[$v];
                                                                                                            @endphp

                                                                                                            <label class="radio">
                                                                                                            <input type="radio" value="{{ $ansdata }}" >
                                                                                                            <input type="hidden" name="ans3[]" value="{{ $ansdata }}" >
                                                                                                            <span></span>{{ $ansdata }}</label>
                                                                                                        @endfor
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if ($value[$i]['answer_type'] == 'singleCheckbox')
                                                                                                    <input type="checkbox" class="form-control" name="ans4">
                                                                                                @endif
                                                                                                @if ($value[$i]['answer_type'] == 'multipleCheckbox')
                                                                                                    <div class="checkbox-list">
                                                                                                        @php
                                                                                                            $max1 = count(json_decode($value[$i]['5ans']))==1 ? 0 : count(json_decode($value[$i]['5ans']))-1 ;
                                                                                                        @endphp
                                                                                                        @for ($v = 0; $v <= $max1; $v++)
                                                                                                            @php $ansdata = json_decode($value[$i]['5ans'])[$v]; @endphp
                                                                                    
                                                                                                            <label class="checkbox">
                                                                                                            <input type="checkbox" value="{{ $ansdata }}" >
                                                                                                            <input type="hidden" name="ans5[]" value="{{ $ansdata }}" >
                                                                                                            <span></span>{{ $ansdata }}</label>
                                                                                                        @endfor
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if ($value[$i]['answer_type'] == 'dropdown')
                                                                                                    <div class="dropdown">
                                                                                                    <select class="custom-select" name="6ans" >
                                                                                                    <option disable>Choose...</option>
                                                                                                    @php 
                                                                                                        $max1 = count(json_decode($value[$i]['6ans']))==1 ? 0 : count(json_decode($value[$i]['6ans']))-1 ;
                                                                                                    @endphp
                                                                                                    @for ($v = 0; $v <= $max1; $v++)
                                                                                                            @php $ansdata = json_decode($value[$i]['6ans'])[$v]; @endphp
                                                                                                        <option selected value="{{  $ansdata  }}">{{  $ansdata  }}</option>
                                                                                                    @endfor
                                                                                                    </select>

                                                                                                    @for ($v = 1; $v <= $max1; $v++)
                                                                                                        @php $ansdata = json_decode($value[$i]['6ans'])[$v]; @endphp
                                                                                                        <input type="hidden" name="ans6[]" value="{{  $ansdata  }}" >
                                                                                                    @endfor
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if ($value[$i]['answer_type'] == 'yesOrNo')
                                                                                                    <div class="form-group ml-2 w-100 d-flex extra-time">
                                                                                                    <label class="option m-3">
                                                                                                    <span class="option-control">
                                                                                                    <span class="radio">
                                                                                                    <input type="radio" value="Yes">
                                                                                                    <input type="hidden"  name="ans7[]" value="Yes">
                                                                                                    <span></span>
                                                                                                    </span>
                                                                                                    </span>
                                                                                                    <span class="option-label">
                                                                                                    <span class="option-head">
                                                                                                    <span class="option-title">Yes</span>
                                                                                                    </span>
                                                                                                    </span>
                                                                                                    </label>
                                                                                                    <label class="option m-3">
                                                                                                    <span class="option-control">
                                                                                                    <span class="radio">
                                                                                                    <input type="radio" value="No">
                                                                                                    <input type="hidden" name="ans7[]" value="No">
                                                                                                    <span></span>
                                                                                                    </span>
                                                                                                    </span>
                                                                                                    <span class="option-label">
                                                                                                    <span class="option-head">
                                                                                                    <span class="option-title">No</span>
                                                                                                    </span>
                                                                                                    </span>
                                                                                                    </label>
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if ($value[$i]['answer_type'] == 'des')
                                                                                                    <textarea rows="3" class="form-control" name="ans8">{{ $value[$i]['8ans'] }}</textarea>
                                                                                                @endif
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                @if ($i == 0)
                                                                            </div>
                                                                            @endif
                                                                            @if($i==$max)
                                                                                <div class="card-footer" style="1px solid #EBEDF3;">
                                                                                    <span
                                                                                        class="cursor-pointer text-blue addnewqnatomodel"
                                                                                        data-toggle="modal"
                                                                                        data-target="#addNewQuestionModal">
                                                                                        <i class="text-blue fa fa-plus mr-3"></i>Add
                                                                                        a new Question oritem
                                                                                    </span>
                                                                                </div><br><br>
                                                                            @endif
                                                                            @if ($i == 0)
                                                                        </form>
                                                                    </div>
                                                                    {{--  @break($i == 0)  --}}
                                                                @endif
                                                            @endfor
                                                        @endif
                                                    @endforeach
                                                    @php
                                                        $maxsec = max($seid);
                                                    @endphp
                                                </div>
                                            </div>
                                            <div class="card w-70 mx-auto my-6 " data-toggle="modal"
                                                data-target="#addNewSectionModal">
                                                <div class="rounded cursor-pointer py-2"
                                                    style="background-color: #e5f1ff;border: 1px solid #037aff !important;">
                                                    <h6 class="text-center font-weight-bolder">Drag and drop or <span
                                                            class="text-blue">click
                                                            here</span> to add a
                                                        section</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="preview" role="tabpanel" aria-labelledby="preview-tab-1">
                                            @foreach ($groupdata as $value1)
                                           
                                            <div class="card w-50 mx-auto my-4 preview-tab" @if($value1[0]['section_id']==1) style="display: block" @else style="display: none" @endif>
                                                <div class="card-header text-center">
                                                    <h6 class="mb-4 badge badge-secondary">CONSULTATION FORM PREVIEW
                                                    </h6>
                                                    <h6>step <span class="preview-current-steps setp1"></span> of <span class="total-preview-tab setp2"></span></h6>
                                                    <h3 class="font-weight-bolder title">@if (isset($value1[0]['section_title'])) {{ $value1[0]['section_title'] }} @else {{ $value1[0]['title'] }} @endif</h3>
                                                    <h6 class="m-0 text-dakr-50 sectionDes">@if (isset($value1[0]['section_des'])) {{ $value1[0]['section_des'] }} @else {{ $value1[0]['des'] }} @endif</h6>
                                                </div>

                                                <div class="apppreview">
                                                    @if (isset($value1[0]['section_title']))
                                                        <div class="preview-tab1" >
                                                            <div class="card-body">
                                                                <div class="form-group fnameshow" @if ($value1[0]['first_name'] == 1) style="display: block" @else style="display: none" @endif >
                                                                    <label class="font-weight-bolder">First name
                                                                    </label>
                                                                    <input type="text" class="form-control" name="fname">
                                                                </div>
                                
                                                                <div class="form-group lnameshow" @if ($value1[0]['last_name'] == 1) style="display: block" @else style="display: none" @endif >
                                                                    <label class="font-weight-bolder">Last name
                                                                    </label>
                                                                    <input type="text" class="form-control" name="lname">
                                                                </div>
                                
                                                                <div class="form-group emailshow" @if ($value1[0]['email'] == 1) style="display: block" @else style="display: none" @endif >
                                                                    <label class="font-weight-bolder">Email
                                                                    </label>
                                                                    <input type="email"  class="form-control" >
                                                                </div>
                                
                                                                <div class="form-group showbirthday" @if ($value1[0]['birthday'] == 1) style="display: block" @else style="display: none" @endif >
                                                                    <label class="font-weight-bolder">Brithday
                                                                    </label>
                                                                    <input type="date" class="form-control" >
                                                                </div>
																
																<div class="form-group showmobile" @if ($value1[0]['mobile'] == 1) style="display: block" @else style="display: none" @endif>
																	<label class="font-weight-bolder">
																		Country Code
																	</label>
																	<select class="form-control" name="country_code_select" id="country_code_select">
																	@if($Country)
																		@foreach($Country as $CountryData)
																			<option value="{{ $CountryData['phonecode'] }}" @if($CountryData['phonecode'] == $value1[0]['country_code']) selected @endif>{{ $CountryData['name'] }} +{{ $CountryData['phonecode'] }}</option>
																		@endforeach
																	@endif	
																	</select>
																</div>
																
                                                                <div class="form-group showmobile" @if ($value1[0]['mobile'] == 1) style="display: block" @else style="display: none" @endif >
                                                                    <label class="font-weight-bolder">Mobile Number
                                                                    </label>
                                                                    <input type="text" class="form-control" >
                                                                </div>
                                
                                                                <div class="form-group showgender" @if ($value1[0]['gender']) ) style="display: block" @else style="display: none" @endif >
                                                                    <label class="font-weight-bolder">Gender
                                                                    </label>
                                                                    <select readonly class="form-control"> 
																		<option value="">Please select</option>
																		<option value="Male">Male</option>
																		<option value="Female">Female</option>
																		<option value="Other">Other</option>
																		<option value="I dont want to share">I dont want to share</option>
																	</select>
                                                                </div>
                                
                                                                <div class="form-group showaddress" @if ($value1[0]['address'] == 1) style="display: block" @else style="display: none" @endif >
                                                                    <label class="font-weight-bolder">Address
                                                                    </label>
                                                                    <textarea rows="4" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if (isset($value1[0]['title']))
                                                        @php $max1 = count($value1)==1 ? 0 : count($value1)-1 ; @endphp
                                                        @for ($i = 0; $i <= $max1; $i++)
                                                            
                                                            <div class="preview-tab1" >
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        @if ($i == 0)
                                                                            @if ($value1[$i]['answer_type'] == 'des')
                                                                                        
                                                                            @else
                                                                                <label class="font-weight-bolder">{{ $value1[$i]['question'] }}</label>
                                                                            @endif
                                                                            <input type="hidden" class="form-control" name="required[]" value="{{ $value1[$i]['required'] }}">
                                                                            <input type="hidden" class="form-control" name="answer_type[]" value="{{ $value1[$i]['answer_type'] }}">
                                                                            @if($value1[$i]['answer_type'] == 'des')
                                                                                <input type="hidden" name="question[]" value="null" class="form-control">
                                                                            @else
                                                                                <input type="hidden" class="form-control" name="question[]" value="{{ $value1[$i]['question'] }}">
                                                                            @endif
                                                            
                                                                            @if ($value1[$i]['answer_type'] == 'shortAnswer')
                                                                                <input type="text" class="form-control" name="ans1" value="input">
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'longAnswer')
                                                                                <textarea rows="3" class="form-control" name="ans2"></textarea>
                                                                            @endif
        
                                                                            @if ($value1[$i]['answer_type'] == 'singleAnswer')
                                                                                <div class="radio-list">
                                                                                    @php 
                                                                                        $max1 = count(json_decode($value1[$i]['3ans']))==1 ? 0 : count(json_decode($value1[$i]['3ans']))-1 ;
                                                                                    @endphp
                                                                                    @for ($v = 0; $v <= $max1; $v++)
                                                                                        @php
                                                                                            $ansdata = json_decode($value1[$i]['3ans'])[$v];
                                                                                        @endphp
                                                                                        <label class="radio">
                                                                                            <input type="radio" value="{{ $ansdata }}" >
                                                                                            <input type="hidden" name="ans3[]" value="{{ $ansdata }}" >
                                                                                            <span></span>{{ $ansdata }}
                                                                                        </label>
                                                                                    @endfor
                                                                                </div>
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'singleCheckbox')
                                                                                <input type="checkbox" class="form-control" name="ans4">
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'multipleCheckbox')
                                                                                <div class="checkbox-list">
                                                                                    @php
                                                                                        $max1 = count(json_decode($value1[$i]['5ans']))==1 ? 0 : count(json_decode($value1[$i]['5ans']))-1 ;
                                                                                    @endphp
                                                                                    @for ($v = 0; $v <= $max1; $v++)
                                                                                        @php $ansdata = json_decode($value1[$i]['5ans'])[$v]; @endphp
                                                                                        <label class="checkbox">
                                                                                            <input type="checkbox" value="{{ $ansdata }}" >
                                                                                            <input type="hidden" name="ans5[]" value="{{ $ansdata }}" >
                                                                                            <span></span>{{ $ansdata }}
                                                                                        </label>
                                                                                    @endfor
                                                                                </div>
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'dropdown')
                                                                                <div class="dropdown">
                                                                                    <select class="custom-select" name="6ans" >
                                                                                        <option disable>Choose...</option>
                                                                                        @php 
                                                                                            $max1 = count(json_decode($value1[$i]['6ans']))==1 ? 0 : count(json_decode($value1[$i]['6ans']))-1 ;
                                                                                        @endphp
                                                                                        @for ($v = 0; $v <= $max1; $v++)
                                                                                            @php $ansdata = json_decode($value1[$i]['6ans'])[$v]; @endphp
                                                                                            <option selected value="{{  $ansdata  }}">{{  $ansdata  }}</option>
                                                                                        @endfor
                                                                                    </select>
        
                                                                                    @for ($v = 0; $v <= $max1; $v++)
                                                                                        @php $ansdata = json_decode($value1[$i]['6ans'])[$v]; @endphp
                                                                                        <input type="hidden" name="ans6[]" value="{{  $ansdata  }}" >
                                                                                    @endfor
                                                                                </div>
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'yesOrNo')
                                                                                <div class="form-group ml-2 w-100 d-flex extra-time">
                                                                                    <label class="option m-3">
                                                                                        <span class="option-control">
                                                                                            <span class="radio">
                                                                                                <input type="radio" value="Yes">
                                                                                                <input type="hidden"  name="ans7[]" value="Yes">
                                                                                                <span></span>
                                                                                            </span>
                                                                                        </span>
                                                                                        <span class="option-label">
                                                                                            <span class="option-head">
                                                                                                <span class="option-title">Yes</span>
                                                                                            </span>
                                                                                        </span>
                                                                                    </label>
                                                                                    <label class="option m-3">
                                                                                        <span class="option-control">
                                                                                            <span class="radio">
                                                                                                <input type="radio" value="No">
                                                                                                <input type="hidden" name="ans7[]" value="No">
                                                                                                <span></span>
                                                                                            </span>
                                                                                        </span>
                                                                                        <span class="option-label">
                                                                                            <span class="option-head">
                                                                                                <span class="option-title">No</span>
                                                                                            </span>
                                                                                        </span>
                                                                                    </label>
                                                                                </div>
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'des')
                                                                                <textarea rows="3" class="form-control" name="ans8">{{ $value1[$i]['8ans'] }}</textarea>
                                                                            @endif
                                                                        @endif
                                                                        @if ($i >= 1)
                                                                            <hr>
																			@if ($value1[$i]['answer_type'] == 'des')
                                                                                        
                                                                            @else
                                                                                <label class="font-weight-bolder">{{ $value1[$i]['question'] }}</label>
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'shortAnswer')
                                                                                <input type="text" class="form-control" name="ans1" value="input">
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'longAnswer')
                                                                                <textarea rows="3" class="form-control" name="ans2"></textarea>
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'singleAnswer')
                                                                                <div class="radio-list">
                                                                                    @php 
                                                                                        $cont1 = count(json_decode($value1[$i]['3ans']))==1 ? 0 : count(json_decode($value1[$i]['3ans']))-1 ;
                                                                                    @endphp
                                                                                    @for ($v = 0; $v <= $cont1; $v++)
                                                                                        @php
                                                                                            $ansdata = json_decode($value1[$i]['3ans'])[$v];
                                                                                        @endphp
                                                                                        <label class="radio">
                                                                                            <input type="radio" value="{{ $ansdata }}" >
                                                                                            <input type="hidden" name="ans3[]" value="{{ $ansdata }}" >
                                                                                            <span></span>{{ $ansdata }}
                                                                                        </label>
                                                                                    @endfor
                                                                                </div>
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'singleCheckbox')
                                                                                <input type="checkbox" class="form-control" name="ans4">
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'multipleCheckbox')
                                                                                <div class="checkbox-list">
                                                                                    @php
                                                                                        $cont1 = count(json_decode($value1[$i]['5ans']))==1 ? 0 : count(json_decode($value1[$i]['5ans']))-1 ;
                                                                                    @endphp
                                                                                    @for ($v = 0; $v <= $cont1; $v++)
                                                                                        @php $ansdata = json_decode($value1[$i]['5ans'])[$v]; @endphp
                                                                
                                                                                        <label class="checkbox">
                                                                                        <input type="checkbox" value="{{ $ansdata }}" >
                                                                                        <input type="hidden" name="ans5[]" value="{{ $ansdata }}" >
                                                                                        <span></span>{{ $ansdata }}</label>
                                                                                    @endfor
                                                                                </div>
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'dropdown')
                                                                                <div class="dropdown">
                                                                                    <select class="custom-select" name="6ans" >
                                                                                        <option disable>Choose...</option>
                                                                                        @php 
                                                                                            $cont1 = count(json_decode($value1[$i]['6ans']))==1 ? 0 : count(json_decode($value1[$i]['6ans']))-1 ;
                                                                                        @endphp
                                                                                        @for ($v = 0; $v <= $cont1; $v++)
                                                                                                @php $ansdata = json_decode($value1[$i]['6ans'])[$v]; @endphp
                                                                                            <option selected value="{{  $ansdata  }}">{{  $ansdata  }}</option>
                                                                                        @endfor
                                                                                    </select>
        
                                                                                    @for ($v = 1; $v <= $cont1; $v++)
                                                                                        @php $ansdata = json_decode($value1[$i]['6ans'])[$v]; @endphp
                                                                                        <input type="hidden" name="ans6[]" value="{{  $ansdata  }}" >
                                                                                    @endfor
                                                                                </div>
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'yesOrNo')
                                                                                <div class="form-group ml-2 w-100 d-flex extra-time">
                                                                                    <label class="option m-3">
                                                                                        <span class="option-control">
                                                                                            <span class="radio">
                                                                                                <input type="radio" value="Yes">
                                                                                                <input type="hidden"  name="ans7[]" value="Yes">
                                                                                                <span></span>
                                                                                            </span>
                                                                                        </span>
                                                                                        <span class="option-label">
                                                                                            <span class="option-head">
                                                                                                <span class="option-title">Yes</span>
                                                                                            </span>
                                                                                        </span>
                                                                                    </label>
                                                                                    <label class="option m-3">
                                                                                        <span class="option-control">
                                                                                            <span class="radio">
                                                                                                <input type="radio" value="No">
                                                                                                <input type="hidden" name="ans7[]" value="No">
                                                                                                <span></span>
                                                                                            </span>
                                                                                        </span>
                                                                                        <span class="option-label">
                                                                                            <span class="option-head">
                                                                                                <span class="option-title">No</span>
                                                                                            </span>
                                                                                        </span>
                                                                                    </label>
                                                                                </div>
                                                                            @endif
                                                                            @if ($value1[$i]['answer_type'] == 'des')
                                                                                <textarea rows="3" class="form-control" name="ans8">{{ $value1[$i]['8ans'] }}</textarea>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    @endif
                                                </div>
                                                <div class="card-footer text-right">
                                                    <button class="preview-next btn btn-primary"
                                                        onclick="nextPrevPreview(1)">Next
                                                        Step</button>
                                                    <button class="preview-previous btn btn-white"
                                                        onclick="nextPrevPreview(-1)">Previous Step</button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="add-voucher-tab">
                        <div class="row">
                            <div class="col-12 col-md-4 border-right"
                                style="height: calc(100vh - 90px);overflow-y: scroll;overflow-x: hidden;">
                                <form name="formdetail" id="formdetail">
                                    @csrf
                                    <h2 class="font-weight-bolder my-3">Consultation form details</h2>
                                    <div class="form-group">
                                        <label class="font-weight-bolder">Consultation form name</label>
                                        <input type="text" class="form-control" value="{{ $conform->name }}" id="name" name="name">
                                        <input type="hidden" class="form-control" value="{{ $conform->id }}" id="id" name="formid">
                                        <span class="navi-text text-danger" id="nameerror"></span>
                                    </div>
                                    <hr>
                                    <h3 class="font-weight-bolder">
                                        Complete consultation form
                                    </h3>
                                    <div class="form-group">
                                        <div class="form-group ml-2 w-100 d-flex extra-time">
                                            <label class="option m-3">
                                                <span class="option-control">
                                                    <span class="radio">
                                                        <input type="radio" name="send_request" value="before-appoinment"
                                                            @if($conform->send_request==0)checked="checked" @endif>
                                                        <span></span>
                                                    </span>
                                                </span>
                                                <span class="option-label">
                                                    <span class="option-head">
                                                        <span class="option-title">Before appointment</span>
                                                    </span>
                                                    <span class="option-body text-dark">
                                                        Automatically send to clients to fill out before their appointment.
                                                    </span>
                                                </span>
                                            </label>
                                            <!--label class="option m-3">
                                                <span class="option-control">
                                                    <span class="radio">
                                                        <input type="radio" disabled name="send_request" @if($conform->send_request==1)checked="checked" @endif value="manually">
                                                        <span></span>
                                                    </span>
                                                </span>
                                                <span class="option-label">
                                                    <span class="option-head">
                                                        <span class="option-title">
                                                            Manually
                                                            <span class="badge badge-success">Soon</span></span>
                                                    </span>
                                                    <span class="option-body text-dark">
                                                        You decide when to send to your clients.
                                                    </span>
                                                </span>
                                            </label-->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bolder">Ask clients to complete</label>
                                        <select class="form-control" name="complete">
                                            <option value="0" @if($conform->complete==0) selected @endif >Every time they book an
                                                appointment</option>
                                            <option value="1" @if($conform->complete==1) selected @endif>Only once</option>
                                        </select>
                                    </div>
                                    <div class="form-group" style="position: relative;">
                                        <label class="font-weight-bolder">Ask clients to complete when booking</label>
                                        <input type="text" id="serviceInput" readonly="" value="{{ count(json_decode($conform->service_id)) }} services"
                                            class="form-control form-control-lg" placeholder="All services"
                                            data-toggle="modal" data-target="#servicesModal" style="cursor: pointer;">
                                        <a href="" class="chng_popup" data-toggle="modal" data-target="#servicesModal">
                                            Edit</a>
                                        {{-- select service modal --}}
                                        <div class="modal" id="servicesModal">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header d-flex justify-content-between">
                                                        <h4 class="modal-title">Select services</h4>
                                                        <button type="button" class="text-dark close"
                                                            data-dismiss="modal">×</button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <div class="input-icon input-icon-right">
                                                                <input type="text" class="rounded-0 form-control" placeholder="Scan barcode or search any item" id="searchForCategory">
                                                                <span>
                                                                    <i class="flaticon2-search-1 icon-md"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <hr class="m-0">

                                                        <div class="multicheckbox">
                                                            <ul id="treeview">
																<li>
																	<label for="all" class="checkbox allService">
																		<input type="checkbox" name="all" id="all" @if($TotalFormServices == $TotalServices) checked @endif>
																		<span></span>
																		All Services
																	</label>
																	<ul>
																		@foreach($cat as $serviceKey => $serviceValue)
																			@php 
																				$checkFlag = 0;
																			@endphp
																			@foreach ($serviceValue['service'] as $serviceData)
																				@if(in_array($serviceData['id'],json_decode($conform->service_id))) 
																					@php $checkFlag++ @endphp
																				@endif
																			@endforeach
																		
																			<li>
																				<label for="all-{{ $serviceValue['category_title'] }}" class="checkbox">
																					<input type="checkbox" id="all-{{ $serviceValue['category_title'] }}" @if($checkFlag > 0) checked @endif>
																					<span></span>
																					{{ $serviceValue['category_title'] }}
																				</label>
																				<ul>
																					@foreach ($serviceValue['service'] as $serviceData)
																						@foreach ($serviceData['service_price'] as $priceKey => $servicePrice)
																							<li>
																								<label for="all-{{ $serviceValue['category_title'] }}-{{ $serviceData['id'] }}-{{ $priceKey }}" class="checkbox">
																									<input type="checkbox" name="value_checkbox[]" id="all-{{ $serviceValue['category_title'] }}-{{ $serviceData['id'] }}-{{ $priceKey }}" value="{{ $serviceData['id'] }}" @if(in_array($serviceData['id'],json_decode($conform->service_id))) checked @endif>
																									<span></span>
																									<div class="d-flex align-items-center w-100">
																										<span class="m-0">
																											<?php
																											$totalMinutes = $servicePrice['duration'];
																											$hours = intval($totalMinutes/60);
																											$minutes = $totalMinutes - ($hours * 60);
																											$duration = $hours."h ".$minutes."min";
																											?>
																											{{ $serviceData['service_name'] }}
																											<p class="m-0 text-muted">p{{ $priceKey + 1 }},{{ $duration }}</p>
																										</span>
																										<span class="ml-auto">
																											CA ${{ $servicePrice['price'] }}
																										</span>
																									</div>
																								</label>
																							</li>
																						@endforeach
																					@endforeach
																				</ul>
																			</li>	
																		@endforeach
																	</ul>
																</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary"
                                                            data-dismiss="modal">Select Services</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- end --}}
                                    </div>
                                    <hr>
                                    <h3 class="font-weight-bolder">Signature</h3>
                                    <div class="form-group mb-0">
                                        <div class="switch switch-md switch-icon switch-success" style="line-height: 28px;">
                                            <label class="d-flex align-item-center font-weight-bolder">
                                                <input type="checkbox" checked="checked" name="signature">
                                                <span></span>&nbsp; Require client signatures
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 col-md-8 p-4"
                                style="height: calc(100vh - 90px);overflow-y: scroll;overflow-x: hidden;">
                                @foreach ($groupdata as $value2)
                                <div class="card w-70 mx-auto forms-tab" @if($value2[0]['section_id']==1) style="display: block" @else style="display: none" @endif>
                                    <div class="card-header text-center">
                                        <h6 class="mb-4 badge badge-secondary">CONSULTATION FORM PREVIEW</h6>
                                        <h6>step <span class="forms-current-steps">1</span> of <span class="total-forms-tab">1</span></h6>
                                        @if (isset($value2[0]['section_title']))
                                        <h3 class="font-weight-bolder title">@if (isset($value1[0]['section_title'])) {{ $value1[0]['section_title'] }} @else {{ $value1[0]['title'] }} @endif</h3>
                                        <h6 class="sectionDes ">@if (isset($value1[0]['section_des'])) {{ $value1[0]['section_des'] }} @else {{ $value1[0]['des'] }} @endif</h6>
                                        @endif
                                    </div>
                                    
                                    <div class="appendpre">
                                        @if (isset($value2[0]['section_title']))
                                            <div class="forms-tab1" >
                                                <div class="card-body">
                                                    <div class="form-group fnameshow" @if ($value2[0]['first_name'] == 1) style="display: block" @else style="display: none" @endif >
                                                        <label class="font-weight-bolder">First name
                                                        </label>
                                                        <input type="text" class="form-control" name="fname">
                                                    </div>
                    
                                                    <div class="form-group lnameshow" @if ($value2[0]['last_name'] == 1) style="display: block" @else style="display: none" @endif >
                                                        <label class="font-weight-bolder">Last name
                                                        </label>
                                                        <input type="text" class="form-control" name="lname">
                                                    </div>
                    
                                                    <div class="form-group emailshow" @if ($value2[0]['email'] == 1) style="display: block" @else style="display: none" @endif >
                                                        <label class="font-weight-bolder">Email
                                                        </label>
                                                        <input type="email"  class="form-control" >
                                                    </div>
                    
                                                    <div class="form-group showbirthday" @if ($value2[0]['birthday'] == 1) style="display: block" @else style="display: none" @endif >
                                                        <label class="font-weight-bolder">Brithday
                                                        </label>
                                                        <input type="date" class="form-control" >
                                                    </div>
													
													<div class="form-group showmobile" @if ($value2[0]['mobile'] == 1) style="display: block" @else style="display: none" @endif>
														<label class="font-weight-bolder">
															Country Code
														</label>
														<select class="form-control" name="country_code_select" id="country_code_select">
														@if($Country)
															@foreach($Country as $CountryData)
																<option value="{{ $CountryData['phonecode'] }}" @if($CountryData['phonecode'] == $value2[0]['country_code']) selected @endif>{{ $CountryData['name'] }} +{{ $CountryData['phonecode'] }}</option>
															@endforeach
														@endif	
														</select>
													</div>
													
                                                    <div class="form-group showmobile" @if ($value2[0]['mobile'] == 1) style="display: block" @else style="display: none" @endif >
                                                        <label class="font-weight-bolder">Mobile Number
                                                        </label>
                                                        <input type="text" class="form-control" >
                                                    </div>
                    
                                                    <div class="form-group showgender" @if ($value2[0]['gender']) ) style="display: block" @else style="display: none" @endif >
                                                        <label class="font-weight-bolder">Gender
                                                        </label>
														<select readonly class="form-control">
															<option value="">Please select</option>
															<option value="Male">Male</option>
															<option value="Female">Female</option>
															<option value="Other">Other</option>
															<option value="I dont want to share">I dont want to share</option>
														</select>
                                                    </div>
                    
                                                    <div class="form-group showaddress" @if ($value2[0]['address'] == 1) style="display: block" @else style="display: none" @endif >
                                                        <label class="font-weight-bolder">Address
                                                        </label>
                                                        <textarea rows="4" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (isset($value2[0]['title']))
                                            @php $max2 = count($value2)==1 ? 0 : count($value2)-1 ; @endphp
                                            @for ($i = 0; $i <= $max2; $i++)
                                                
                                                <div cclass="forms-tab1" >
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            @if ($i == 0)
                                                                @if ($value2[$i]['answer_type'] == 'des')
                                                                            
                                                                @else
                                                                    <label class="font-weight-bolder">{{ $value2[$i]['question'] }}</label>
                                                                @endif
                                                                <input type="hidden" class="form-control" name="required[]" value="{{ $value2[$i]['required'] }}">
                                                                <input type="hidden" class="form-control" name="answer_type[]" value="{{ $value2[$i]['answer_type'] }}">
                                                                @if($value2[$i]['answer_type'] == 'des')
                                                                    <input type="hidden" name="question[]" value="null" class="form-control">
                                                                @else
                                                                    <input type="hidden" class="form-control" name="question[]" value="{{ $value2[$i]['question'] }}">
                                                                @endif
                                                
                                                                @if ($value2[$i]['answer_type'] == 'shortAnswer')
                                                                    <input type="text" class="form-control" name="ans1" value="input">
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'longAnswer')
                                                                    <textarea rows="3" class="form-control" name="ans2"></textarea>
                                                                @endif

                                                                @if ($value2[$i]['answer_type'] == 'singleAnswer')
                                                                    <div class="radio-list">
                                                                        @php 
                                                                            $cont2 = count(json_decode($value2[$i]['3ans']))==1 ? 0 : count(json_decode($value2[$i]['3ans']))-1 ;
                                                                        @endphp
                                                                        @for ($v = 0; $v <= $cont2; $v++)
                                                                            @php
                                                                                $ansdata = json_decode($value2[$i]['3ans'])[$v];
                                                                            @endphp
                                                                            <label class="radio">
                                                                                <input type="radio" value="{{ $ansdata }}" >
                                                                                <input type="hidden" name="ans3[]" value="{{ $ansdata }}" >
                                                                                <span></span>{{ $ansdata }}
                                                                            </label>
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'singleCheckbox')
                                                                    <input type="checkbox" class="form-control" name="ans4">
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'multipleCheckbox')
                                                                    <div class="checkbox-list">
                                                                        @php
                                                                            $cont2 = count(json_decode($value2[$i]['5ans']))==1 ? 0 : count(json_decode($value2[$i]['5ans']))-1 ;
                                                                        @endphp
                                                                        @for ($v = 0; $v <= $cont2; $v++)
                                                                            @php $ansdata = json_decode($value2[$i]['5ans'])[$v]; @endphp
                                                                            <label class="checkbox">
                                                                                <input type="checkbox" value="{{ $ansdata }}" >
                                                                                <input type="hidden" name="ans5[]" value="{{ $ansdata }}" >
                                                                                <span></span>{{ $ansdata }}
                                                                            </label>
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'dropdown')
                                                                    <div class="dropdown">
                                                                        <select class="custom-select" name="6ans" >
                                                                            <option disable>Choose...</option>
                                                                            @php 
                                                                                $cont2 = count(json_decode($value2[$i]['6ans']))==1 ? 0 : count(json_decode($value2[$i]['6ans']))-1 ;
                                                                            @endphp
                                                                            @for ($v = 0; $v <= $cont2; $v++)
                                                                                @php $ansdata = json_decode($value2[$i]['6ans'])[$v]; @endphp
                                                                                <option selected value="{{  $ansdata  }}">{{  $ansdata  }}</option>
                                                                            @endfor
                                                                        </select>

                                                                        @for ($v = 0; $v <= $cont2; $v++)
                                                                            @php $ansdata = json_decode($value2[$i]['6ans'])[$v]; @endphp
                                                                            <input type="hidden" name="ans6[]" value="{{  $ansdata  }}" >
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'yesOrNo')
                                                                    <div class="form-group ml-2 w-100 d-flex extra-time">
                                                                        <label class="option m-3">
                                                                            <span class="option-control">
                                                                                <span class="radio">
                                                                                    <input type="radio" value="Yes">
                                                                                    <input type="hidden"  name="ans7[]" value="Yes">
                                                                                    <span></span>
                                                                                </span>
                                                                            </span>
                                                                            <span class="option-label">
                                                                                <span class="option-head">
                                                                                    <span class="option-title">Yes</span>
                                                                                </span>
                                                                            </span>
                                                                        </label>
                                                                        <label class="option m-3">
                                                                            <span class="option-control">
                                                                                <span class="radio">
                                                                                    <input type="radio" value="No">
                                                                                    <input type="hidden" name="ans7[]" value="No">
                                                                                    <span></span>
                                                                                </span>
                                                                            </span>
                                                                            <span class="option-label">
                                                                                <span class="option-head">
                                                                                    <span class="option-title">No</span>
                                                                                </span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'des')
                                                                    <textarea rows="3" class="form-control" name="ans8">{{ $value2[$i]['8ans'] }}</textarea>
                                                                @endif
                                                            @endif

                                                            @if ($i >= 1)
                                                                <hr>
                                                                @if ($value2[$i]['answer_type'] == 'shortAnswer')
                                                                    <input type="text" class="form-control" name="ans1" value="input">
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'longAnswer')
                                                                    <textarea rows="3" class="form-control" name="ans2"></textarea>
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'singleAnswer')
                                                                    <div class="radio-list">
                                                                        @php 
                                                                            $cont2 = count(json_decode($value2[$i]['3ans']))==1 ? 0 : count(json_decode($value2[$i]['3ans']))-1 ;
                                                                        @endphp
                                                                        @for ($v = 0; $v <= $cont2; $v++)
                                                                            @php
                                                                                $ansdata = json_decode($value2[$i]['3ans'])[$v];
                                                                            @endphp
                                                                            <label class="radio">
                                                                                <input type="radio" value="{{ $ansdata }}" >
                                                                                <input type="hidden" name="ans3[]" value="{{ $ansdata }}" >
                                                                                <span></span>{{ $ansdata }}
                                                                            </label>
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'singleCheckbox')
                                                                    <input type="checkbox" class="form-control" name="ans4">
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'multipleCheckbox')
                                                                    <div class="checkbox-list">
                                                                        @php
                                                                            $cont2 = count(json_decode($value2[$i]['5ans']))==1 ? 0 : count(json_decode($value2[$i]['5ans']))-1 ;
                                                                        @endphp
                                                                        @for ($v = 0; $v <= $cont2; $v++)
                                                                            @php $ansdata = json_decode($value2[$i]['5ans'])[$v]; @endphp
                                                    
                                                                            <label class="checkbox">
                                                                            <input type="checkbox" value="{{ $ansdata }}" >
                                                                            <input type="hidden" name="ans5[]" value="{{ $ansdata }}" >
                                                                            <span></span>{{ $ansdata }}</label>
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'dropdown')
                                                                    <div class="dropdown">
                                                                        <select class="custom-select" name="6ans" >
                                                                            <option disable>Choose...</option>
                                                                            @php 
                                                                                $cont2 = count(json_decode($value2[$i]['6ans']))==1 ? 0 : count(json_decode($value2[$i]['6ans']))-1 ;
                                                                            @endphp
                                                                            @for ($v = 0; $v <= $cont2; $v++)
                                                                                    @php $ansdata = json_decode($value2[$i]['6ans'])[$v]; @endphp
                                                                                <option selected value="{{  $ansdata  }}">{{  $ansdata  }}</option>
                                                                            @endfor
                                                                        </select>

                                                                        @for ($v = 1; $v <= $cont2; $v++)
                                                                            @php $ansdata = json_decode($value2[$i]['6ans'])[$v]; @endphp
                                                                            <input type="hidden" name="ans6[]" value="{{  $ansdata  }}" >
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'yesOrNo')
                                                                    <div class="form-group ml-2 w-100 d-flex extra-time">
                                                                        <label class="option m-3">
                                                                            <span class="option-control">
                                                                                <span class="radio">
                                                                                    <input type="radio" value="Yes">
                                                                                    <input type="hidden"  name="ans7[]" value="Yes">
                                                                                    <span></span>
                                                                                </span>
                                                                            </span>
                                                                            <span class="option-label">
                                                                                <span class="option-head">
                                                                                    <span class="option-title">Yes</span>
                                                                                </span>
                                                                            </span>
                                                                        </label>
                                                                        <label class="option m-3">
                                                                            <span class="option-control">
                                                                                <span class="radio">
                                                                                    <input type="radio" value="No">
                                                                                    <input type="hidden" name="ans7[]" value="No">
                                                                                    <span></span>
                                                                                </span>
                                                                            </span>
                                                                            <span class="option-label">
                                                                                <span class="option-head">
                                                                                    <span class="option-title">No</span>
                                                                                </span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                @endif
                                                                @if ($value2[$i]['answer_type'] == 'des')
                                                                    <textarea rows="3" class="form-control" name="ans8">{{ $value2[$i]['8ans'] }}</textarea>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                    </div>
                                    <div class="card-footer text-right">
                                        <button class="forms-next btn btn-primary" onclick="nextPrevForms(1)">Next
                                            Step</button>
                                        <button class="forms-previous btn btn-white" onclick="nextPrevForms(-1)">Previous
                                            Step</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    {{-- add new section modal --}}
    <div class="modal fade" id="addNewSectionModal" tabindex="-1" role="dialog" aria-labelledby="addNewSectionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewSectionModalLabel">Add a section</h5>
                    <p class="cursor-pointer m-0 px-6" data-dismiss="modal" aria-label="Close"><i
                            class="text-dark fa fa-times icon-lg"></i>
                    </p>
                </div>
                <div class="modal-body">
                    <p>Add a standard section or start from scratch with a custom section.</p>
                    <h6 class="font-weight-bolder text-uppercase">STANDARD SECTION</h6>
                    <div>
                        <ul class="ks-cboxtags ">
                            <li class="showclientmodal add" data-toggle="modal" @if(isset($conform->client->id)) "" @else data-target="#addClientDetailModal" @endif
                                data-dismiss="modal">
                                <input type="radio" name="section" id="client-section" value="client-section">
                                <label class="text-center" for="client-section">
                                    <div class="m-auto" style="width: 50px;height: 50px;">
                                        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16 3.872a2.743 2.743 0 012.737 2.737v2.48h6.741a2.65 2.65 0 012.645 2.483l.005.167v13.74a2.65 2.65 0 01-2.482 2.644l-.168.005H6.522a2.65 2.65 0 01-2.645-2.482l-.005-.168V11.74a2.65 2.65 0 012.482-2.645l.168-.005h6.74l.001-2.48a2.747 2.747 0 012.393-2.714l.172-.018zm-2.737 6.517H6.522a1.35 1.35 0 00-1.344 1.22l-.006.13v13.74a1.35 1.35 0 001.22 1.343l.13.006h18.956a1.35 1.35 0 001.344-1.22l.006-.13V11.74a1.35 1.35 0 00-1.22-1.344l-.13-.006h-6.741v2.087h-5.474V10.39zm.694 4.961c.91 0 1.65.739 1.65 1.65v4.26a1.65 1.65 0 01-1.65 1.65H9.696a1.65 1.65 0 01-1.65-1.65V17c0-.911.738-1.65 1.65-1.65zm9.347 5.217a.65.65 0 01.096 1.293l-.096.007H19.13a.65.65 0 01-.096-1.293l.096-.007h4.174zm-9.347-3.917H9.696a.35.35 0 00-.35.35v4.26c0 .194.156.35.35.35h4.26a.35.35 0 00.35-.35V17a.35.35 0 00-.35-.35zm9.347-.257a.65.65 0 01.096 1.293l-.096.007H19.13a.65.65 0 01-.096-1.292l.096-.008h4.174zM16.01 5.171l-.111.004a1.444 1.444 0 00-1.335 1.434v4.567h2.873l.001-4.567c0-.743-.574-1.36-1.3-1.43l-.128-.008z">
                                            </path>
                                        </svg>
                                    </div>
                                    clients details
                                </label>
                            </li>
                            <!--li><input type="radio" name="section" disabled id="medical" value="medical">
                                <label class="text-center" for="medical">
                                    <span class="badge badge-info px-2 py-1" style="position: absolute;top:0;right:0">
                                        soon
                                    </span>
                                    <div class="m-auto" style="width: 50px;height: 50px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                            <path
                                                d="M51.1 62.5H36.6c-1.1 0-2.3-.9-2.5-2-.5-2.5-.2-5.5.8-8.9 0-.1 0-.1.1-.2.8-2.7 2.1-5.6 3.8-8.9l-.2-2.2c-.3-3.6-.7-7.1-1-10.4-.1-1.4.7-2.7 1.9-3.3 2.7-1.2 5.8-1.2 8.6 0 1.3.6 2.1 1.9 1.9 3.3-.3 3.2-.6 6.5-.9 9.9l-.3 2.8c1.7 3.2 2.9 6 3.8 8.6.1.2.2.3.2.6v.1c1 3.3 1.2 6.1.8 8.6-.2 1-1.4 2-2.5 2zm-14.6-9.8c-.8 2.9-1 5.3-.6 7.5 0 .2.5.5.8.5h14.5c.3 0 .7-.3.8-.5.4-2.1.2-4.6-.6-7.4H36.5zm.6-1.8h13.6c-.8-2.3-2-4.9-3.5-7.7-.1-.2-.1-.4-.1-.6l.3-2.9c.3-3.4.6-6.7.9-9.9.1-.6-.3-1.2-.9-1.5-2.3-1-4.8-1-7.1 0-.6.3-.9.9-.9 1.5.3 3.3.6 6.8 1 10.4l.2 1.7h3.3c.5 0 .9.4.9.9s-.4.9-.9.9h-3.6c-1.4 2.6-2.5 5-3.2 7.2zm-8.6 11.6c-.5 0-.9-.4-.9-.9V37c0-1.8-.6-3.5-1.7-4.9-.6-.8-1.4-1.4-2.2-1.9-.4-.2-.6-.8-.3-1.2.2-.4.8-.6 1.2-.3 1 .6 1.9 1.4 2.7 2.3 1.4 1.7 2.1 3.9 2.1 6.1v24.6c0 .4-.4.8-.9.8zm-17.6 0c-.5 0-.9-.4-.9-.9V37c0-2.2.8-4.4 2.1-6.1.6-.8 1.4-1.5 2.2-2v-2.4c0-1.6.7-3.2 2.1-4.2.4-.3 1-.2 1.3.2.3.4.2 1-.2 1.3-.9.7-1.4 1.7-1.4 2.8V38c0 .4.4.8.8.8h5.4c.4 0 .8-.4.8-.8v-5.8c0-.5.4-.9.9-.9s.9.4.9.9V38c0 1.4-1.2 2.6-2.6 2.6H17c-1.4 0-2.6-1.2-2.6-2.6v-6.8c-.3.3-.6.6-.8.9-1.1 1.4-1.7 3.1-1.7 4.9v24.6c-.1.5-.5.9-1 .9zm11.3-33.1c-.2 0-.5-.1-.6-.3-.4-.4-.4-.9 0-1.3l2-2c.7-.7.7-1.7 0-2.4-.3-.3-.7-.5-1.2-.5-.4 0-.9.2-1.2.5l-2 2c-.4.4-.9.4-1.3 0s-.4-.9 0-1.3l2-2c.7-.7 1.5-1 2.5-1 .6 0 1.2.2 1.7.5l6.3-6.3-2.6-2.6c-.4-.4-.4-.9 0-1.3l6.8-6.8c1-1 2.4-1.6 3.9-1.6s2.8.6 3.8 1.6c2.1 2.1 2.1 5.5 0 7.7l-6.8 6.8c-.2.2-.4.3-.6.3-.2 0-.5-.1-.6-.3l-2.6-2.6-6.3 6.5c.3.5.5 1.1.5 1.7 0 .9-.4 1.8-1 2.5l-2 2c-.2.1-.5.2-.7.2zm7.5-17.2l5.2 5.2 6.1-6.1c1.4-1.4 1.4-3.7 0-5.1-.7-.8-1.6-1.2-2.6-1.2s-1.9.4-2.6 1.1l-6.1 6.1zm-9.3 15.4c-.2 0-.5-.1-.6-.3-.4-.4-.4-.9 0-1.3l1.9-1.9c.4-.4.9-.4 1.3 0s.4.9 0 1.3l-2 1.9c-.1.2-.4.3-.6.3z">
                                            </path>
                                        </svg>
                                    </div>
                                    Medical history
                                </label>
                            </li>
                            <li><input type="radio" name="section" disabled id="marketing" value="marketing" checked="">
                                <label class="text-center" for="marketing">
                                    <span class="badge badge-info px-2 py-1" style="position: absolute;top:0;right:0">
                                        soon
                                    </span>
                                    <div class="m-auto" style="width: 50px;height: 50px;">
                                        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M13.495 19.673l.055.09 2.459 4.937c.59 1.194-.112 2.527-1.472 2.97-1.24.403-2.651-.047-3.26-1.064l-.077-.142-3.074-6.119a.65.65 0 011.107-.674l.055.09 3.074 6.12c.253.509 1.058.786 1.773.554.608-.199.884-.66.745-1.07l-.036-.087-2.458-4.935a.65.65 0 011.109-.67zM23.279 4v18.19l-8.483-4.135h-6.17c-2.485 0-4.514-2.102-4.621-4.74L4 13.096l.005-.221c.104-2.562 2.021-4.62 4.41-4.735l.212-.005h6.17L23.278 4zm-1.301 2.079l-6.882 3.355h-6.47c-1.7 0-3.14 1.422-3.309 3.267l-.013.2-.004.194c0 1.973 1.411 3.552 3.146 3.655l.18.005h6.47l6.882 3.354V6.079zm3.229 4.299c1.434 0 2.577 1.226 2.577 2.717 0 1.49-1.143 2.717-2.577 2.717a.65.65 0 01-.096-1.293l.096-.007c.695 0 1.277-.625 1.277-1.417 0-.746-.516-1.343-1.156-1.41l-.121-.007a.65.65 0 110-1.3z">
                                            </path>
                                        </svg>
                                    </div>
                                    Marketing preferences
                                </label>
                            </li-->
                            <hr>
                            <li data-toggle="modal" data-target="#addCustomSectionModal" data-dismiss="modal">
                                <input type="radio" name="section" id="custom-section" value="custom-section" checked="">
                                <label class="text-center" for="custom-section">
                                    <div class="m-auto" style="width: 50px;height: 50px;">
                                        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16 3l4.326 8.56L30 12.93l-7 6.662L24.652 29 16 24.558 7.348 29 9 19.593l-7-6.662 9.674-1.372L16 3zm3.472 9.751L16 5.882l-3.472 6.869-7.686 1.09 5.559 5.291L9.08 26.65 16 23.096l6.919 3.553-1.32-7.517 5.558-5.291-7.685-1.09z">
                                            </path>
                                        </svg>
                                    </div>
                                    Custom Section
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Add Section</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end --}}


    {{-- add new question modal --}}
    <div class="modal fade" id="addNewQuestionModal" tabindex="-1" role="dialog" aria-labelledby="addNewQuestionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewQuestionModalLabel">Select an answer type or item</h5>
                    <p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
                            class="text-dark fa fa-times icon-lg"></i>
                    </p>
                </div>
                <form id="newqna" name="newqna">
                    <div class="modal-body">
                        <h6 class="font-weight-bolder text-uppercase">ANSWER TYPES</h6>
                        <div>
                            <ul class="ks-cboxtags question">
                                <li><input type="radio" name="question" id="short-ans" value="short-ans">
                                    <label class="text-center" for="short-ans">
                                        <div class="m-auto" style="width: 30px;height: 30px;">
                                            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17 18.85a.65.65 0 01.096 1.293L17 20.15H6a.65.65 0 01-.096-1.293L6 18.85h11zm9-6a.65.65 0 01.096 1.293L26 14.15H6a.65.65 0 01-.096-1.293L6 12.85h20z"
                                                    fill="#101928" fill-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        Short Answer
                                    </label>
                                </li>
                                <li><input type="radio" name="question" id="long-ans" value="long-ans">
                                    <label class="text-center" for="long-ans">
                                        <div class="m-auto" style="width: 30px;height: 30px;">
                                            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M15 24.85a.65.65 0 01.096 1.293L15 26.15H6a.65.65 0 01-.096-1.293L6 24.85h9zm11-6a.65.65 0 01.096 1.293L26 20.15H6a.65.65 0 01-.096-1.293L6 18.85h20zm-11-6a.65.65 0 01.096 1.293L15 14.15H6a.65.65 0 01-.096-1.293L6 12.85h9zm11-6a.65.65 0 01.096 1.293L26 8.15H6a.65.65 0 01-.096-1.293L6 6.85h20z"
                                                    fill="#101928" fill-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        Long Answer
                                    </label>
                                </li>
                                <li><input type="radio" name="question" id="single-ans" value="single-ans">
                                    <label class="text-center" for="single-ans">
                                        <div class="m-auto" style="width: 30px;height: 30px;">
                                            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M16 4.35c6.434 0 11.65 5.216 11.65 11.65S22.434 27.65 16 27.65 4.35 22.434 4.35 16 9.566 4.35 16 4.35zm0 1.3C10.284 5.65 5.65 10.284 5.65 16c0 5.716 4.634 10.35 10.35 10.35 5.716 0 10.35-4.634 10.35-10.35 0-5.716-4.634-10.35-10.35-10.35zm0 5.7a4.65 4.65 0 110 9.3 4.65 4.65 0 010-9.3zm0 1.3a3.35 3.35 0 100 6.7 3.35 3.35 0 000-6.7z"
                                                    fill="#101928" fill-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        Single Answer
                                    </label>
                                </li>
                                <li><input type="radio" name="question" id="single-checkbox" value="single-checkbox">
                                    <label class="text-center" for="single-checkbox">
                                        <div class="m-auto" style="width: 30px;height: 30px;">
                                            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M24 4.35A3.65 3.65 0 0127.65 8v16A3.65 3.65 0 0124 27.65H8A3.65 3.65 0 014.35 24V8A3.65 3.65 0 018 4.35zm0 1.3H8A2.35 2.35 0 005.65 8v16A2.35 2.35 0 008 26.35h16A2.35 2.35 0 0026.35 24V8A2.35 2.35 0 0024 5.65zm-2.565 6.867a.65.65 0 01.112.834l-.064.084-6.726 7.473-3.182-2.75a.65.65 0 01.765-1.046l.085.063 2.218 1.916 5.874-6.526a.65.65 0 01.918-.048z"
                                                    fill="#101928" fill-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        Single checkbox
                                    </label>
                                </li>
                                <li><input type="radio" name="question" id="multi-choice" value="multi-choice">
                                    <label class="text-center" for="multi-choice">
                                        <div class="m-auto" style="width: 30px;height: 30px;">
                                            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6.5 22.35a3.15 3.15 0 110 6.3 3.15 3.15 0 010-6.3zm0 1.3a1.85 1.85 0 100 3.7 1.85 1.85 0 000-3.7zm18.148 1.7a.65.65 0 01.096 1.293l-.096.007H14.054a.65.65 0 01-.096-1.293l.096-.007h10.594zM9.428 13.01a.65.65 0 01.124.833l-.063.085-3.931 4.493-1.99-1.769a.65.65 0 01.78-1.035l.084.064 1.009.897 3.07-3.506a.65.65 0 01.917-.061zm11.367 2.34a.65.65 0 01.096 1.293l-.096.007h-6.741a.65.65 0 01-.096-1.293l.096-.007h6.741zM9.428 3.51a.65.65 0 01.124.833l-.063.085-3.931 4.493-1.99-1.769a.65.65 0 01.78-1.035l.084.064 1.009.897 3.07-3.506a.65.65 0 01.917-.061zM28.5 5.85a.65.65 0 01.096 1.293l-.096.007H14.054a.65.65 0 01-.096-1.293l.096-.007H28.5z"
                                                    fill="#101928" fill-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        Multiple Choise
                                    </label>
                                </li>
                                <li><input type="radio" name="question" id="drop-down" value="drop-down">
                                    <label class="text-center" for="drop-down">
                                        <div class="m-auto" style="width: 30px;height: 30px;">
                                            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M29 6a3 3 0 013 3v14a3 3 0 01-3 3H3a3 3 0 01-3-3V9a3 3 0 013-3h26zm0 1.3H3a1.7 1.7 0 00-1.694 1.553L1.3 9v14a1.7 1.7 0 001.553 1.694L3 24.7h26a1.7 1.7 0 001.694-1.553L30.7 23V9a1.7 1.7 0 00-1.553-1.694L29 7.3zm-1.492 7.294a.65.65 0 01-.025.841l-.077.073L22 19.832l-5.406-4.324a.65.65 0 01.724-1.075l.088.06L22 18.166l4.594-3.675a.65.65 0 01.914.102z"
                                                    fill="#101928" fill-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        Drop Down
                                    </label>
                                </li>
                                <li><input type="radio" name="question" id="yesorno" value="yesorno">
                                    <label class="text-center" for="yesorno">
                                        <div class="m-auto" style="width: 30px;height: 30px;">
                                            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M16 4.35c6.434 0 11.65 5.216 11.65 11.65S22.434 27.65 16 27.65 4.35 22.434 4.35 16 9.566 4.35 16 4.35zm0 1.3C10.284 5.65 5.65 10.284 5.65 16c0 5.716 4.634 10.35 10.35 10.35z"
                                                    fill="#101928" fill-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        Yes or No
                                    </label>
                                </li>
                                <hr>
                                <h6 class="font-weight-bolder">ITEMS</h6>
                                <li><input type="radio" name="question" id="description-text" value="description-text">
                                    <label class="text-center" for="description-text">
                                        <div class="m-auto" style="width: 30px;height: 30px;">
                                            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6.492 22.845a.68.68 0 00.646-.444l1.333-3.343h6.395l1.333 3.343a.774.774 0 00.239.319.655.655 0 00.417.125h1.492L12.648 9h-1.95L5 22.845h1.492zm7.837-5.14H9.01l2.237-5.623c.146-.348.288-.782.428-1.304a12.556 12.556 0 00.417 1.294l2.238 5.633zM21.888 23c.384 0 .734-.034 1.05-.101a4.41 4.41 0 001.685-.754 9.81 9.81 0 00.776-.633l.199.928c.053.16.126.269.218.323.093.055.226.082.398.082H27v-6.26a4.74 4.74 0 00-.229-1.508 3.169 3.169 0 00-.676-1.169 3.081 3.081 0 00-1.104-.753c-.438-.18-.938-.27-1.502-.27-.782 0-1.495.128-2.138.386a5.768 5.768 0 00-1.8 1.169l.318.55c.06.09.133.166.219.227a.519.519 0 00.308.092.819.819 0 00.462-.16c.156-.106.342-.225.557-.357.216-.132.471-.25.766-.357.295-.106.662-.16 1.1-.16.65 0 1.143.195 1.481.585.338.39.507.964.507 1.725v.763c-1.147.026-2.115.127-2.904.304-.789.177-1.429.404-1.92.681-.49.277-.845.595-1.063.952-.22.358-.329.73-.329 1.116 0 .444.075.83.224 1.155.15.325.352.594.607.806.255.213.555.372.9.479.345.106.713.159 1.104.159zm.527-1.208c-.232 0-.45-.027-.651-.082a1.433 1.433 0 01-.528-.26 1.243 1.243 0 01-.353-.46 1.58 1.58 0 01-.129-.666c0-.27.081-.516.244-.735.162-.219.424-.409.785-.57.362-.16.83-.291 1.403-.391.573-.1 1.268-.163 2.083-.188v2.029c-.198.206-.402.39-.611.55-.21.161-.428.3-.657.416a3.282 3.282 0 01-.73.265 3.716 3.716 0 01-.856.092z"
                                                    fill="#101928" fill-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        Description Text
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary " id="addnewqnatomodel">Add</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end --}}

    {{-- add custom modal --}}
    <div class="modal fade" id="addCustomSectionModal" tabindex="-1" role="dialog"
        aria-labelledby="addCustomSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form name="addcustomsec" id="addcustomsec">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCustomSectionModalLabel">Add a custom section</h5>
                        <p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
                                class="text-dark fa fa-times icon-lg"></i>
                        </p>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bolder">Section title</label>
                            <input type="text" name="sectiontitle" id="sectiontitle" placeholder="add title"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bolder">Section description <span
                                    class="text-muted">(Optional)</span></label>
                            <input type="text" name="sectiondes" id="sectiondes" placeholder="add description"
                                class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="addCusSection">Add Section</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- end --}}

    {{-- add client detail model --}}
    <div class="modal fade" id="addClientDetailModal" tabindex="-1" role="dialog"
        aria-labelledby="addClientDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form name="saveClientinfo" id="saveClientinfo" action="{{ route('conFormClientInfo') }}">
                @csrf
                <input type="hidden" name="secton_id" id="secton_id" @if(isset($conform->client->section_id)) value="{{ $conform->client->section_id }}" @endif>
                <input type="hidden" name="client_id" id="client_id" @if(isset($conform->client->id)) value="{{ $conform->client->id }}" @endif>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addClientDetailModalLabel"><span id="showtext"></span> a client details
                            section</h5>
                        <p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
                                class="text-dark fa fa-times icon-lg"></i>
                        </p>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bolder">Section title</label>
                            <input type="text" name="section_title" @if(isset($conform->client->section_title)) value="{{ $conform->client->section_title }}" @endif id="section_title" placeholder="add title"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bolder">Section description <span
                                    class="text-muted">(Optional)</span></label>
                            <input type="text" name="section_des" id="section_des" @if(isset($conform->client->section_des)) value="{{ $conform->client->section_des }}" @endif placeholder="add description"
                                class=" form-control">
                        </div>
                        <hr>
                        <h3 class="font-weight-bolder">Client details fields</h3>
                        <p>Choose the information you’d like your clients to provide.</p>
                        <div class="">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <div class="checkbox-list">
                                            <label class="checkbox font-weight-bolder">
                                                <input name="first_name" id="first_name" type="checkbox" @if(isset($conform->client->first_name) && $conform->client->first_name==1) checked @endif>
                                                <span></span> First Name
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <div class="checkbox-list">
                                            <label class="checkbox font-weight-bolder">
                                                <input name="last_name" id="last_name" type="checkbox" @if(isset($conform->client->last_name) && $conform->client->last_name==1) checked @endif >
                                                <span></span> Last Name
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <div class="checkbox-list">
                                            <label class="checkbox font-weight-bolder">
                                                <input name="email" id="email" type="checkbox" @if(isset($conform->client->email) && $conform->client->email==1) checked @endif >
                                                <span></span> Email
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <div class="checkbox-list">
                                            <label class="checkbox font-weight-bolder">
                                                <input name="birthday" id="birthday" type="checkbox" @if(isset($conform->client->birthday) && $conform->client->birthday==1) checked @endif >
                                                <span></span> Birthday
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <div class="checkbox-list">
                                            <label class="checkbox font-weight-bolder">
												<input name="country_code" id="country_code" type="hidden" value="{{ ($conform->client->country_code) ? $conform->client->country_code : 1 }}">
											
                                                <input name="mobile" id="mobile" type="checkbox" @if(isset($conform->client->mobile) && $conform->client->mobile==1) checked @endif >
                                                <span></span> Mobile Number
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <div class="checkbox-list">
                                            <label class="checkbox font-weight-bolder">
                                                <input name="gender" id="gender" type="checkbox" @if(isset($conform->client->gender) && $conform->client->gender==1) checked @endif >
                                                <span></span> Gender
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <div class="checkbox-list">
                                            <label class="checkbox font-weight-bolder">
                                                <input name="address" id="address" type="checkbox" @if(isset($conform->client->address) && $conform->client->address==1) checked @endif >
                                                <span></span> Address
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span id="spnError" style="display: none;color:red">This field is required</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveClient">Add Section</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- end --}}
		
	<select id="country_code_list" style="display:none">
		@if($Country)
			@foreach($Country as $CountryData)
				<option value="{{ $CountryData['phonecode'] }}" @if($CountryData['phonecode'] == 1 && $CountryData['name'] == 'Canada') selected @endif>{{ $CountryData['name'] }} +{{ $CountryData['phonecode'] }}</option>
			@endforeach
		@endif	
	</select>	
		
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop">
        <span class="svg-icon">
            <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24" />
                    <rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
                    <path
                        d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
                        fill="#000000" fill-rule="nonzero" />
                </g>
            </svg>
            <!--end::Svg Icon-->
        </span>
    </div>
    <!--end::Scrolltop-->
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script>
    var maxsection = '<?php echo $maxsec; ?>';
    var ischeck = 0;
    var checked = 0;
    var sectionid = parseInt(maxsection) + parseInt(1);
    var setp1 = sectionid;
    var setp2 = sectionid;
    var temp = 1;
    {{-- var signans = 3; --}}
    {{-- $(".next-step").attr('disabled', true); --}}
    console.log(sectionid);
    

</script>
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>

<!--end::Page Scripts-->
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/js/hummingbird-treeview-1.3.js') }}"></script>
<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
<script>
    var list = [];
    $("#treeview").hummingbird();
    $("#treeview").on("CheckUncheckDone", function() {
        var count = $('input[name="value_checkbox[]"]:checked').length;
        var allCount = $('input[type="checkbox"]:checked').length;
        var allCheck = $('input[type="checkbox"]').length;

        if (allCheck == allCount) {
            $("#serviceInput").val('All Service Selected')
        } else {
            $("#serviceInput").val(count + ' service Selected')
        }
    });

</script>
<script>
    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
        // This function will display the specified tab of the form...
        var tab = document.getElementsByClassName("add-voucher-tab");
        tab[n].style.display = "block";
        if (n == (tab.length - 1)) {
            $(".next-step").text("Save");
            $(".next-step").hide();
            $('#fullsave').show();
            $(".previous").show();
            $(".steps").text("2");
        } else {
            $(".previous").hide();
            $(".next-step").show();
            $('#fullsave').hide();
            $(".next-step").text("Next Step");
        }
        if (n == 0) {
            $(".steps").text("1");
            $(".main-title").text("Add sections to your consultation form")
        } else if (n == 1) {
            $(".previous").show();
            $(".steps").text("2");
            $(".main-title").text("Add your consultation form details")
        }
    }

    function nextPrev(n) {
        // This function will figure out which tab to display
        var tab = document.getElementsByClassName("add-voucher-tab");
        // Hide the current tab:
        tab[currentTab].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentTab = currentTab + n;
        // if you have reached the end of the tab
        // Otherwise, display the correct tab:
        showTab(currentTab);
    }

</script>
<script>
    var currentFormsTab = 0; // Current tab is set to be the first tab (0)
    showFormsTab(currentFormsTab); // Display the current tab

    function showFormsTab(n) {
        // This function will display the specified tab of the form...
        var formsTab = document.getElementsByClassName("forms-tab");
        $(".total-forms-tab").text(formsTab.length);
        $(".forms-current-steps").text(n + 1);
        formsTab[n].style.display = "block";
        if (n == (formsTab.length - 1)) {
            $(".forms-previous").show();
            $(".forms-next").hide();
        } else {
            $(".forms-previous").show();
            $(".forms-next").show();
        }
        if (n == 0) {
            $(".forms-previous").hide();
        }
    }

    function nextPrevForms(n) {
        // This function will figure out which tab to display
        var formsTab = document.getElementsByClassName("forms-tab");
        // Hide the current tab:
        formsTab[currentFormsTab].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentFormsTab = currentFormsTab + n;
        // if you have reached the end of the tab
        // Otherwise, display the correct tab:
        showFormsTab(currentFormsTab);
    }

</script>
<script>
    var currentPreviewTab = 0; // Current tab is set to be the first tab (0)
    showPreviewTab(currentPreviewTab); // Display the current tab

    function showPreviewTab(n) {
        // This function will display the specified tab of the form...
        var previewTab = document.getElementsByClassName("preview-tab");
        $(".total-preview-tab").text(previewTab.length);
        $(".preview-current-steps").text(n + 1);
        previewTab[n].style.display = "block";
        if (n == (previewTab.length - 1)) {
            $(".preview-previous").show();
            $(".preview-next").hide();
        } else {
            $(".preview-previous").show();
            $(".preview-next").show();
        }
        if (n == 0) {
            $(".preview-previous").hide();
        }
    }

    function nextPrevPreview(n) {
        // This function will figure out which tab to display
        var previewTab = document.getElementsByClassName("preview-tab");
        // Hide the current tab:
        previewTab[currentPreviewTab].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentPreviewTab = currentPreviewTab + n;

        // if you have reached the end of the tab
        // Otherwise, display the correct tab:
        showPreviewTab(currentPreviewTab);
    }

	function makeid(length) {
	   var result           = '';
	   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	   var charactersLength = characters.length;
	   for ( var i = 0; i < length; i++ ) {
		  result += characters.charAt(Math.floor(Math.random() * charactersLength));
	   }
	   return result;
	}

    jQuery(document).ready(function() {

        $(document).on('click','#showpr', function(){
            var previewTab = document.getElementsByClassName("preview-tab");
            
            $(".total-preview-tab").text(previewTab.length);
            $(".total-forms-tab").text(previewTab.length);

            if(previewTab.length <= 1){
                
                $('.preview-next').attr('disabled', true);
                $('.forms-next').attr('disabled', true);
            } else {
                $('.preview-next').attr('disabled', false);
                $('.forms-next').attr('disabled', false);
            }
        });

        $(document).on('click', '#fullsave', function() {
            var formname = $('#name').val()
            if (formname == '') {
                $('#nameerror').text('This field is required');
            } else {
                $('#nameerror').text('');
                var filterdata = [];
                $('form[id="qnaform"]').each(function() {
                    var form = $(this);
                    var add = form.serialize();
                    filterdata.push(add);
                })
                console.log(filterdata)

                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    url: '{{ route('updateconform') }}',
                    data: {
                        formdata: $("#formdetail").serialize(),
                        client: $("#saveClientinfo").serialize(),
                        filterdata
                    },

                    success: function(response) {
                        console.log(response);
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "4000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        };
                        toastr.success(response.message);
						window.location = response.url;
                    },
                    error: function(data) {
                        console.log(data);

                        {{-- KTUtil.btnRelease(formSubmitButton, "Save"); --}}
                        var errors = data.responseJSON;

                        var errorsHtml = '';
                        $.each(errors.errors, function(key, value) {
                            errorsHtml += value[0];
                        });

                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "4000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        };
                        toastr.error((errorsHtml) ? errorsHtml : "Something went wrong!");
                    }
                });
            }

        });

        $(document).on('click', '#deleteClinemo', function() {
            setp2--;
            temp--;
            $('#builderclient').remove();
            $('.apppreview > .cliremove').remove();
            $('.appendpre > .cliremove').remove();

            if(sectionid==0){$(".next-step").attr('disabled',true);}
            $('.showclientmodal').attr('data-target', '#addClientDetailModal');
            document.getElementById('saveClientinfo').reset();
            $("#spnError").hide();


        });

        $(document).on('click', '#deleteCustom', function() {
            setp2--;
            temp--;
            $('#custom'+$(this).data('id')).remove();
            var secid = $(this).closest('form').find('#sectionid').val();

            $('.apppreview > .remove'+secid).remove();
            $('.appendpre > .remove'+secid).remove();
            
            if(sectionid==0){$(".next-step").attr('disabled',true);}
            $(".setp1").val();

        });

        $(document).on('click','.closeqna', function(){
            var qna = $(this).closest('.card').find('#question').val();
            var req = $(this).closest('.card').find('#required').val();
            var ans	= $(this).closest('.card').find(':selected').val();
            var data = $(this).closest('.showdata').find('.showedit');
            console.log(req);
            if(data.length==0){
                var html = '';
                html+='<div class="form-group showedit">';
                    if(ans=='des'){
                        
                    } else {
                        html+='<label class="font-weight-bolder">'+qna+'</label>';
                    }
                    html+='<input type="hidden" class="form-control" name="required[]" value="'+req+'">';
                    html+='<input type="hidden" class="form-control" name="answer_type[]" value="'+ans+'">';
                    if(ans=='des'){
                        html+='<input type="hidden" name="question[]" value="null" class="form-control">';
                    } else {
                        html+='<input type="hidden" class="form-control" name="question[]" value="'+qna+'">';
                    }
                html+='</div>';
                $(this).closest('.showdata').append(html);
            } else {
                var data = $(this).closest('.showdata').find('.showedit').show();
            }
                
            $(this).closest('.card').hide();
        });

        $(document).on('click','.copyNewQnaCus', function(){
            var clonedata = $(this).closest('.showdata').clone();
            $(this).parents('form').find('#addnewqnainsec').append(clonedata);
			
			var unique_id = makeid(10);
			$(this).parents('form').find("#addnewqnainsec .showdata:last").attr('id','divIndex'+unique_id);
        });

        $(document).on('click','.deleteNewQnaCus', function(){
            var qna = $(this).parent('.sectiondata').find('.showdata').length;
            var qna1 = $(this).closest('.showdata').find('#question').val();
            var divid13 = qna1.replace(/ /g,"_");
            console.log(divid13)
            console.log(qna)
            console.log($(  ).parents('.sectiondata'));
            //if(qna<=1){
                {{-- $(this).attr('disabled',true); --}}
            //} else {
                setp2--;
                temp--;
                $(this).closest('.showdata').remove();
                $('.apppreview > .'+divid13).remove();
                $('.appendpre > .next'+divid13).remove();
                if(sectionid==0){$(".next-step").attr('disabled',true);}
                $(".setp1").val();
            //}

        });

        $(document).on('click', '.add', function() {
            $("#showtext").text('Add');
            $("#secton_id").val(sectionid);

        });
        $(document).on('click', '.edit', function() {
            $("#showtext").text('Edit');
        });

        $(document).on('click', '#required', function() {
            console.log($(this).val())
        });

        $(document).on('click', '.saveques', function() {

            var qna = $(this).closest('.card').find('#question').val();
            var req = $(this).closest('.card').find('#required').val();
            var ans = $(this).closest('.card').find(':selected').val();
            var ansInput = $(this).closest('.card').find('.ans').length;
            console.log(req);

            if (ans == 'des') {
                qna = 'in';
            }
            if (qna == '') {
                $(this).closest('.card').find('#error').text('This field is required');
            } else {
                $(this).closest('.card').find('#error').text('');

                var validated = true;
                $(this).closest('.card').find('input,textarea,select').filter('[required]:visible').each(function(){
                    if($.trim($(this).val()) == '') {
                        validated = false;
                        if($(this).siblings('.custom-error').length > 0) {
                            $(this).siblings('.custom-error').text('This field is required');
                        } else {
                            $('<span class="navi-text text-danger custom-error">This field is required</span>').insertAfter(this);
                        }
                    } else {
                        $(this).siblings('.custom-error').remove();
                    }
                });

                if(!validated) {
                    return false;
                }

                $(this).closest('.showdata').find('.showedit').remove();

                $(this).closest('.card').hide();
                console.log(ans);
                var html = '';
                html += '<div class="form-group showedit">';
                if (ans == 'des') {

                } else {
                    html += '<label class="font-weight-bolder">' + qna + '</label>';
                }
                html += '<input type="hidden" class="form-control" name="required[]" value="' + req +'">';
                html += '<input type="hidden" class="form-control" name="answer_type[]" value="' + ans +'">';
                if (ans == 'des') {
                    html += '<input type="hidden" name="question[]" value="null" class="form-control">';
                } else {
                    html += '<input type="hidden" class="form-control" name="question[]" value="' + qna + '">';
                }


                if (ans == 'shortAnswer') {
                    html += '<input type="text" class="form-control" name="ans1" value="input">';
                }
                if (ans == 'longAnswer') {
                    html += '<textarea rows="3" class="form-control" name="ans2"></textarea>';
                }
                if (ans == 'singleAnswer') {
                    html += '<div class="radio-list">';
                    for (i = 1; i <= ansInput; i++) {
                        var ansdata = $(this).closest('.card').find('#ans' + i).val();

                        html += '<label class="radio">';
                        html += '<input type="radio" value="' + ansdata + '" >';
                        html += '<input type="hidden" name="ans3[]" value="' + ansdata + '" >';
                        html += '<span></span>' + ansdata + '</label>';
                    }
                    html += '</div>';
                }
                if (ans == 'singleCheckbox') {
                    html += '<input type="checkbox" class="form-control" name="ans4">';
                }
                if (ans == 'multipleCheckbox') {
                    html += '<div class="checkbox-list">';
                    for (i = 1; i <= ansInput; i++) {
                        var ansdata = $(this).closest('.card').find('#ans' + i).val();

                        html += '<label class="checkbox">';
                        html += '<input type="checkbox" value="' + ansdata + '" >';
                        html += '<input type="hidden" name="ans5[]" value="' + ansdata + '" >';
                        html += '<span></span>' + ansdata + '</label>';
                    }
                    html += '</div>';
                }
                if (ans == 'dropdown') {
                    html += '<div class="dropdown">';
                    html += '<select class="custom-select" name="6ans" >';
                    html += '<option disable>Choose...</option>';
                    for (i = 1; i <= ansInput; i++) {
                        var ansdata = $(this).closest('.card').find('#ans' + i).val();
                        html += '<option selected value="' + ansdata + '">' + ansdata + '</option>';

                    }
                    html += '</select>';
                    for (i = 1; i <= ansInput; i++) {
                        var ansdata = $(this).closest('.card').find('#ans' + i).val();

                        html += '<input type="hidden" name="ans6[]" value="' + ansdata + '" >';
                    }
                    html += '</div>';
                }
                if (ans == 'yesOrNo') {
                    html += '<div class="form-group ml-2 w-100 d-flex extra-time">';
                    html += '<label class="option m-3">';
                    html += '<span class="option-control">';
                    html += '<span class="radio">';
                    html += '<input type="radio" value="Yes">';
                    html += '<input type="hidden"  name="ans7[]" value="Yes">';
                    html += '<span></span>';
                    html += '</span>';
                    html += '</span>';
                    html += '<span class="option-label">';
                    html += '<span class="option-head">';
                    html += '<span class="option-title">Yes</span>';
                    html += '</span>';
                    html += '</span>';
                    html += '</label>';
                    html += '<label class="option m-3">';
                    html += '<span class="option-control">';
                    html += '<span class="radio">';
                    html += '<input type="radio" value="No">';
                    html += '<input type="hidden" name="ans7[]" value="No">';
                    html += '<span></span>';
                    html += '</span>';
                    html += '</span>';
                    html += '<span class="option-label">';
                    html += '<span class="option-head">';
                    html += '<span class="option-title">No</span>';
                    html += '</span>';
                    html += '</span>';
                    html += '</label>';
                    html += '</div>';
                }
                if (ans == 'des') {
                    var ansdata = $(this).closest('.card').find('#ans1').val();
                    html += '<textarea rows="3" class="form-control" name="ans8">' + ansdata + '</textarea>';
                }

                html += '</div>';

                $(this).closest('.showdata').append(html);
            }

        });

        $(document).on('click', '.showedit', function() {
            $(this).parent().find('.card').show();
            $(this).hide();
            {{-- $(this).parent().find('.saveques').addClass("editques");
				$(this).parent().find('.saveques').removeClass("saveques"); --}}
        });

        $(document).on('change', '.dropDownId', function() {
            var ans = $(this).val();
            var id = $(this).data('id');
            console.log(ans);
            console.log(id);

            var html = '';
            $(".inputappend" + id).empty();
            if (ans == 'singleAnswer') {
                $(this).closest('.card').find(".ml-2").show();

                html += '<div class="d-flex justify-content-between">';
                html += '<div class="form-group mb-0 mr-2 w-100">';
                html += '<div class="form-group">';
                html += '<label class="font-weight-bolder">	Answer 1 ';
                html += '</label>';
                html += '<input type="text" class="form-control ans" id="ans1" required="required">';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="d-flex justify-content-between ">';
                html += '<div class="form-group mb-0 mr-2 w-100">';
                html += '<div class="form-group">';
                html += '<label class="font-weight-bolder"> Answer 2 ';
                html += '</label>';
                html += '<input type="text" class="form-control ans" id="ans2" required="required">';
                html += '</div>';
                html += '</div>';
                html += '</div><div class="newsignappans' + id + '"></div>';
                html += '<div>';
                html += '<span class="cursor-pointer text-blue addsignnewans" data-id="' + id + '" >';
                html += '<i class="text-blue fa fa-plus mr-3"></i>Add next answer';
                html += '</span>';
                html += '</div>';

                $(".inputappend" + id).append(html);
            }

            if (ans == 'multipleCheckbox') {
                $(this).closest('.card').find(".ml-2").show();

                html += '<div class="d-flex justify-content-between">';
                html += '<div class="form-group mb-0 mr-2 w-100">';
                html += '<div class="form-group">';
                html += '<label class="font-weight-bolder">	Answer 1 ';
                html += '</label>';
                html += '<input type="text" class="form-control ans" id="ans1" required="required">';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="d-flex justify-content-between ">';
                html += '<div class="form-group mb-0 mr-2 w-100">';
                html += '<div class="form-group">';
                html += '<label class="font-weight-bolder"> Answer 2 ';
                html += '</label>';
                html += '<input type="text" class="form-control ans" id="ans2" required="required">';
                html += '</div>';
                html += '</div>';
                html += '</div><div class="newsignappans' + id + '"></div>';
                html += '<div>';
                html += '<span class="cursor-pointer text-blue addsignnewans" data-id="' + id + '" >';
                html += '<i class="text-blue fa fa-plus mr-3"></i>Add next answer';
                html += '</span>';
                html += '</div>';

                $(".inputappend" + id).append(html);
            }

            if (ans == 'dropdown') {
                $(this).closest('.card').find(".ml-2").show();
                html += '<div class="d-flex justify-content-between">';
                html += '<div class="form-group mb-0 mr-2 w-100">';
                html += '<div class="form-group">';
                html += '<label class="font-weight-bolder">	Answer 1 ';
                html += '</label>';
                html += '<input type="text" class="form-control ans" id="ans1" required="required">';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="d-flex justify-content-between ">';
                html += '<div class="form-group mb-0 mr-2 w-100">';
                html += '<div class="form-group">';
                html += '<label class="font-weight-bolder"> Answer 2 ';
                html += '</label>';
                html += '<input type="text" class="form-control ans" id="ans2" required="required">';
                html += '</div>';
                html += '</div>';
                html += '</div><div class="newsignappans' + id + '"></div>';
                html += '<div>';
                html += '<span class="cursor-pointer text-blue addsignnewans" data-id="' + id + '" >';
                html += '<i class="text-blue fa fa-plus mr-3"></i>Add next answer';
                html += '</span>';
                html += '</div>';

                $(".inputappend" + id).append(html);
            }

            if (ans == 'des') {
                $(this).closest('.card').find(".ml-2").hide();
                html += '<div class="form-group mb-0 mr-2 w-100">';
                html += '<div class="form-group">';
                html += '<label class="font-weight-bolder">description Text</label>';
                html += '<textarea rows="3" class="form-control" id="ans1" required="required"></textarea>';
                html += '</div>';
                html += '</div>';
                $(".inputappend" + id).append(html);
            }

            if (ans == 'yesOrNo') {
                $(this).closest('.card').find(".ml-2").show();
            }

            if (ans == 'shortAnswer') {
                $(this).closest('.card').find(".ml-2").show();
            }

            if (ans == 'longAnswer') {
                $(this).closest('.card').find(".ml-2").show();
            }
            if (ans == 'singleCheckbox') {
                $(this).closest('.card').find(".ml-2").show();
            }


        });

        $(document).on('click', '.addsignnewans', function() {
            var id = $(this).data('id');
            var index = ($(this).closest('.inputappend' + id).find('.d-flex').length) + 1;

            var html = '';
            html += '<div class="d-flex justify-content-between removes ">';
            html += '<div class="form-group mb-0 mr-2 w-100">';
            html += '<div class="form-group">';
            html += '<label class="font-weight-bolder">	Answer ' + index + ' ';
            html += '</label>';
            html += '<input type="text" class="form-control ans" id="ans' + index + '">';
            html += '</div>';
            html += '</div>';
            html += '<div class="form-group mb-0 ml-2 removesignans">';
            html += '<label>&nbsp;</label>';
            html +=
                '<button class="btn btn-sm btn-white "><i class="p-0 fa fa-trash text-danger"></i></button>';
            html += '</div>';
            html += '</div>';

            $(".newsignappans" + id).append(html);

        });

        $(document).on('click', '.removesignans', function() {
            $(this).parent('.removes').remove();
        });

		$(document).on('click','#addnewqnatomodel', function(){
			var data = $('#newqna').serialize();
			var explodeData = data.split("=");
			var fieldType   = explodeData[1];
			
			$(".showdata:last .dropDownId").val(fieldType).trigger('change'); 
		});

        $(document).on('click', '.addnewqnatomodel', function() {
            var data = $('#newqna').serialize();
            console.log(data);
            var html = '';

			var unique_id = makeid(10);

            //html += '<form name="qnaform" id="qnaform">';
            html += '<div class="card-body showdata" id="divIndex'+unique_id+'">';
            html += '<div class="card">';
            html += '<div class="card-body bg-content">';
            html += '<div class="d-flex justify-content-between ">';
            html +=
                '<div class="form-group mr-2 w-100"><label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label><select class="form-control dropDownId" data-id="' +
                sectionid +
                '" tabindex="null"><option data-icon="fas fa-grip-lines" value="shortAnswer">Short answer</option><option data-icon="fas fa-align-left" value="longAnswer">Long answer</option><option data-icon="far fa-dot-circle" value="singleAnswer">Single answer</option><option data-icon="far fa-check-square" value="singleCheckbox">Singlecheckbox</option><option data-icon="fas fa-tasks" value="multipleCheckbox">Multiplechoice</option><option data-icon="far fa-caret-square-down" value="dropdown">Drop-down</option><option data-icon="fas fa-adjust" value="yesOrNo">Yes or No</option><option data-icon="far fa-font-case" value="des">Description text</option></select></div>';

            html += '<div class="form-group ml-2 w-100">';
            html += '<label class="font-weight-bolder" for="exampleSelect1">Question</label>';
            html +=
                '<input type="text" class="form-control" id="question" placeholder="New question copy">';
            html += '</div>';

            html += '</div>';
            html += '<div class="inputappend' + sectionid + '"></div>'
            html += '<hr>';
            html += '<div class="d-flex flex-wrap justify-content-between align-items-center">';
            html += '<div class="">';
            html += '<div class="form-group mb-0">';
            html +=
                '<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">';
            html += '<label class="d-flex align-item-center font-weight-bolder">';
            html += '<input type="checkbox" checked="checked" id="required" >';
            html += '<span></span>&nbsp;Required';
            html += '</label>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '<div class="d-flex align-items-center">';
            html += '<span class="border-right p-3">';
            html += '<a href="javascript:;" class="chageToUpper"><i class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i></a>';
            html += '<a href="javascript:;" class="chageToLower"><i class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i></a>';
            html += '</span>';
            html += '<span class="border-right p-3">';
            html += '<i class="mx-1 far fa-clone fa-2x"></i>';
            html += '<i class="mx-1 fas fa-trash fa-2x text-danger"></i>';
            html += '</span>';
            html += '<span class="p-3">';
            html +=
                '<button type="button" class="mx-2 btn btn-sm btn-white"><i class="p-0 fa fa-times"></i></button>';
            html +=
                '<button type="button" class="mx-2 btn btn-sm btn-primary saveques"><i class="p-0 fas fa-check"></i></button>';
            html += '</span>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            $(this).parent().parent().find('#addnewqnainsec').append(html);
            console.log($(this).parent().parent());
        });
		
		$(document).on('change','#country_code_select',function(){
			var valthis = $(this).val();
			$("#country_code").val(valthis);
		});
		
		$(document).on('click','.chageToUpper',function(){
			var currentDiv  = $(this).closest('.showdata').attr('id');
			var previousDiv = $(this).closest('.showdata').prev('.showdata').attr('id');
			if(typeof previousDiv === "undefined"){
				var previousDiv = $(this).closest('.showdata').parent().prev('.showdata').attr('id');
			} 
			
			if(typeof previousDiv === "undefined"){
			} else {
				div1 = $('#'+currentDiv);
				div2 = $('#'+previousDiv);
				
				var div1SelectVal = $(div1).find('select').val();
				var div2SelectVal = $(div2).find('select').val();
				
				tdiv1 = div1.clone(true);
				tdiv2 = div2.clone(true);
				
				if(!div2.is(':empty')){
					div1.replaceWith(tdiv2);
					div2.replaceWith(tdiv1);	
					
					tdiv2.find('select').val(div2SelectVal);
					tdiv1.find('select').val(div1SelectVal);
				}
			}
		});
		
		$(document).on('click','.chageToLower',function(){
			var currentDiv  = $(this).closest('.showdata').attr('id');
			var nextDiv = $(this).closest('.showdata').next('.showdata').attr('id');
			
			if(typeof nextDiv === "undefined"){
				var nextDiv = $(this).closest('.showdata').next('div').children('.showdata').attr('id');
			} 
			
			if(typeof nextDiv === "undefined"){
			} else {
				div1 = $('#'+currentDiv);
				div2 = $('#'+nextDiv);
				
				var div1SelectVal = $(div1).find('select').val();
				var div2SelectVal = $(div2).find('select').val();
				
				tdiv1 = div1.clone(true);
				tdiv2 = div2.clone(true);
				
				if(!div2.is(':empty')){
					div1.replaceWith(tdiv2);
					div2.replaceWith(tdiv1);	
					
					tdiv2.find('select').val(div2SelectVal);
					tdiv1.find('select').val(div1SelectVal);
				}
			}
		});
    });

</script>
<script src="{{ asset('js/editconFormClienInfo.js') }}"></script>
<script>
	$(document).on('keyup','#searchForCategory',function(){	
		var searchKeyWord = $(this).val();
		var csrf = $("input[name=_token]").val();
		$.ajax({
			type: "POST",
			url: "{{ route('searchForServiceCategory') }}",
			dataType: 'json',
			data: {searchKeyWord : searchKeyWord,_token : csrf},
			success: function (data) {
				//KTApp.unblockPage();
				$(".multicheckbox").html(data.html);
				
				$("#treeview").hummingbird();
				$("#treeview").on("CheckUncheckDone", function() {
					var count = $('input[name="value_checkbox[]"]:checked').length;
					var allCount = $('input[type="checkbox"]:checked').length;
					var allCheck = $('input[type="checkbox"]').length;

					if (allCheck == allCount) {
						$("#serviceInput").val('All Service Selected')
					} else {
						$("#serviceInput").val(count + ' service Selected')
					}
				});
			}
		});
	});
</script>
@endsection
