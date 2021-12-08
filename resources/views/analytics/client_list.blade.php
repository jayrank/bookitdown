{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
@endsection

@section('content')
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Tabs-->
                    <div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
                        <div
                            class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
                            <ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
                                role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('analyticsHome') }}">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('reportList') }}">Reports</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--end::Tabs-->
                    <!--begin::Entry-->
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container">
                            <!--begin::Sales-->
                            <!--begin::Row-->
                            <div class="row">
                                <div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
                                    <!--begin::List Widget 3-->
                                    <div class="card-custom card-stretch gutter-b">
                                        <!--begin::Body-->
                                        <!--begin::Item-->
                                        <div class="content-header" style="margin-top:2%">
                                            <div class="mb-3">
                                                <a onclick="history.back()" class="font-size-lg text-blue cursor-pointer"><i
                                                        class="fa fa-chevron-left text-blue mr-2"></i>Reports</a>
                                                <h3 class="my-3 font-weight-bolder">Clients List</h3>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div class="calender-div my-2">
                                                    <div class="input-group date">
                                                        <div class="form-group mb-0 mr-2">
                                                            <div class='input-icon' id='kt_daterangepicker_6'>
                                                                <input type='text' class="form-control kt_date" readonly
                                                                    placeholder="Select date range" />
                                                                <span class=""><i
                                                                        class="la la-calendar-check-o"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="action-btn-div">
                                                    <div class="dropdown dropdown-inline mr-2">
                                                        <button type="button"
                                                            class="btn btn-lg btn-white my-2 font-weight-bolder dropdown-toggle my-2"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <span class="svg-icon svg-icon-md">
                                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
                                                                <svg xmlns="http://www.w3.org/2000/svg">
                                                                    <g>
                                                                        <path
                                                                            d="M15.072 9.62c.506 0 .911.405.911.912v4.962a.908.908 0 0 1-.911.911H.962c-.506 0-.945-.405-.945-.911v-4.962c0-.507.439-.912.945-.912s.911.405.911.912v4.017H14.16v-4.017c0-.507.405-.912.912-.912z">
                                                                        </path>
                                                                        <path
                                                                            d="M7.376 11.68L3.662 7.965a.878.878 0 0 1 0-1.282.878.878 0 0 1 1.283 0l2.16 2.126V.911c0-.506.406-.911.912-.911s.911.405.911.911v7.9l2.127-2.127a.917.917 0 0 1 1.316 0 .878.878 0 0 1 0 1.282L8.658 11.68a.922.922 0 0 1-.641.27.922.922 0 0 1-.641-.27z">
                                                                        </path>
                                                                    </g>
                                                                </svg>
                                                                <!--end::Svg Icon-->
                                                            </span>Export</button>
                                                        <!--begin::Dropdown Menu-->
                                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                            <!--begin::Navigation-->
                                                            <ul class="navi flex-column navi-hover py-2">
                                                                <li class="navi-item getClientListExcel">
                                                                    <a href="#" class="navi-link">
                                                                        <span class="navi-icon">
                                                                            <i class="la la-file-excel-o"></i>
                                                                        </span>
                                                                        <span class="navi-text">Excel</span>
                                                                    </a>
                                                                </li>
                                                                <li class="navi-item getClientListCSV">
                                                                    <a href="#" class="navi-link">
                                                                        <span class="navi-icon">
                                                                            <i class="la la-file-text-o"></i>
                                                                        </span>
                                                                        <span class="navi-text">CSV</span>
                                                                    </a>
                                                                </li>
                                                                <li class="navi-item getClientListPDF">
                                                                    <a href="#" class="navi-link">
                                                                        <span class="navi-icon">
                                                                            <i class="la la-file-pdf-o"></i>
                                                                        </span>
                                                                        <span class="navi-text">PDF</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                            <!--end::Navigation-->
                                                        </div>
                                                        <!--end::Dropdown Menu-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row reports mx-1 my-3">
                                            <div class="table-responsive bg-white apointment">
                                                <table cellspacing="10" cellpadding="10" class="table getClientListTable">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Blocked</th>
                                                            <th scope="col">Appointments</th>
                                                            <th scope="col">No Shows</th>
                                                            <th scope="col">Total Sales</th>
                                                            <th scope="col">Outstanding</th>
                                                            <th scope="col">Gender</th>
                                                            <th scope="col">Added</th>
                                                            <th scope="col">Last Appointment</th>
                                                            <th scope="col">Last Location</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Jayesh G</td>
                                                            <td>No</td>
                                                            <td>4</td>
                                                            <td>0</td>
                                                            <td>CA $0.00</td>
                                                            <td>CA $0.00</td>
                                                            <td>2 Jan 2021</td>
                                                            <td>5 Jan 2021</td>
                                                            <td></td>
                                                        </tr>
                                                        <tr class="text-center text-muted">
                                                            <td colspan="10">Showing 1 of 1 results</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!--end:Item-->
                                        <!--end::Body-->
                                    </div>
                                    <!--end::List Widget 3-->
                                </div>
                            </div>
                            <!--end::Row-->

                            <!--end::Sales-->
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Entry-->
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                <div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
                    <!--begin::Container-->
                    <div
                        class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <!--begin::Copyright-->
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted font-weight-bold mr-2">2020©</span>
                            <a href="http://keenthemes.com/metronic" target="_blank"
                                class="text-dark-75 text-hover-primary">Schedulethat</a>
                        </div>
                        <!--end::Copyright-->
                        <!--begin::Nav-->
                        <!-- <div class="nav nav-dark">
							<a href="http://keenthemes.com/metronic" target="_blank"
								class="nav-link pl-0 pr-5">About</a>
							<a href="http://keenthemes.com/metronic" target="_blank" class="nav-link pl-0 pr-5">Team</a>
							<a href="http://keenthemes.com/metronic" target="_blank"
								class="nav-link pl-0 pr-0">Contact</a>
						</div> -->
                        <!--end::Nav-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Main-->
    <!--begin::Quick Cart-->
    <div id="kt_quick_cart" class="offcanvas offcanvas-right p-10">
        <!--begin::Header-->
        <div class="offcanvas-header d-flex align-items-center justify-content-between pb-7">
            <h4 class="font-weight-bold m-0">Shopping Cart</h4>
            <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_cart_close">
                <i class="ki ki-close icon-xs text-muted"></i>
            </a>
        </div>
        <!--end::Header-->
        <!--begin::Content-->
        <div class="offcanvas-content">
            <!--begin::Wrapper-->
            <div class="offcanvas-wrapper mb-5 scroll-pull">
                <!--begin::Item-->
                <div class="d-flex align-items-center justify-content-between py-8">
                    <div class="d-flex flex-column mr-2">
                        <a href="#" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">iBlender</a>
                        <span class="text-muted">The best kitchen gadget in 2020</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 350</span>
                            <span class="text-muted mr-1">for</span>
                            <span class="font-weight-bold mr-2 text-dark-75 font-size-lg">5</span>
                            <a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
                                <i class="ki ki-minus icon-xs"></i>
                            </a>
                            <a href="#" class="btn btn-xs btn-light-success btn-icon">
                                <i class="ki ki-plus icon-xs"></i>
                            </a>
                        </div>
                    </div>
                    <a href="#" class="symbol symbol-70 flex-shrink-0">
                        <!-- <img src="assets/media/stock-600x400/img-1.jpg" title="" alt="" /> -->
                    </a>
                </div>
                <!--end::Item-->
                <!--begin::Separator-->
                <div class="separator separator-solid"></div>
                <!--end::Separator-->
                <!--begin::Item-->
                <div class="d-flex align-items-center justify-content-between py-8">
                    <div class="d-flex flex-column mr-2">
                        <a href="#"
                            class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">SmartCleaner</a>
                        <span class="text-muted">Smart tool for cooking</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 650</span>
                            <span class="text-muted mr-1">for</span>
                            <span class="font-weight-bold mr-2 text-dark-75 font-size-lg">4</span>
                            <a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
                                <i class="ki ki-minus icon-xs"></i>
                            </a>
                            <a href="#" class="btn btn-xs btn-light-success btn-icon">
                                <i class="ki ki-plus icon-xs"></i>
                            </a>
                        </div>
                    </div>
                    <a href="#" class="symbol symbol-70 flex-shrink-0">
                        <!-- <img src="assets/media/stock-600x400/img-2.jpg" title="" alt="" /> -->
                    </a>
                </div>
                <!--end::Item-->
                <!--begin::Separator-->
                <div class="separator separator-solid"></div>
                <!--end::Separator-->
                <!--begin::Item-->
                <div class="d-flex align-items-center justify-content-between py-8">
                    <div class="d-flex flex-column mr-2">
                        <a href="#" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">CameraMax</a>
                        <span class="text-muted">Professional camera for edge cutting shots</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 150</span>
                            <span class="text-muted mr-1">for</span>
                            <span class="font-weight-bold mr-2 text-dark-75 font-size-lg">3</span>
                            <a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
                                <i class="ki ki-minus icon-xs"></i>
                            </a>
                            <a href="#" class="btn btn-xs btn-light-success btn-icon">
                                <i class="ki ki-plus icon-xs"></i>
                            </a>
                        </div>
                    </div>
                    <a href="#" class="symbol symbol-70 flex-shrink-0">
                        <!-- <img src="assets/media/stock-600x400/img-3.jpg" title="" alt="" /> -->
                    </a>
                </div>
                <!--end::Item-->
                <!--begin::Separator-->
                <div class="separator separator-solid"></div>
                <!--end::Separator-->
                <!--begin::Item-->
                <div class="d-flex align-items-center justify-content-between py-8">
                    <div class="d-flex flex-column mr-2">
                        <a href="#" class="font-weight-bold text-dark text-hover-primary">4D Printer</a>
                        <span class="text-muted">Manufactoring unique objects</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 1450</span>
                            <span class="text-muted mr-1">for</span>
                            <span class="font-weight-bold mr-2 text-dark-75 font-size-lg">7</span>
                            <a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
                                <i class="ki ki-minus icon-xs"></i>
                            </a>
                            <a href="#" class="btn btn-xs btn-light-success btn-icon">
                                <i class="ki ki-plus icon-xs"></i>
                            </a>
                        </div>
                    </div>
                    <a href="#" class="symbol symbol-70 flex-shrink-0">
                        <!-- <img src="assets/media/stock-600x400/img-4.jpg" title="" alt="" /> -->
                    </a>
                </div>
                <!--end::Item-->
                <!--begin::Separator-->
                <div class="separator separator-solid"></div>
                <!--end::Separator-->
                <!--begin::Item-->
                <div class="d-flex align-items-center justify-content-between py-8">
                    <div class="d-flex flex-column mr-2">
                        <a href="#" class="font-weight-bold text-dark text-hover-primary">MotionWire</a>
                        <span class="text-muted">Perfect animation tool</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 650</span>
                            <span class="text-muted mr-1">for</span>
                            <span class="font-weight-bold mr-2 text-dark-75 font-size-lg">7</span>
                            <a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
                                <i class="ki ki-minus icon-xs"></i>
                            </a>
                            <a href="#" class="btn btn-xs btn-light-success btn-icon">
                                <i class="ki ki-plus icon-xs"></i>
                            </a>
                        </div>
                    </div>
                    <a href="#" class="symbol symbol-70 flex-shrink-0">
                        <!-- <img src="assets/media/stock-600x400/img-8.jpg" title="" alt="" /> -->
                    </a>
                </div>
                <!--end::Item-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Purchase-->
            <div class="offcanvas-footer">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="font-weight-bold text-muted font-size-sm mr-2">Total</span>
                    <span class="font-weight-bolder text-dark-50 text-right">$1840.00</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-7">
                    <span class="font-weight-bold text-muted font-size-sm mr-2">Sub total</span>
                    <span class="font-weight-bolder text-primary text-right">$5640.00</span>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-primary text-weight-bold">Place Order</button>
                </div>
            </div>
            <!--end::Purchase-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Quick Cart-->
    <!--begin::Chat Panel-->
    <div class="modal modal-sticky modal-sticky-bottom-right" id="kt_chat_modal" role="dialog" data-backdrop="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!--begin::Card-->
                <div class="card card-custom">
                    <!--begin::Header-->
                    <div class="card-header align-items-center px-4 py-3">
                        <div class="text-left flex-grow-1">
                            <!--begin::Dropdown Menu-->
                            <div class="dropdown dropdown-inline">
                                <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="svg-icon svg-icon-lg">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                <path
                                                    d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                                    fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                <path
                                                    d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                                    fill="#000000" fill-rule="nonzero" />
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>
                                </button>
                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-md">
                                    <!--begin::Navigation-->
                                    <ul class="navi navi-hover py-5">
                                        <li class="navi-item">
                                            <a href="#" class="navi-link">
                                                <span class="navi-icon">
                                                    <i class="flaticon2-drop"></i>
                                                </span>
                                                <span class="navi-text">New Group</span>
                                            </a>
                                        </li>
                                        <li class="navi-item">
                                            <a href="#" class="navi-link">
                                                <span class="navi-icon">
                                                    <i class="flaticon2-list-3"></i>
                                                </span>
                                                <span class="navi-text">Contacts</span>
                                            </a>
                                        </li>
                                        <li class="navi-item">
                                            <a href="#" class="navi-link">
                                                <span class="navi-icon">
                                                    <i class="flaticon2-rocket-1"></i>
                                                </span>
                                                <span class="navi-text">Groups</span>
                                                <span class="navi-link-badge">
                                                    <span
                                                        class="label label-light-primary label-inline font-weight-bold">new</span>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="navi-item">
                                            <a href="#" class="navi-link">
                                                <span class="navi-icon">
                                                    <i class="flaticon2-bell-2"></i>
                                                </span>
                                                <span class="navi-text">Calls</span>
                                            </a>
                                        </li>
                                        <li class="navi-item">
                                            <a href="#" class="navi-link">
                                                <span class="navi-icon">
                                                    <i class="flaticon2-gear"></i>
                                                </span>
                                                <span class="navi-text">Settings</span>
                                            </a>
                                        </li>
                                        <li class="navi-separator my-3"></li>
                                        <li class="navi-item">
                                            <a href="#" class="navi-link">
                                                <span class="navi-icon">
                                                    <i class="flaticon2-magnifier-tool"></i>
                                                </span>
                                                <span class="navi-text">Help</span>
                                            </a>
                                        </li>
                                        <li class="navi-item">
                                            <a href="#" class="navi-link">
                                                <span class="navi-icon">
                                                    <i class="flaticon2-bell-2"></i>
                                                </span>
                                                <span class="navi-text">Privacy</span>
                                                <span class="navi-link-badge">
                                                    <span
                                                        class="label label-light-danger label-rounded font-weight-bold">5</span>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <!--end::Navigation-->
                                </div>
                            </div>
                            <!--end::Dropdown Menu-->
                        </div>
                        <div class="text-center flex-grow-1">
                            <div class="text-dark-75 font-weight-bold font-size-h5">Matt Pears</div>
                            <div>
                                <span class="label label-dot label-success"></span>
                                <span class="font-weight-bold text-muted font-size-sm">Active</span>
                            </div>
                        </div>
                        <div class="text-right flex-grow-1">
                            <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md"
                                data-dismiss="modal">
                                <i class="ki ki-close icon-1x"></i>
                            </button>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Scroll-->
                        <div class="scroll scroll-pull" data-height="375" data-mobile-height="300">
                            <!--begin::Messages-->
                            <div class="messages">
                                <!--begin::Message In-->
                                <div class="d-flex flex-column mb-5 align-items-start">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-circle symbol-40 mr-3">
                                            <!-- <img alt="Pic" src="assets/media/users/300_12.jpg" /> -->
                                        </div>
                                        <div>
                                            <a href="#"
                                                class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Matt
                                                Pears</a>
                                            <span class="text-muted font-size-sm">2 Hours</span>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
                                        How likely are you to recommend our company to your friends and family?
                                    </div>
                                </div>
                                <!--end::Message In-->
                                <!--begin::Message Out-->
                                <div class="d-flex flex-column mb-5 align-items-end">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <span class="text-muted font-size-sm">3 minutes</span>
                                            <a href="#"
                                                class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
                                        </div>
                                        <div class="symbol symbol-circle symbol-40 ml-3">
                                            <!-- <img alt="Pic" src="assets/media/users/300_21.jpg" /> -->
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
                                        Hey there, we’re just writing to let you know that you’ve been subscribed to
                                        a
                                        repository on GitHub.</div>
                                </div>
                                <!--end::Message Out-->
                                <!--begin::Message In-->
                                <div class="d-flex flex-column mb-5 align-items-start">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-circle symbol-40 mr-3">
                                            <!-- <img alt="Pic" src="assets/media/users/300_21.jpg" /> -->
                                        </div>
                                        <div>
                                            <a href="#"
                                                class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Matt
                                                Pears</a>
                                            <span class="text-muted font-size-sm">40 seconds</span>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
                                        Ok, Understood!</div>
                                </div>
                                <!--end::Message In-->
                                <!--begin::Message Out-->
                                <div class="d-flex flex-column mb-5 align-items-end">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <span class="text-muted font-size-sm">Just now</span>
                                            <a href="#"
                                                class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
                                        </div>
                                        <div class="symbol symbol-circle symbol-40 ml-3">
                                            <!-- <img alt="Pic" src="assets/media/users/300_21.jpg" /> -->
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
                                        You’ll receive notifications for all issues, pull requests!</div>
                                </div>
                                <!--end::Message Out-->
                                <!--begin::Message In-->
                                <div class="d-flex flex-column mb-5 align-items-start">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-circle symbol-40 mr-3">
                                            <!-- <img alt="Pic" src="assets/media/users/300_12.jpg" /> -->
                                        </div>
                                        <div>
                                            <a href="#"
                                                class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Matt
                                                Pears</a>
                                            <span class="text-muted font-size-sm">40 seconds</span>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
                                        You can unwatch this repository immediately by clicking here:
                                        <a href="#">https://github.com</a>
                                    </div>
                                </div>
                                <!--end::Message In-->
                                <!--begin::Message Out-->
                                <div class="d-flex flex-column mb-5 align-items-end">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <span class="text-muted font-size-sm">Just now</span>
                                            <a href="#"
                                                class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
                                        </div>
                                        <div class="symbol symbol-circle symbol-40 ml-3">
                                            <!-- <img alt="Pic" src="assets/media/users/300_21.jpg" /> -->
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
                                        Discover what students who viewed Learn Figma - UI/UX Design. Essential
                                        Training
                                        also viewed</div>
                                </div>
                                <!--end::Message Out-->
                                <!--begin::Message In-->
                                <div class="d-flex flex-column mb-5 align-items-start">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-circle symbol-40 mr-3">
                                            <!-- <img alt="Pic" src="assets/media/users/300_12.jpg" /> -->
                                        </div>
                                        <div>
                                            <a href="#"
                                                class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Matt
                                                Pears</a>
                                            <span class="text-muted font-size-sm">40 seconds</span>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
                                        Most purchased Business courses during this sale!</div>
                                </div>
                                <!--end::Message In-->
                                <!--begin::Message Out-->
                                <div class="d-flex flex-column mb-5 align-items-end">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <span class="text-muted font-size-sm">Just now</span>
                                            <a href="#"
                                                class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
                                        </div>
                                        <div class="symbol symbol-circle symbol-40 ml-3">
                                            <!-- <img alt="Pic" src="assets/media/users/300_21.jpg" /> -->
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
                                        Company BBQ to celebrate the last quater achievements and goals. Food and
                                        drinks
                                        provided</div>
                                </div>
                                <!--end::Message Out-->
                            </div>
                            <!--end::Messages-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="card-footer align-items-center">
                        <!--begin::Compose-->
                        <textarea class="form-control border-0 p-0" rows="2" placeholder="Type a message"></textarea>
                        <div class="d-flex align-items-center justify-content-between mt-5">
                            <div class="mr-3">
                                <a href="#" class="btn btn-clean btn-icon btn-md mr-1">
                                    <i class="flaticon2-photograph icon-lg"></i>
                                </a>
                                <a href="#" class="btn btn-clean btn-icon btn-md">
                                    <i class="flaticon2-photo-camera icon-lg"></i>
                                </a>
                            </div>
                            <div>
                                <button type="button"
                                    class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6">Send</button>
                            </div>
                        </div>
                        <!--begin::Compose-->
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end::Card-->
            </div>
        </div>
    </div>
    <!--end::Chat Panel-->

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
    <!--end::Scrolltop-->
    <script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
    <script src="{{ asset('datatables/datatables.bundle.js') }}"></script>

    <!--end::Global Config-->
    <!--begin::Global Theme Bundle(used by all pages)-->
    <!-- <script src="../assets/plugins/global/plugins.bundle.js"></script> -->
    <!-- <script src="../assets/plugins/custom/prismjs/prismjs.bundle.js"></script> -->
    <!-- <script src="../assets/js/scripts.bundle.js"></script> -->
    <!--end::Global Theme Bundle-->
    <!--begin::Page Vendors(used by this page)-->
    <!-- <script src="../assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script> -->
    <!-- <script src="../assets/js/pages/crud/forms/widgets/bootstrap-daterangepicker.js"></script> -->
    <!--end::Page Vendors-->
    <!--begin::Page Scripts(used by this page)-->
    <!-- <script src="../assets/js/pages/widgets.js"></script> -->
    <!--end::Page Scripts-->
    <script>
        jQuery(document).ready(function () {
            var start = moment().subtract(29, 'days');
            var end = moment();
            $('#kt_daterangepicker_6 .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            getClientList();

            $('#kt_daterangepicker_6').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary',

                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    // 'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end, label) {
                $('#kt_daterangepicker_6 .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));

                getClientList();
            });
            $(document).on('click','.scrolltop',function(){
                KTUtil.scrollTop();
            });

            $(document).on('click','.getClientListPDF', function(){
                KTApp.blockPage();
                var date = $(".kt_date").val();

                if(date != '') {
                    var new1 = date.split('-');
                    var startdate = new Date(new1[0]);
                    var enddate = new Date(new1[1]);
                    var start_date = moment(startdate).format('YYYY-MM-DD');
                    var end_date = moment(enddate).format('YYYY-MM-DD');
                } else {
                    var new1 = '';
                    var startdate = '';
                    var enddate = '';
                    var start_date = '';
                    var end_date = '';
                }

                if (date != '') {
                    var fileName = 'client_list_' + start_date + '_' + end_date + '.pdf';
                } else {
                    var fileName = 'client_list.pdf';
                }

                $.ajax({
                    url: '{{ route("getClientListPDF") }}',
                    type: 'POST',
                    data: {
                        start_date: start_date,
                        end_date: end_date
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response) {
                        toastr.success((response.message) ? response.message : "PDF Downloading...");

                        var blob = new Blob([response]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = fileName;
                        link.click();
                    },
                    error: function(response) {
                        toastr.error("PDF Not Downloding!! Something Went Wrong!");
                    },
                    complete: function(response) {
                        KTApp.unblockPage();
                    }
                });
            });

            $(document).on('click','.getClientListCSV', function(){
                KTApp.blockPage();
                var date = $(".kt_date").val();

                if(date != '') {
                    var new1 = date.split('-');
                    var startdate = new Date(new1[0]);
                    var enddate = new Date(new1[1]);
                    var start_date = moment(startdate).format('YYYY-MM-DD');
                    var end_date = moment(enddate).format('YYYY-MM-DD');
                } else {
                    var new1 = '';
                    var startdate = '';
                    var enddate = '';
                    var start_date = '';
                    var end_date = '';
                }

                if (date != '') {
                    var fileName = 'client_list_' + start_date + '_' + end_date + '.csv';
                } else {
                    var fileName = 'client_list.csv';
                }

                $.ajax({
                    url: '{{ route("getClientListCSV") }}',
                    type: 'POST',
                    data: {
                        start_date: start_date,
                        end_date: end_date
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response) {
                        toastr.success((response.message) ? response.message : "CSV Downloading...");

                        var blob = new Blob([response]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = fileName;
                        link.click();
                    },
                    error: function(response) {
                        toastr.error("CSV Not Downloding!! Something Went Wrong!");
                    },
                    complete: function(response) {
                        KTApp.unblockPage();
                    }
                });
            });

            $(document).on('click','.getClientListExcel', function(){
                KTApp.blockPage();
                var date = $(".kt_date").val();

                if(date != '') {
                    var new1 = date.split('-');
                    var startdate = new Date(new1[0]);
                    var enddate = new Date(new1[1]);
                    var start_date = moment(startdate).format('YYYY-MM-DD');
                    var end_date = moment(enddate).format('YYYY-MM-DD');
                } else {
                    var new1 = '';
                    var startdate = '';
                    var enddate = '';
                    var start_date = '';
                    var end_date = '';
                }

                if (date != '') {
                    var fileName = 'client_list_' + start_date + '_' + end_date + '.xlsx';
                } else {
                    var fileName = 'client_list.xlsx';
                }

                $.ajax({
                    url: '{{ route("getClientListExcel") }}',
                    type: 'POST',
                    data: {
                        start_date: start_date,
                        end_date: end_date
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response) {
                        toastr.success((response.message) ? response.message : "Excel Downloading...");

                        var blob = new Blob([response]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = fileName;
                        link.click();
                    },
                    error: function(response) {
                        toastr.error("Excel Not Downloding!! Something Went Wrong!");
                    },
                    complete: function(response) {
                        KTApp.unblockPage();
                    }
                });
            });
        });

        function getClientList(){
            var date = $(".kt_date").val();
            
            if(date != '') {
                var new1 = date.split('-');
                var startdate = new Date(new1[0]);
                var enddate = new Date(new1[1]);
                var start_date = moment(startdate).format('YYYY-MM-DD');
                var end_date = moment(enddate).format('YYYY-MM-DD');
            } else {
                var new1 = '';
                var startdate = '';
                var enddate = '';
                var start_date = '';
                var end_date = '';
            }

            var datatable = $('.getClientListTable').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "autoWidth": false,
                "searching": false,
                "bLengthChange": false,
                "ordering": false,
                ajax : {
                    url: '{{ route("getClientList") }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        start_date: start_date,
                        end_date: end_date
                    }
                },
                columns: [
                    {data: 'name', profile: 'name'},
                    {data: 'blocked', name: 'blocked'},
                    {data: 'appointments', name: 'appointments'},
                    {data: 'no_show', name: 'no_show'},
                    {data: 'total_sales', name: 'total_sales'},
                    {data: 'outstanding', name: 'outstanding'},
                    {data: 'gender', name: 'gender'},
                    {data: 'added', name: 'added'},
                    {data: 'last_appointment', name: 'last_appointment'},
                    {data: 'last_location', name: 'last_location'},
                ]	
            });
        }
    </script>
    <script type="text/javascript">

        function openNav() {
            document.getElementById("myFilter").style.width = "300px";
        }

        function closeNav() {
            document.getElementById("myFilter").style.width = "0%";
        }
    </script>
@endsection