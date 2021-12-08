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
                <div class="p-4 d-flex align-items-center border-bottom">
                    <p class="cursor-pointer m-0 px-6" onclick="history.back();"><i
                            class="text-dark fa fa-times icon-lg"></i>
                    </p>
                    <span class="text-center m-auto">
                        <h1 class="font-weight-bolder main-title">Consultation form preview</h1>
                    </span>
                </div>
            </div> -->
            <div class="my-custom-header bg-white"> 
                <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
                    <div style="display: flex; justify-content: flex-start; align-items: center;">
                        <a type="button" class="close" data-dismiss="modal" aria-label="Close" style="top:unset;left: 0;opacity: 1;font-size: 1.75rem;" onclick="window.history.back();">
                            <span aria-hidden="true" class="la la-times"></span>
                        </a> 
                    </div>
                    <div style="flex-grow: 1; text-align: center;"> 
                        <h2 class="font-weight-bolder main-title">Consultation form preview</h2>
                    </div> 
                </div>
            </div>
            <div class="my-custom-body bg-content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-10">
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
                                
                            @endphp
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
                    
                                                    <div class="form-group showmobile" @if ($value1[0]['mobile'] == 1) style="display: block" @else style="display: none" @endif >
                                                        <label class="font-weight-bolder">Mobile Number
                                                        </label>
                                                        <input type="text" class="form-control" >
                                                    </div>
                    
                                                    <div class="form-group showgender" @if ($value1[0]['gender']) ) style="display: block" @else style="display: none" @endif >
                                                        <label class="font-weight-bolder">Gender
                                                        </label>
                                                        <input type="text" class="form-control" >
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

                                            @for ($j = 0; $j <= $max1; $j++)
                                                
                                                <div class="preview-tab1" >
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                                @if ($value1[$j]['answer_type'] == 'des')
                                                                            
                                                                @else
                                                                    <label class="font-weight-bolder">{{ $value1[$j]['question'] }}</label>
                                                                @endif
                                                                <input type="hidden" class="form-control" name="required[]" value="{{ $value1[$j]['required'] }}">
                                                                <input type="hidden" class="form-control" name="answer_type[]" value="{{ $value1[$j]['answer_type'] }}">
                                                                @if($value1[$j]['answer_type'] == 'des')
                                                                    <input type="hidden" name="question[]" value="null" class="form-control">
                                                                @else
                                                                    <input type="hidden" class="form-control" name="question[]" value="{{ $value1[$j]['question'] }}">
                                                                @endif
                                                
                                                                @if ($value1[$j]['answer_type'] == 'shortAnswer')
                                                                    <input type="text" class="form-control" name="ans1" value="input">
                                                                @endif
                                                                @if ($value1[$j]['answer_type'] == 'longAnswer')
                                                                    <textarea rows="3" class="form-control" name="ans2"></textarea>
                                                                @endif

                                                                @if ($value1[$j]['answer_type'] == 'singleAnswer')
                                                                    <div class="radio-list">
                                                                        @php 
                                                                            $max1 = count(json_decode($value1[$j]['3ans']))==1 ? 0 : count(json_decode($value1[$j]['3ans']))-1 ;
                                                                        @endphp
                                                                        @for ($v = 0; $v <= $max1; $v++)
                                                                            @php
                                                                                $ansdata = json_decode($value1[$j]['3ans'])[$v];
                                                                            @endphp
                                                                            <label class="radio">
                                                                                <input type="radio" value="{{ $ansdata }}" >
                                                                                <input type="hidden" name="ans3[]" value="{{ $ansdata }}" >
                                                                                <span></span>{{ $ansdata }}
                                                                            </label>
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                                @if ($value1[$j]['answer_type'] == 'singleCheckbox')
                                                                    <input type="checkbox" class="form-control" name="ans4">
                                                                @endif
                                                                @if ($value1[$j]['answer_type'] == 'multipleCheckbox')
                                                                    <div class="checkbox-list">
                                                                        @php
                                                                            $max1 = count(json_decode($value1[$j]['5ans']))==1 ? 0 : count(json_decode($value1[$j]['5ans']))-1 ;
                                                                        @endphp
                                                                        @for ($v = 0; $v <= $max1; $v++)
                                                                            @php $ansdata = json_decode($value1[$j]['5ans'])[$v]; @endphp
                                                                            <label class="checkbox">
                                                                                <input type="checkbox" value="{{ $ansdata }}" >
                                                                                <input type="hidden" name="ans5[]" value="{{ $ansdata }}" >
                                                                                <span></span>{{ $ansdata }}
                                                                            </label>
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                                @if ($value1[$j]['answer_type'] == 'dropdown')
                                                                    <div class="dropdown">
                                                                        <select class="custom-select" name="6ans" >
                                                                            <option disable>Choose...</option>
                                                                            @php 
                                                                                $max1 = count(json_decode($value1[$j]['6ans']))==1 ? 0 : count(json_decode($value1[$j]['6ans']))-1 ;
                                                                            @endphp
                                                                            @for ($v = 0; $v <= $max1; $v++)
                                                                                @php $ansdata = json_decode($value1[$j]['6ans'])[$v]; @endphp
                                                                                <option selected value="{{  $ansdata  }}">{{  $ansdata  }}</option>
                                                                            @endfor
                                                                        </select>

                                                                        @for ($v = 0; $v <= $max1; $v++)
                                                                            @php $ansdata = json_decode($value1[$j]['6ans'])[$v]; @endphp
                                                                            <input type="hidden" name="ans6[]" value="{{  $ansdata  }}" >
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                                @if ($value1[$j]['answer_type'] == 'yesOrNo')
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
                                                                @if ($value1[$j]['answer_type'] == 'des')
                                                                    <textarea rows="3" class="form-control" name="ans8">{{ $value1[$j]['8ans'] }}</textarea>
                                                                @endif
                                                            {{--  @endif  --}}
                                                            {{--  @if ($j >= 1)

                                                                <hr>
                                                                @if ($value1[$j]['answer_type'] == 'shortAnswer')
                                                                    <input type="text" class="form-control" name="ans1" value="input">
                                                                @endif
                                                                @if ($value1[$j]['answer_type'] == 'longAnswer')
                                                                    <textarea rows="3" class="form-control" name="ans2"></textarea>
                                                                @endif
                                                                @if ($value1[$j]['answer_type'] == 'singleAnswer')
                                                                    <div class="radio-list">
                                                                        @php 
                                                                            $cont1 = count(json_decode($value1[$j]['3ans']))==1 ? 0 : count(json_decode($value1[$j]['3ans']))-1 ;
                                                                        @endphp
                                                                        @for ($v = 0; $v <= $cont1; $v++)
                                                                            @php
                                                                                $ansdata = json_decode($value1[$j]['3ans'])[$v];
                                                                            @endphp
                                                                            <label class="radio">
                                                                                <input type="radio" value="{{ $ansdata }}" >
                                                                                <input type="hidden" name="ans3[]" value="{{ $ansdata }}" >
                                                                                <span></span>{{ $ansdata }}
                                                                            </label>
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                                @if ($value1[$j]['answer_type'] == 'singleCheckbox')
                                                                    <input type="checkbox" class="form-control" name="ans4">
                                                                @endif
                                                                @if ($value1[$j]['answer_type'] == 'multipleCheckbox')
                                                                    <div class="checkbox-list">
                                                                        @php
                                                                            $cont1 = count(json_decode($value1[$j]['5ans']))==1 ? 0 : count(json_decode($value1[$j]['5ans']))-1 ;
                                                                        @endphp
                                                                        @for ($v = 0; $v <= $cont1; $v++)
                                                                            @php $ansdata = json_decode($value1[$j]['5ans'])[$v]; @endphp
                                                    
                                                                            <label class="checkbox">
                                                                            <input type="checkbox" value="{{ $ansdata }}" >
                                                                            <input type="hidden" name="ans5[]" value="{{ $ansdata }}" >
                                                                            <span></span>{{ $ansdata }}</label>
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                                @if ($value1[$j]['answer_type'] == 'dropdown')
                                                                    <div class="dropdown">
                                                                        <select class="custom-select" name="6ans" >
                                                                            <option disable>Choose...</option>
                                                                            @php 
                                                                                $cont1 = count(json_decode($value1[$j]['6ans']))==1 ? 0 : count(json_decode($value1[$j]['6ans']))-1 ;
                                                                            @endphp
                                                                            @for ($v = 0; $v <= $cont1; $v++)
                                                                                    @php $ansdata = json_decode($value1[$j]['6ans'])[$v]; @endphp
                                                                                <option selected value="{{  $ansdata  }}">{{  $ansdata  }}</option>
                                                                            @endfor
                                                                        </select>

                                                                        @for ($v = 1; $v <= $cont1; $v++)
                                                                            @php $ansdata = json_decode($value1[$j]['6ans'])[$v]; @endphp
                                                                            <input type="hidden" name="ans6[]" value="{{  $ansdata  }}" >
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                                @if ($value1[$j]['answer_type'] == 'yesOrNo')
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
                                                                @if ($value1[$j]['answer_type'] == 'des')
                                                                    <textarea rows="3" class="form-control" name="ans8">{{ $value1[$j]['8ans'] }}</textarea>
                                                                @endif
                                                            @endif  --}}
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


    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop">
        <span class="svg-icon">
            <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                height="24px" viewBox="0 0 24 24" version="1.1">
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
@endsection

{{-- Scripts Section --}}
@section('scripts')
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
    </script>
@endsection
