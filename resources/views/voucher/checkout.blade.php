{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')

	<style>
		.help-inline{
			color: red;
		}
		.parpal{
			background: linear-gradient(-45deg, rgb(190, 74, 244) 0%, rgb(92, 55, 246) 100%);
		}
		.blue{
			background: linear-gradient(-225deg, rgb(11, 109, 217) 0%, rgb(95, 171, 255) 100%);
		}
		.black{
			background: linear-gradient(-225deg, rgb(16, 25, 40) 0%, rgb(32, 48, 71) 100%);
		}
		.green{
			background: linear-gradient(-45deg, rgb(0, 166, 156) 0%, rgb(0, 157, 98) 100%);
		}
		.orange{
			background: linear-gradient(-45deg, rgb(237, 176, 27) 0%, rgb(222, 100, 38) 100%);
		}
	</style>
@endsection

@section('content')

	<div class="container-fluid invoice-view p-0">
		<div class="p-4 d-flex justify-content-between" id="title-header">
			<h2 class="m-auto font-weight-bolder">Checkout</h2>
			<h1 class="cursor-pointer" onclick="history.back();">&times;</h1>
		</div>
		<hr class="m-0" />
		<div class="container-fluid p-0">
			<div class="row m-0">
				<div class="col-12 col-sm-8 p-0 bg-content content-height"
					style="overflow-y: scroll; position: relative;">
					<div class="container p-12 position-relative">
						<div class="card addcard">
							<div class="card-body border-left border-primary border-3">
								<div class="row flex-wrap justify-content-between">
									<div class="d-flex">
										<h4 class="m-1 p-4 fonter-weight-bolder">1</h4>
										<div>
											<h3 class="m-0">{{ $voucher->name }}</h3>
											<p class="text-dark-50">{{ $voucher->validfor }}</p>
										</div>
									</div>
									<div class="d-flex flex-wrap">
										<div>
											<h3 class="m-0 Tprice">CA ${{ $voucher->retailprice }}</h3>
											<h5 class="m-0 text-dark-50"><s>CA ${{ $voucher->value }}</s></h5>
										</div>
										<i class="fa fa-times cursor-pointer text-danger fa-1x mt-2 ml-3"></i>
									</div>
								</div>
								<div class="row px-8">
									<div class="col-md-2 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Quantity</label>
											<input class="form-control" name="quantity" value="1" type="text">
										</div>
									</div>
									<div class="col-md-3 col-sm-6" id="pricing-type">
										<div class="form-group">
											<label class="font-weight-bolder">Price</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">CA $</span>
												</div>
												<input type="text"  class="form-control" value="{{ $voucher->retailprice }}"
													placeholder="0.00">
											</div>
										</div>
									</div>
									<div class="col-md-4 col-sm-6" id="staff">
										<div class="form-group">
											<label class="font-weight-bolder">Staff</label>
											<select class="form-control">
												@foreach($staff as $value)
													<option value="{{ $value->id }}">{{ $value->user->first_name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-md-4 col-sm-6" id="spc-discount">
										<div class="form-group">
											<label class="font-weight-bolder">Discount</label>
											<select class="form-control">
												<option value="no-discount">No Discount</option>
												{{-- <option value="special-offer">Special Discount CA $10 off</option> --}}
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row my-4 p-4">
							<div class="col-12 col-sm-12 col-md-6">
								<h5 data-toggle="modal" data-target="#addItemToSale" class="cursor-pointer text-blue"><i
										class="fa fa-plus text-blue mr-2"></i>Add item to sale</h5>
							</div>
							<div class="col-12 col-sm-12 col-md-6">
								<div>
									<div class="border-bottom d-flex flex-wrap justify-content-between">
										<h5>Total</h5>
										<h5 class="mr-5 total">CA $</h5>
									</div>

									<div class="">
										<div
											class="text-blue border-bottom py-4 d-flex flex-wrap justify-content-between">
											<h5 class="cursor-pointer" data-toggle="modal" data-target="#addTipModal">
												Tip for adam</h5>
											<div class="d-flex align-items-center">
												<h5 class="cursor-pointer m-0">CA $30</h5>
												<i class="ml-2 fa fa-times text-danger fa-1x"></i>
											</div>
										</div>
										<h6 class="py-3 cursor-pointer text-blue" data-toggle="modal"
											data-target="#addTipModal">
											<i class="text-blue fa fa-plus mr-2"></i>
											Add tip
										</h6>
									</div>

									<div class="my-4 font-weight-bolder d-flex flex-wrap justify-content-between">
										<h4>Total</h4>
										<h4 class="mr-5 total">CA $</h4>
									</div>
								</div>
							</div>
						</div>

						<div class="d-flex flex-column align-items-center p-10">
							<div style="width: 50px;height:50px">
								<svg viewBox="0 0 56 58" xmlns="http://www.w3.org/2000/svg">
									<g fill-rule="nonzero">
										<path
											d="M49 20c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h6c.6179394 0 1.0879825.5548673.9863939 1.164399l-3.4430553 20.6583318C52.1408079 42.2329143 50.0545619 44 47.611 44H13c-.4888392 0-.9060293-.353413-.9863939-.835601l-4.00000002-24C7.91201746 18.5548673 8.38206056 18 9 18h8c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-6.81953958l3.66666668 22H47.611c1.46605 0 2.7181281-1.0605314 2.9596061-2.506399L53.8195396 20H49z">
										</path>
										<path
											d="M50 48c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1H13.828C11.7142153 50 10 48.2857847 10 46.171c0-1.014735.40298826-1.9878479 1.1211951-2.7074085l1.172-1.171c.3906909-.3903576 1.0238558-.3900874 1.4142134.0006036.3903576.3906909.3900874 1.0238558-.0006036 1.4142134l-1.171032 1.1700315C12.1925918 45.2212686 12 45.6863283 12 46.171c0 1.0102153.8187847 1.829 1.828 1.829H50zM1 12c-.55228475 0-1-.4477153-1-1 0-.55228475.44771525-1 1-1h4.438c1.3765066 0 2.57649505.93687534 2.9111513 2.2724996l1.621 6.485c.13392929.5357997-.19185111 1.0787224-.72765087 1.2126517-.53579977.1339293-1.07872244-.1918511-1.21265173-.7276509l-1.62086284-6.4844519C6.2973771 12.3126144 5.8969677 12 5.438 12H1zM46 30c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-2c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h2zM20 30c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-2c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h2zM46 36c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-4c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h4zM38 36c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-4c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h4zM30 36c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-4c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h4zM38 30c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-4c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h4zM30 30c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-4c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h4zM22 36c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-4c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h4z">
										</path>
										<path
											d="M41 49c0-.5522847.4477153-1 1-1s1 .4477153 1 1v2c0 .5522847-.4477153 1-1 1s-1-.4477153-1-1v-2z">
										</path>
										<path
											d="M44 54c0-1.1047153-.8952847-2-2-2s-2 .8952847-2 2 .8952847 2 2 2 2-.8952847 2-2zm2 0c0 2.2092847-1.7907153 4-4 4s-4-1.7907153-4-4 1.7907153-4 4-4 4 1.7907153 4 4zM17 49c0-.5522847.4477153-1 1-1s1 .4477153 1 1v2c0 .5522847-.4477153 1-1 1s-1-.4477153-1-1v-2z">
										</path>
										<path
											d="M20 54c0-1.1047153-.8952847-2-2-2s-2 .8952847-2 2 .8952847 2 2 2 2-.8952847 2-2zm2 0c0 2.2092847-1.7907153 4-4 4s-4-1.7907153-4-4 1.7907153-4 4-4 4 1.7907153 4 4zM44 13c0-6.07471525-4.9252847-11-11-11S22 6.92528475 22 13c0 6.0747153 4.9252847 11 11 11s11-4.9252847 11-11zm2 0c0 7.1792847-5.8207153 13-13 13s-13-5.8207153-13-13c0-7.17928475 5.8207153-13 13-13s13 5.82071525 13 13z">
										</path>
										<path
											d="M36.2928932 8.29289322c.3905243-.39052429 1.0236893-.39052429 1.4142136 0 .3905243.39052429.3905243 1.02368927 0 1.41421356l-8 8.00000002c-.3905243.3905243-1.0236893.3905243-1.4142136 0-.3905243-.3905243-.3905243-1.0236893 0-1.4142136l8-7.99999998z">
										</path>
										<path
											d="M28.2928932 9.70710678c-.3905243-.39052429-.3905243-1.02368927 0-1.41421356.3905243-.39052429 1.0236893-.39052429 1.4142136 0l8 7.99999998c.3905243.3905243.3905243 1.0236893 0 1.4142136-.3905243.3905243-1.0236893.3905243-1.4142136 0l-8-8.00000002z">
										</path>
									</g>
								</svg>
							</div>
							<h6 class="my-4 font-weight-bolder">Your order is empty. You haven't added any items yet
							</h6>
							<button class="btn btn-primary px-20" data-toggle="modal"
								data-target="#addItemToSale">
								Add item to sale
							</button>
						</div>
					</div>
					<div class="side-overlay">
						<div id="dismiss">
							<i class="la la-close" style="font-size: 18px;"></i>
							<span
								style="display: block; margin-top: 10px; color: #fff; font-size: 14px; text-transform: uppercase; font-weight: 700; text-shadow: 0 0 4px #67768c;">CLICK
								TO CLOSE</span>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4 p-0 content-height">
					<div class="form-group border-bottom p-6">
						<div class="input-icon">
							<input type="text" class="form-control" placeholder="Search...">
							<span>
								<i class="flaticon2-search-1 icon-md"></i>
							</span>
						</div>
					</div>
					<div class="d-flex align-items-center border-bottom p-6 customer-data" id="sidebarCollapse">
						<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
							<div class="symbol-label rounded-circle"
								style="background-image:url('assets/media/users/300_13.jpg')">
							</div>
						</div>
						<div>
							<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">Jayesh
								G <span class="fonter-weight-bolder">*</span></a>
							<div class="text-muted">+91 89048 52640 <span class="font-weight-bolder">*</span></div>
							<div class="text-muted">tjcloudtest@gmail.com
							</div>
						</div>
						<i class="text-dark fa fa-chevron-right ml-auto"></i>
					</div>
					<div class="customer-bottom" style="overflow: hidden scroll;">
						<div class="customer-bottom-outer">
							<div class="view-appoinment- p-4 mb-2">
								<p class="m-0 font-weight-bolder text-center">Pay</p>
								<div class="p-6 border">
									<h2 class="mb-0 text-center font-weight-bolder">CA $89.00</h2>
								</div>
								<div class="d-flex justify-content-center my-3">
									<p class="mx-2">ScheduleDown Pay accept</p>
									<div class="mx-2" style="height: 28px;width: 28px;">
										<svg viewBox="0 0 40 13" xmlns="http://www.w3.org/2000/svg"
											class="_6pH7Dd _1d8UT3">
											<g fill="none" fill-rule="evenodd">
												<path
													d="M17.3117 12.5356h-3.2444L16.095.3858h3.2447l-2.028 12.1498zM11.338.3858L8.245 8.7425 7.879 6.943l.0003.0006-1.0916-5.4639s-.132-1.094-1.539-1.094H.1353l-.06.2058S1.639.9087 3.469 1.9802L6.2877 12.536H9.668L14.8297.3858H11.338zm25.5183 12.1498h2.979L37.238.3854H34.63c-1.2043 0-1.4977.9055-1.4977.9055l-4.8386 11.2447h3.382l.6763-1.8048h4.1243l.38 1.8048zm-3.57-4.2978L34.991 3.691l.959 4.5468h-2.6637zm-4.739-4.9303l.463-2.609s-1.4286-.5298-2.918-.5298c-1.61 0-5.4333.686-5.4333 4.0222 0 3.1388 4.4873 3.1778 4.4873 4.8266 0 1.6487-4.025 1.3532-5.3533.3136l-.4823 2.728s1.4486.6861 3.662.6861c2.214 0 5.554-1.1177 5.554-4.1597 0-3.159-4.5277-3.4531-4.5277-4.8265.0003-1.3738 3.16-1.1973 4.5483-.4515z"
													fill="#2566AF" fill-rule="nonzero"></path>
												<path
													d="M8 7L6.881 2.0009S6.7455 1 5.3032 1H.0615L0 1.1882s2.5194.4544 4.936 2.1567C7.2464 4.972 8 7 8 7z"
													fill="#E6A540" fill-rule="nonzero"></path>
												<path d="M-4-6h47v26H-4z"></path>
											</g>
										</svg>
									</div>
									<div class="mx-2" style="height: 28px;width: 28px;">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.2 11.7"
											enable-background="new 0 0 19.2 11.7" class="_6pH7Dd _1d8UT3">
											<path fill-rule="evenodd" clip-rule="evenodd" fill="#ff5f00"
												d="M6.6 10.4h5.7V1.2H6.6z"></path>
											<path
												d="M7.2 5.8c0-1.8.8-3.4 2.2-4.5-2.5-2-6.2-1.6-8.2 1-2 2.5-1.6 6.2 1 8.2 2.1 1.7 5.1 1.7 7.2 0C8 9.3 7.2 7.6 7.2 5.8"
												fill-rule="evenodd" clip-rule="evenodd" fill="#eb001b"></path>
											<path
												d="M19.2 10.2v-1h-.1l-.1.7-.1-.7h-.1v1h.1v-.7l.1.7.1-.7.1.7zm-.7 0v-.8h.2v-.2h-.4v.2h.2v.8z"
												fill-rule="evenodd" clip-rule="evenodd" fill="#f79e1b"></path>
											<defs>
												<filter id="a" filterUnits="userSpaceOnUse" x="9.4" y="0" width="9.4"
													height="11.7">
													<feColorMatrix values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0">
													</feColorMatrix>
												</filter>
											</defs>
											<mask maskUnits="userSpaceOnUse" x="9.4" y="0" width="9.4" height="11.7"
												id="b">
												<path fill-rule="evenodd" clip-rule="evenodd" fill="#fff"
													d="M9.4 0h9.5v11.7H9.4z" filter="url(#a)"></path>
											</mask>
											<path
												d="M18.9 5.8c0 3.2-2.6 5.8-5.8 5.8-1.3 0-2.6-.4-3.6-1.2 2.5-2 3-5.7 1-8.2-.3-.4-.6-.7-1-1 2.5-2 6.2-1.6 8.2 1 .8 1.1 1.2 2.3 1.2 3.6z"
												mask="url(#b)" fill-rule="evenodd" clip-rule="evenodd" fill="#f79e1b">
											</path>
										</svg>
									</div>
								</div>
								<div class="d-flex flex-wrap justify-content-center my-3">
									<button class="m-2 w-45 btn btn-warning" data-toggle="modal"
										data-target="#payByTextModal">
										Pay by Text
									</button>
									<button class="m-2 w-45 btn btn-primary">
										Cash
									</button>
									<button class="m-2 w-45 btn btn-primary">
										Other
									</button>
									<button class="m-2 w-45 btn btn-primary" data-toggle="modal" data-target="#voucherPay">
										Voucher
									</button>
								</div>

							</div>
							<div class="view-appoinment-footer border-top w-100 py-6">
								<div class="buttons d-flex justify-content-between">
									<div class="btn-group dropup w-100 mx-4">
										<button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											More Option
										</button>
										<div class="dropdown-menu dropdown-menu-lg dropdown-menu-center">
											<ul class="text-center navi flex-column navi-hover py-2">
												<li class="navi-item">
													<a href="#" class="navi-link">
														<span class="navi-text">Save Unpaid</span>
													</a>
												</li>
												<li class="navi-item">
													<a href="#" class="navi-link">
														<span class="navi-text" data-toggle="modal"
															data-target="#invoiceDetailModal">Invoice Details</span>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div id="sidebar" class="bg-white">
							<div class="card-body p-1">
								<div class="total-appoinment-data justify-content-around d-flex">
									<div class="text-center w-100 data pt-1 p-42">
										<h3 class="price font-weight-bolder text-center text-dark">4</h3>
										<p class="title text-muted">Total Booking</p>
									</div>
									<div class=" text-center w-100 data pt-1 p-2">
										<h3 class="price font-weight-bolder text-center text-dark">CA $30</h3>
										<p class="title text-muted">Total Sales</p>
									</div>
								</div>
								<ul class="nav nav-tabs nav-tabs-line nav-tabs-primary nav-tabs-line-2x" role="tablist">
									<li class="nav-item">
										<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link active"
											data-toggle="tab" href="#appointments">Appointments
											(2)</a>
									</li>
									<li class="nav-item">
										<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link"
											data-toggle="tab" href="#consultationforms">Consultation
											forms (0)</a>
									</li>
									<li class="nav-item">
										<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link"
											data-toggle="tab" href="#products">Products (0)</a>
									</li>
									<li class="nav-item">
										<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link"
											data-toggle="tab" href="#invoices">Invoices (2)</a>
									</li>
								</ul>
								<div class="tab-content mt-5" id="myTabContent">
									<div class="tab-pane fade show active" id="appointments" role="tabpanel"
										aria-labelledby="appointments">
										<div class="row">
											<div class="card-body py-2 px-8">
												<div class="client-apoinments-list mb-6">
													<div class="d-flex align-items-center flex-grow-1">
														<h6 class="font-weight-bolder text-dark">
															04 Jan</h6>
														<div
															class="d-flex flex-wrap align-items-center justify-content-between w-100">
															<div class="d-flex flex-column align-items-cente py-2 w-75">
																<h6 class="text-muted font-weight-bold">
																	Tue
																	10:00am <a class="text-blue" href="#">New</a>
																</h6>
																<a href="#"
																	class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg mb-1">Hair
																	Cuts</a>
																<span class="text-muted font-weight-bold">1h
																	15min with <i class="fa fa-heart text-danger"></i>
																	Adam
																</span>
															</div>
															<h6 class="font-weight-bolder py-4">
																CA
																$30</h6>
														</div>
													</div>
												</div>
												<div class="client-apoinments-list mb-6">
													<div class="d-flex align-items-center flex-grow-1">
														<h6 class="font-weight-bolder text-dark">
															04 Jan</h6>
														<div
															class="d-flex flex-wrap align-items-center justify-content-between w-100">
															<div class="d-flex flex-column align-items-cente py-2 w-75">
																<h6 class="text-muted font-weight-bold">
																	Tue
																	10:00am <a class="text-blue" href="#">New</a>
																</h6>
																<a href="#"
																	class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg mb-1">Hair
																	Cuts</a>
																<span class="text-muted font-weight-bold">1h
																	15min with <i class="fa fa-heart text-danger"></i>
																	Adam
																</span>
															</div>
															<h6 class="font-weight-bolder py-4">
																CA
																$30</h6>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="consultationforms" role="tabpanel"
										aria-labelledby="consultationforms">
										<h3>No consultation forms</h3>
										<p>Create your first consultation form</p>

									</div>
									<div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products">
										<h3>No product</h3>
										<p>Create your first Product</p>

									</div>
									<div class="tab-pane fade" id="invoices" role="tabpanel" aria-labelledby="invoices">
										<div class="col-12 col-md-12">
											<table class="table table-hover">
												<thead>
													<tr>
														<th>Status</th>
														<th>Invoice#</th>
														<th>Invoice date</th>
														<th>Total</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<span
																class="badge badge-pill badge-success">Completed</span>
														</td>
														<td><a class="text-blue" href="invoice_view.html">4</a>
														</td>
														<td>2 Jan 2021</td>
														<td>CA $3</td>
													</tr>
													<tr>
														<td>
															<span class="badge badge-pill badge-danger">Cancelled</span>
														</td>
														<td><a class="text-blue" href="invoice_view.html">5</a>
														</td>
														<td>5 Jan 2021</td>
														<td>CA $1</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="payByTextModal" tabindex="-1" role="dialog" aria-labelledby="payByTextModal"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
				<div class="modal-content">
					<div class="modal-body p-0 m-0">
						<button type="button" class="position-absolute close m-auto p-6 right-0 z-index"
							data-dismiss="modal" aria-label="Close" style="z-index: 99;font-size: 30px;">
							<span aria-hidden="true">&times;</span>
						</button>
						<div class="container p-0 m-0">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="p-10">
										<h1 class="font-weight-bolder my-18">
											Enable card payments for online booking and say goodbye to no shows!
										</h1>
										<h5 class="text-secondary my-18">
											Setup Fresha Pay now to enable in-app payment processing, take back control
											of your calendar by charging no show and
											late cancellation fees to client cards
										</h5>
										<h5 class="text-secondary my-18">There are <span
												class="font-weight-bolder"><u>no
													additional
													fees</u></span> to use
											integrated
											payment processing features, it's already included with ScheduleDown Plus.
										</h5>
										<div class="d-flex my-4">
											<button class="btn btn-primary mr-8">Setup ScheduleDown Pay</button>
											<a class="btn btn-white">Learn More</a>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="learnMoreModalBackImg">
										<div class="owl-carousel">
											<div class="">
												<div class="d-flex">
													<img alt="phone" src="{{ asset('/assets/images/phone.png') }}" />
													<img alt="phone" class="position-absolute"
														src="{{ asset('/assets/images/chat.png') }}" />
												</div>
												<div class="px-10">
													<h3 class="text-center mb-10 pt-0 px-20"><span
															class="font-weight-bolder">Protect
															from no
															shows</span> and late
														cancellations by charging client cards
													</h3>
												</div>
											</div>
											<div class="">
												<div class="d-flex">
													<img alt="phone" src="{{ asset('/assets/images/visa-phone.png') }}" />
													<img alt="phone" class="position-absolute"
														src="{{ asset('/assets/images/visa.png') }}" />
												</div>
												<div class="px-10">
													<h3 class="text-center mb-10 pt-0 px-20"><span
															class="font-weight-bolder">Integrated card processing</span>
														for
														easy and secure in-app payments
													</h3>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="voucherPay">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header d-flex justify-content-between">
						<h3 class="font-weight-bolder modal-title">Redeem Voucher</h3>
						<button type="button" class="text-dark close" data-dismiss="modal"><i
								class="ki ki-close"></i></button>
					</div>
					<!-- Modal body -->
					<div class="modal-body">
						<div class="form-group">
							<div class="input-icon input-icon-right">
								<input type="text" class="rounded-0 form-control" placeholder="Enter voucher code">
								<span>
									<i class="flaticon2-search-1 icon-md"></i>
								</span>
							</div>
						</div>
						<div class="d-flex flex-column justify-content-center align-items-center my-20">
							<div style="height: 50px;width: 50px;">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60">
									<path
										d="M21.3 59.4c-1.3 0-2.6-.5-3.5-1.5L1.5 41.6c-1-1-1.5-2.2-1.5-3.5s.5-2.6 1.5-3.5L28.6 7.3c.2-.2.5-.3.7-.3H51c.6 0 1 .4 1 1v22.1c0 .3-.1.5-.3.7L24.9 57.9c-1 1-2.3 1.5-3.6 1.5zM29.8 9L2.9 35.9c-.6.6-.9 1.4-.9 2.2s.3 1.6.9 2.1l16.3 16.3c1.2 1.2 3.1 1.2 4.2 0L50 29.6V9H29.8z"
										class="st0"></path>
									<path
										d="M41.3 23.1c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-34 24c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l9-9c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-9 9c-.1.2-.4.3-.7.3z"
										class="st0"></path>
									<path
										d="M51.3 19.1c-.6 0-1-.4-1-1s.4-1 1-1c.5 0 1-.1 1.5-.3.5-.2 1.1 0 1.3.5.2.5 0 1.1-.5 1.3-.6.3-1.4.5-2.3.5zm-10 0c-.6 0-1-.4-1-1v-.9c0-.5.5-1 1-1 .6 0 1 .5 1 1v.9c0 .5-.4 1-1 1zm14.2-2.4c-.2 0-.4-.1-.6-.2-.4-.3-.5-1-.2-1.4.3-.4.7-1 .9-1.6.2-.5.8-.7 1.3-.4.5.2.7.8.4 1.3-.3.7-.7 1.3-1.1 1.9-.1.3-.4.4-.7.4zm-13.9-1.5h-.1c-.5-.1-.9-.6-.9-1.1.1-.7.2-1.4.3-2 .1-.5.6-.9 1.2-.8.5.1.9.6.8 1.2-.1.6-.2 1.3-.3 1.9-.1.4-.5.8-1 .8zm16.1-3h-.3c-.5-.2-.8-.7-.7-1.3.1-.4.3-.9.4-1.3 0-.2.1-.3.1-.5.1-.5.6-.9 1.2-.8.5.1.9.6.8 1.2 0 .2-.1.4-.1.7-.1.5-.3.9-.4 1.4-.2.3-.6.6-1 .6zm-15-1.9c-.1 0-.2 0-.3-.1-.5-.2-.8-.8-.6-1.3.2-.6.4-1.2.7-1.9.2-.5.8-.7 1.3-.5.5.2.7.8.5 1.3-.3.6-.5 1.2-.7 1.8-.1.4-.5.7-.9.7zm15.4-3c-.4 0-.8-.3-1-.7-.1-.6-.4-1.1-.7-1.6-.3-.5-.1-1.1.3-1.4.5-.3 1.1-.1 1.4.3.4.7.7 1.4.9 2.1.1.5-.2 1.1-.7 1.2-.1.1-.2.1-.2.1zM44.9 5.9c-.2 0-.4-.1-.6-.2-.5-.3-.6-.9-.2-1.4.4-.6.9-1.1 1.3-1.7.4-.4 1-.4 1.4-.1.4.4.4 1 .1 1.4-.4.5-.8.9-1.2 1.5-.1.3-.5.5-.8.5zm10.3-2.6c-.2 0-.4-.1-.6-.2-.5-.3-1-.6-1.5-.8-.5-.2-.8-.8-.6-1.3.2-.5.8-.8 1.3-.6.7.2 1.4.6 2 1 .5.3.6.9.3 1.4-.2.4-.6.5-.9.5zm-6.6-.8c-.4 0-.8-.2-.9-.6-.2-.5 0-1.1.5-1.3.7-.3 1.5-.5 2.2-.6.6-.1 1 .3 1.1.9.1.5-.3 1-.9 1.1-.6.1-1.1.2-1.6.4-.2.1-.3.1-.4.1zM21.3 53.1c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l9-9c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4l-9 9c-.1.2-.4.3-.7.3zm-8-16c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3zm6-2c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3zm7-1c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3zm6-2c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3zm7-1c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3z"
										class="st0"></path>
								</svg>
							</div>
							<h6 class="font-weight-bold my-3">
								Check an existing voucher status & balance
							</h6>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="invoiceDetailModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header d-flex justify-content-between">
						<h3 class="font-weight-bolder modal-title">Invoice Details</h3>
						<button type="button" class="text-dark close" data-dismiss="modal"><i
								class="ki ki-close"></i></button>
					</div>
					<!-- Modal body -->
					<div class="modal-body">
						<div class="form-group">
							<label class="font-weight-bolder">Payment received by</label>
							<select class="form-control" name="paymenyReceivedyBy">
								<option>Adam omar</option>
								<option>Wendy Smith</option>
							</select>
						</div>
						<div class="form-group">
							<label class="font-weight-bolder">Invoice notes</label>
							<textarea rows="3" class="form-control"></textarea>
						</div>
						<div class="d-flex flex-column justify-content-center align-items-center my-20">
							<div style="height: 50px;width: 50px;">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60">
									<path
										d="M21.3 59.4c-1.3 0-2.6-.5-3.5-1.5L1.5 41.6c-1-1-1.5-2.2-1.5-3.5s.5-2.6 1.5-3.5L28.6 7.3c.2-.2.5-.3.7-.3H51c.6 0 1 .4 1 1v22.1c0 .3-.1.5-.3.7L24.9 57.9c-1 1-2.3 1.5-3.6 1.5zM29.8 9L2.9 35.9c-.6.6-.9 1.4-.9 2.2s.3 1.6.9 2.1l16.3 16.3c1.2 1.2 3.1 1.2 4.2 0L50 29.6V9H29.8z"
										class="st0"></path>
									<path
										d="M41.3 23.1c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-34 24c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l9-9c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-9 9c-.1.2-.4.3-.7.3z"
										class="st0"></path>
									<path
										d="M51.3 19.1c-.6 0-1-.4-1-1s.4-1 1-1c.5 0 1-.1 1.5-.3.5-.2 1.1 0 1.3.5.2.5 0 1.1-.5 1.3-.6.3-1.4.5-2.3.5zm-10 0c-.6 0-1-.4-1-1v-.9c0-.5.5-1 1-1 .6 0 1 .5 1 1v.9c0 .5-.4 1-1 1zm14.2-2.4c-.2 0-.4-.1-.6-.2-.4-.3-.5-1-.2-1.4.3-.4.7-1 .9-1.6.2-.5.8-.7 1.3-.4.5.2.7.8.4 1.3-.3.7-.7 1.3-1.1 1.9-.1.3-.4.4-.7.4zm-13.9-1.5h-.1c-.5-.1-.9-.6-.9-1.1.1-.7.2-1.4.3-2 .1-.5.6-.9 1.2-.8.5.1.9.6.8 1.2-.1.6-.2 1.3-.3 1.9-.1.4-.5.8-1 .8zm16.1-3h-.3c-.5-.2-.8-.7-.7-1.3.1-.4.3-.9.4-1.3 0-.2.1-.3.1-.5.1-.5.6-.9 1.2-.8.5.1.9.6.8 1.2 0 .2-.1.4-.1.7-.1.5-.3.9-.4 1.4-.2.3-.6.6-1 .6zm-15-1.9c-.1 0-.2 0-.3-.1-.5-.2-.8-.8-.6-1.3.2-.6.4-1.2.7-1.9.2-.5.8-.7 1.3-.5.5.2.7.8.5 1.3-.3.6-.5 1.2-.7 1.8-.1.4-.5.7-.9.7zm15.4-3c-.4 0-.8-.3-1-.7-.1-.6-.4-1.1-.7-1.6-.3-.5-.1-1.1.3-1.4.5-.3 1.1-.1 1.4.3.4.7.7 1.4.9 2.1.1.5-.2 1.1-.7 1.2-.1.1-.2.1-.2.1zM44.9 5.9c-.2 0-.4-.1-.6-.2-.5-.3-.6-.9-.2-1.4.4-.6.9-1.1 1.3-1.7.4-.4 1-.4 1.4-.1.4.4.4 1 .1 1.4-.4.5-.8.9-1.2 1.5-.1.3-.5.5-.8.5zm10.3-2.6c-.2 0-.4-.1-.6-.2-.5-.3-1-.6-1.5-.8-.5-.2-.8-.8-.6-1.3.2-.5.8-.8 1.3-.6.7.2 1.4.6 2 1 .5.3.6.9.3 1.4-.2.4-.6.5-.9.5zm-6.6-.8c-.4 0-.8-.2-.9-.6-.2-.5 0-1.1.5-1.3.7-.3 1.5-.5 2.2-.6.6-.1 1 .3 1.1.9.1.5-.3 1-.9 1.1-.6.1-1.1.2-1.6.4-.2.1-.3.1-.4.1zM21.3 53.1c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l9-9c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4l-9 9c-.1.2-.4.3-.7.3zm-8-16c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3zm6-2c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3zm7-1c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3zm6-2c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3zm7-1c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3z"
										class="st0"></path>
								</svg>
							</div>
							<h6 class="font-weight-bold my-3">
								Check an existing voucher status & balance
							</h6>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="addTipModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header d-flex justify-content-between">
						<h3 class="font-weight-bolder modal-title">Invoice Details</h3>
						<button type="button" class="text-dark close" data-dismiss="modal"><i
								class="ki ki-close"></i></button>
					</div>
					<!-- Modal body -->
					<div class="modal-body">
						<div class="form-group d-flex align-items-center w-100">
							<div class="w-70">
								<label class="font-weight-bolder">Tip Amount (CA $ 3)</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text bg-white isPrice">
											CA $
										</span>
										<span class="input-group-text bg-white isPercent" style="display: none;">
											%
										</span>
									</div>
									<input class="form-control" id="discount-input" name="discount-type" value="10">
								</div>
							</div>
							<div class="ml-4 mt-6">
								<div class="tgl-radio-tabs">
									<input id="price" value="price" type="radio"
										class="form-check-input tgl-radio-tab-child" name="discount-type"
										onclick="getDiscountType()">
									<label for="price" class="radio-inline">CA $</label>

									<input id="percent" value="percent" checked="" type="radio"
										class="form-check-input tgl-radio-tab-child" name="discount-type"
										onclick="getDiscountType()">
									<label for="percent" class="radio-inline">%</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="font-weight-bolder">Staff Tipped</label>
							<select class="form-control" name="staffTipped">
								<option>Adam omar</option>
								<option>Wendy Smith</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="addItemToSale">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header d-flex justify-content-between">
						<a class="text-primary modal-back cursor-pointer" onclick="nextPrevModal(-1)"><i
								class="fa fa-chevron-left icon-lg text-primary"></i></a>
						<h3 class="font-weight-bolder modal-title">Select Item</h3>
						<button type="button" class="text-dark close" data-dismiss="modal"><i
								class="ki ki-close"></i></button>
					</div>
					<!-- Modal body -->
					<div class="modal-body">
						<div class="modal-tab">
							<div class="form-group">
								<div class="input-icon input-icon-right">
									<input type="text" class="rounded-0 form-control"
										placeholder="Scan barcode or search any item">
									<span>
										<i class="flaticon2-search-1 icon-md"></i>
									</span>
								</div>
							</div>
							<ul class="list-group">
								<li type="button" data-name="product" id="product" data-url="{{ route('findItems','product') }}"
									class="d-flex justify-content-between align-items-center list-group-item list-group-item-action text-primary itemsfind">
									Products<i class="fa fa-chevron-right"></i>
								</li>
								<li type="button"  id="service" data-url="{{ route('findItems','service') }}"
									class="d-flex justify-content-between align-items-center list-group-item list-group-item-action text-primary itemsfind">
									Services<i class="fa fa-chevron-right"></i>
								</li>
								<li type="button"  id="voucher" data-url="{{ route('findItems','voucher') }}"
									class="d-flex justify-content-between align-items-center list-group-item list-group-item-action text-primary itemsfind">
									Vouchers<i class="fa fa-chevron-right"></i>
								</li>
								<li type="button" id="plans" data-url="{{ route('findItems','plans') }}"
									class="d-flex justify-content-between align-items-center list-group-item list-group-item-action text-primary itemsfind">
									Paid plans<i class="fa fa-chevron-right"></i>
								</li>
							</ul>
						</div>
						<div class="modal-tab">
							<div class="form-group">
								<div class="input-icon input-icon-right">
									<input type="text" class="rounded-0 form-control"
										placeholder="Scan barcode or search any item">
									<span>
										<i class="flaticon2-search-1 icon-md"></i>
									</span>
								</div>
							</div>
							<ul class="list-group output">
								
							</ul>
						</div>
					</div>
					<!-- Modal footer -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="allServicesModal" tabindex="-1" role="dialog"
			aria-labelledby="allServicesModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog " role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="font-weight-bolder m-auto modal-title" id="allServicesModalLongTitle">Redeemable
							Services
						</h4>
						<h3 type="button" class="" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</h3>
					</div>
					<div class="modal-body">
						<div class="m-auto ">
							<div class="card-body">
								<h3 class="font-weight-bolder">Hair</h3>
								<div
									class="mt-4 border-bottom py-3 order-total d-flex align-items-center justify-content-between">
									<span>
										<p class="mb-0 text-dark font-size-lg font-weight-bolder">Hair Cuts</p>
										<p class="mb-0 text-muted font-size-lg">
											1h
										</p>
									</span>
									<h4>Ca $30</h4>
								</div>
								<div
									class="mt-4 border-bottom py-3 order-total d-flex align-items-center justify-content-between">
									<span>
										<p class="mb-0 text-dark font-size-lg font-weight-bolder">HairCut</p>
										<p class="mb-0 text-muted font-size-lg">
											1h 30min
										</p>
									</span>
									<h4>Ca $25</h4>
								</div>
								<h3 class="mt-4 mb-1 font-weight-bolder">Spa</h3>
								<div
									class="border-bottom py-3 order-total d-flex align-items-center justify-content-between">
									<span>
										<p class="mb-0 text-dark font-size-lg font-weight-bolder">Blow Dry</p>
										<p class="mb-0 text-muted font-size-lg">
											1h 30min
										</p>
									</span>
									<h4>Ca $25</h4>
								</div>
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
	</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
		<!-- Slider Carousel -->
		<script src="{{ asset('assets/js/owl.js') }}"></script>
		<script>
			$(document).ready(function () {
				$('.owl-carousel').owlCarousel({
					center: true,
					items: 1,
					dots: true,
					loop: false,
					margin: 10
				});
			});
			function getDiscountType() {
				var type = $('input[name="discount-type"]:checked').val();
				if (type === 'price') {
					$(".isPrice").show();
					$(".isPercent").hide();
				} else {
					$(".isPrice").hide();
					$(".isPercent").show();
				}
			}
		</script>

		<script type="text/javascript">
			$(document).ready(function () {
				var titleHeader = $('#title-header').outerHeight();
				var customerData = $('.customer-data').outerHeight();
				// var customerBottom = $('.customer-bottom').outerHeight();

				$('.customer-bottom').css('height', 'calc(100vh - ' + titleHeader + 'px - ' + customerData + 'px)');
				$('.content-height').css('height', 'calc(100vh - ' + titleHeader + 'px)');

				var contentHeight = $('.content-height').css('height');
				$('.side-overlay').css('height', contentHeight + 'px');

				$('#dismiss, .side-overlay').on('click', function () {
					$('#sidebar').removeClass('active');
					$('.side-overlay').removeClass('active');
				});

				$('#sidebarCollapse').on('click', function () {
					$('#sidebar').addClass('active');
					$('.side-overlay').addClass('active');
				});
			});
		</script>
		<script>
			var currentModalTab = 0; // Current tab is set to be the first tab (0)
			showModalTab(currentModalTab); // Display the current tab

			function showModalTab(n) {
				// This function will display the specified tab of the form...
				var modalTab = document.getElementsByClassName("modal-tab");

				modalTab[n].style.display = "block";
				if (n == (modalTab.length - 1)) {
					$(".modal-back").show();
				} else {
					$(".modal-back").hide();
				}
			}

			function nextPrevModal(n,name) {
				// This function will figure out which tab to display
				var modalTab = document.getElementsByClassName("modal-tab");
				// Hide the current tab:
				modalTab[currentModalTab].style.display = "none";
				// Increase or decrease the current tab by 1:
				currentModalTab = currentModalTab + n;
				// if you have reached the end of the tab
				// Otherwise, display the correct tab:
				showModalTab(currentModalTab);
			}
			$(document).on('click','.itemsfind', function(){
				url = $(this).data('url');
				var modalTab = document.getElementsByClassName("modal-tab");
				// Hide the current tab:
				
				modalTab[0].style.display = "none";
				currentModalTab = 1;
				showModalTab(currentModalTab);

				$.ajax({
					type: "get",
					url: url,
					success: function (data) {

						$('.output').empty();
						$('.output').append(data);
					},
					error: function(data) {
						var errors = data.responseJSON;
						var errorsHtml = '';
						$.each(errors.error, function(key, value) {
							errorsHtml += '<p>' + value[0] + '</p>';
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
		
						toastr.error((errorsHtml) ? errorsHtml : 'There is something wrong!');
						KTUtil.scrollTop();
					}
				});
			});

			

			$(document).on('click','.addNewItems', function(){
				url = $(this).data('url');
				id = $(this).data('id');
				name = $(this).data('name');

				var modalTab = document.getElementsByClassName("modal-tab");
				// Hide the current tab:
				
				{{--  modalTab[0].style.display = "none";
				currentModalTab = 1;
				showModalTab(currentModalTab);  --}}

				$.ajax({
					type: "POST",
					url: url,
					headers:{
								'X-CSRF-Token': '{{ csrf_token() }}',
							},
					data: {id:id,name:name },
					success: function (data) {

						$(".addcard").last().after(data);

						var sum = 0;
						$(".Tprice").each(function(){
							sum += +$(this).val();
						});
						$(".total").text(sum);
					},
					error: function(data) {
						var errors = data.responseJSON;
						var errorsHtml = '';
						$.each(errors.error, function(key, value) {
							errorsHtml += '<p>' + value[0] + '</p>';
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
		
						toastr.error((errorsHtml) ? errorsHtml : 'There is something wrong!');
						KTUtil.scrollTop();
					}
				});
			});
		</script>
@endsection
		