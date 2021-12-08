{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
@endsection

@section('content')

	<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
		<!--begin::Tabs-->
		@include('layouts.overviewNav')
		<!--end::Tabs-->
		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container">
				<!--begin::Sales-->
				<!--begin::Row-->
				<div class="row">
					<div class="billing-activity container">
						<!-- <div class="row my-4">
							<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
								<div class="card-custom card-stretch gutter-b">
									<div class="client-messages-header rounded"
										style="background: #cde4ff url(./assets/images/wallet.png) no-repeat right bottom;background-size: 25% auto;">
										<h2 class="text-dark font-weight-bolder mb-4">
											Collect card payments and receive funds in your Wallet
										</h2>
										<p class="font-size-lg">
											Your notifications are automatically sent to clients using smart
											delivery across email, text message and app
											notification to maximize reach.
										</p>
										<button class="btn btn-white">Learn More</button>
									</div>
								</div>
							</div>
						</div> -->
						<div class="row">
							<div class="col-md-8 col-12">
								<h4 class="font-weight-bolder my-2">Gym area balance</h4>
								<h2 class="font-weight-bolder" style="font-size: 48px;color: #da2346;">- CA
									$1.05
								</h2>
								<div class="card-body p-0 my-8 border rounded">
									<div class="d-flex align-items-center p-4">
										<div class="icon" style="width: 32px;height:32px;">
											<span class="">
												<svg xmlns="http://www.w3.org/2000/svg">
													<rect fill="#da2346" width="32" height="32" rx="16"
														class="svg-fill"></rect>
													<path
														d="M12.812 8.486c2.353 0 4.326.986 4.326 2.413v2.55c0 .148-.022.292-.062.43a7.42 7.42 0 012.112-.292c2.354 0 4.326.986 4.326 2.413v5.101c0 1.427-1.972 2.413-4.326 2.413-2.295 0-4.227-.937-4.322-2.306l-.004-.107.001-.413c-.62.18-1.322.276-2.051.276-2.296 0-4.228-.938-4.323-2.307l-.003-.106v-7.652l.006-.142c.125-1.35 2.044-2.271 4.32-2.271zm3.05 11.639v.976c0 .687 1.454 1.413 3.326 1.413 1.814 0 3.235-.682 3.322-1.348l.004-.065v-.976c-.8.527-2.002.839-3.326.839-1.324 0-2.527-.312-3.327-.84zm0-2.551v.977c0 .686 1.454 1.413 3.326 1.413 1.814 0 3.235-.682 3.322-1.349l.004-.064v-.976c-.8.526-2.002.838-3.326.838-1.324 0-2.527-.312-3.326-.839zm-6.378 0l.002.977c0 .686 1.453 1.413 3.326 1.413.754 0 1.468-.117 2.052-.326v-1.501a7.45 7.45 0 01-2.052.276c-1.325 0-2.528-.312-3.328-.84zm.001-2.55V16c0 .686 1.454 1.413 3.327 1.413.753 0 1.467-.117 2.051-.326V16l.006-.141a1.51 1.51 0 01.056-.29 7.39 7.39 0 01-2.113.293c-1.324 0-2.527-.311-3.327-.838zm9.703-.437c-1.872 0-3.326.727-3.326 1.413s1.454 1.413 3.326 1.413c1.873 0 3.326-.727 3.326-1.413s-1.453-1.413-3.326-1.413zm-3.05-2.114c-.799.527-2.002.839-3.326.839-1.324 0-2.528-.312-3.327-.84v.989l.007.07c.11.662 1.522 1.331 3.32 1.331 1.814 0 3.234-.682 3.321-1.348l.005-.065zm-3.326-2.987c-1.873 0-3.326.726-3.326 1.413 0 .686 1.453 1.413 3.326 1.413 1.872 0 3.326-.727 3.326-1.413 0-.687-1.454-1.413-3.326-1.413z"
														fill="#FFF" class="svg-no-fill"></path>
												</svg>
											</span>
										</div>
										<div>
											<h6 class="mx-3">
												Your <span class="font-weight-bolder">MasterCard</span> card
												ending <span class="font-weight-bolder">4736</span> will be
												charged <span class="font-weight-bolder">Friday, 30 Jul
													2021.</span>
											</h6>
										</div>
									</div>
								</div>
								<div class="my-4">
									<div class="my-2 d-flex flex-wrap justify-content-between">
										<h6 class="font-weight-bolder text-muted">3 Jul 2021</h6>
										<h6 class="">- CA $0.84</h6>
									</div>
									<div class="card shadow-sm rounded p-3">
										<ul class="list-group">
											<li data-toggle="modal" data-target="#detailModal"
												class="p-3 border-0 my-4 bg-hover-grey-o-1 cursor-pointer d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg"
														style="position: relative;background: #f2f2f7;border-radius: 100px;">
														<svg xmlns="http://www.w3.org/2000/svg"
															viewBox="0 0 64 64">
															<path
																d="M37 39.15v2.927H27V39.15h10zm0-8.444v2.927H27v-2.927h10zm0-8.4v2.927H27v-2.927h10z"
																fill-rule="evenodd"></path>
														</svg>
														<span class=""
															style="border:3px solid #FFF;position: absolute;background: #057cc7;height: 26px;bottom: 0px;width: 26px;left: 38px;border-radius: 100px;text-align: center;    align-items: center;">
															<svg style="width: 14px;height: 15px;"
																xmlns="http://www.w3.org/2000/svg">
																<path
																	d="M6.244 1.077a.833.833 0 011.179 0l4.333 4.334a.833.833 0 010 1.178l-4.333 4.333a.833.833 0 11-1.179-1.178l2.911-2.91H.834a.833.833 0 110-1.667h8.321l-2.91-2.911a.833.833 0 010-1.179z"
																	fill="#fff">
																</path>
															</svg>
														</span>
													</div>
													<div class="ml-4 name text-left">
														<h3 class="text-dark">Online booking fee</h3>
														<h6 class="m-0 text-muted font-weight-normal">
															Appointment D3C339E3
														</h6>
													</div>
												</div>
												<div>
													<h4 class="text-dark font-weight-bolder">- CA $0.21</h4>
													<h6 class="text-muted text-right">
														12:50am
													</h6>
												</div>
											</li>
											<li data-toggle="modal" data-target="#detailModal"
												class="p-3 border-0 my-4 bg-hover-grey-o-1 cursor-pointer d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg"
														style="position: relative;background: #f2f2f7;border-radius: 100px;">
														<svg xmlns="http://www.w3.org/2000/svg"
															viewBox="0 0 64 64">
															<path
																d="M37 39.15v2.927H27V39.15h10zm0-8.444v2.927H27v-2.927h10zm0-8.4v2.927H27v-2.927h10z"
																fill-rule="evenodd"></path>
														</svg>
														<span class=""
															style="border:3px solid #FFF;position: absolute;background: #057cc7;height: 26px;bottom: 0px;width: 26px;left: 38px;border-radius: 100px;text-align: center;    align-items: center;">
															<svg style="width: 14px;height: 15px;"
																xmlns="http://www.w3.org/2000/svg">
																<path
																	d="M6.244 1.077a.833.833 0 011.179 0l4.333 4.334a.833.833 0 010 1.178l-4.333 4.333a.833.833 0 11-1.179-1.178l2.911-2.91H.834a.833.833 0 110-1.667h8.321l-2.91-2.911a.833.833 0 010-1.179z"
																	fill="#fff">
																</path>
															</svg>
														</span>
													</div>
													<div class="ml-4 name text-left">
														<h3 class="text-dark">Online booking fee</h3>
														<h6 class="m-0 text-muted font-weight-normal">
															Appointment D3C339E3
														</h6>
													</div>
												</div>
												<div>
													<h4 class="text-dark font-weight-bolder">- CA $0.21</h4>
													<h6 class="text-muted text-right">
														12:50am
													</h6>
												</div>
											</li>
										</ul>
									</div>
								</div>

								<div class="my-4">
									<div class="my-2 d-flex flex-wrap justify-content-between">
										<h6 class="font-weight-bolder text-muted">3 Jul 2021</h6>
										<h6 class="">- CA $0.84</h6>
									</div>
									<div class="card shadow-sm rounded p-3">
										<ul class="list-group">
											<li data-toggle="modal" data-target="#detailModal"
												class="p-3 border-0 my-4 bg-hover-grey-o-1 cursor-pointer d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg"
														style="position: relative;background: #f2f2f7;border-radius: 100px;">
														<svg xmlns="http://www.w3.org/2000/svg"
															viewBox="0 0 64 64">
															<path
																d="M37 39.15v2.927H27V39.15h10zm0-8.444v2.927H27v-2.927h10zm0-8.4v2.927H27v-2.927h10z"
																fill-rule="evenodd"></path>
														</svg>
														<span class=""
															style="border:3px solid #FFF;position: absolute;background: #057cc7;height: 26px;bottom: 0px;width: 26px;left: 38px;border-radius: 100px;text-align: center;    align-items: center;">
															<svg style="width: 14px;height: 15px;"
																xmlns="http://www.w3.org/2000/svg">
																<path
																	d="M6.244 1.077a.833.833 0 011.179 0l4.333 4.334a.833.833 0 010 1.178l-4.333 4.333a.833.833 0 11-1.179-1.178l2.911-2.91H.834a.833.833 0 110-1.667h8.321l-2.91-2.911a.833.833 0 010-1.179z"
																	fill="#fff">
																</path>
															</svg>
														</span>
													</div>
													<div class="ml-4 name text-left">
														<h3 class="text-dark">Online booking fee</h3>
														<h6 class="m-0 text-muted font-weight-normal">
															Appointment D3C339E3
														</h6>
													</div>
												</div>
												<div>
													<h4 class="text-dark font-weight-bolder">- CA $0.21</h4>
													<h6 class="text-muted text-right">
														12:50am
													</h6>
												</div>
											</li>
											<li data-toggle="modal" data-target="#detailModal"
												class="p-3 border-0 my-4 bg-hover-grey-o-1 cursor-pointer d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg"
														style="position: relative;background: #f2f2f7;border-radius: 100px;">
														<svg xmlns="http://www.w3.org/2000/svg"
															viewBox="0 0 64 64">
															<g transform="translate(18 23)">
																<ellipse cx="8.615" cy="8.637" rx="8.615"
																	ry="8.637" fill="red"></ellipse>
																<ellipse cx="8.615" cy="8.637" rx="8.615"
																	ry="8.637" transform="translate(10.769)"
																	fill="#ff9a00"></ellipse>
																<path
																	d="M14 1.87a8.645 8.645 0 000 13.536A8.645 8.645 0 0014 1.87z"
																	fill="#ff5000"></path>
															</g>
														</svg>
														<span class=""
															style="border:3px solid #FFF;position: absolute;background: #00a36d;height: 26px;bottom: 0px;width: 26px;left: 38px;border-radius: 100px;text-align: center;    align-items: center;">
															<svg style="width: 14px;height: 15px;"
																xmlns="http://www.w3.org/2000/svg">
																<path
																	d="M5.756 1.077a.833.833 0 00-1.179 0L.244 5.411a.833.833 0 000 1.178l4.333 4.333a.833.833 0 101.179-1.178l-2.911-2.91h8.322a.833.833 0 100-1.667H2.845l2.91-2.911a.833.833 0 000-1.179z"
																	fill="#fff"></path>
															</svg>
														</span>
													</div>
													<div class="ml-4 name text-left">
														<h3 class="text-dark">Wallet top-up</h3>
														<h6 class="m-0 text-muted font-weight-normal">
															Mastercard •••• 4736
														</h6>
													</div>
												</div>
												<div>
													<h4 class="text-dark font-weight-bolder">CA $31.21</h4>
													<h6 class="text-muted text-right">
														02:18am
													</h6>
												</div>
											</li>
										</ul>
									</div>
								</div>

								<div class="my-4">
									<div class="my-2 d-flex flex-wrap justify-content-between">
										<h6 class="font-weight-bolder text-muted">27 Jul 2021</h6>
										<h6 class="">- CA $0.0</h6>
									</div>
									<div class="card shadow-sm rounded p-3">
										<ul class="list-group">
											<li data-toggle="modal" data-target="#detailModal"
												class="p-3 border-0 my-4 bg-hover-grey-o-1 cursor-pointer d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg"
														style="position: relative;background: #f2f2f7;border-radius: 100px;">
														<svg xmlns="http://www.w3.org/2000/svg"
															viewBox="0 0 65 65">
															<path
																d="M27.552 20.319a.75.75 0 01.101 1.493l-.101.007h-1.655c-.907 0-1.651.696-1.727 1.583l-.006.15v18.207c0 .906.696 1.65 1.583 1.726l.15.006h10.758c.907 0 1.65-.696 1.727-1.583l.006-.15V34.31a.75.75 0 011.493-.101l.007.101v7.449a3.233 3.233 0 01-3.05 3.227l-.183.005H25.897a3.233 3.233 0 01-3.228-3.049l-.005-.183V23.552a3.233 3.233 0 013.05-3.228l.183-.005h1.655zM31 39a1 1 0 110 2 1 1 0 010-2zm13.931-20.336a2.405 2.405 0 012.405 2.405v8.276a2.405 2.405 0 01-2.405 2.405h-9.724l-3.96 2.376a.75.75 0 01-1.128-.543l-.007-.1V21.069a2.405 2.405 0 012.405-2.405zm0 1.5H32.517c-.5 0-.905.405-.905.905v11.088l3.002-1.8a.75.75 0 01.285-.1L35 30.25h9.931c.5 0 .905-.405.905-.905v-8.276c0-.5-.405-.905-.905-.905zm-6.62 5.948a.75.75 0 01.101 1.493l-.102.007H35a.75.75 0 01-.102-1.493l.102-.007h3.31zm4.137-3.31a.75.75 0 01.102 1.493l-.102.007H35a.75.75 0 01-.102-1.493l.102-.007h7.448z"
																fill-rule="nonzero"></path>
														</svg>
														<span class=""
															style="border:3px solid #FFF;position: absolute;background: #057cc7;height: 26px;bottom: 0px;width: 26px;left: 38px;border-radius: 100px;text-align: center;    align-items: center;">
															<svg style="width: 14px;height: 15px;"
																xmlns="http://www.w3.org/2000/svg">
																<path
																	d="M6.244 1.077a.833.833 0 011.179 0l4.333 4.334a.833.833 0 010 1.178l-4.333 4.333a.833.833 0 11-1.179-1.178l2.911-2.91H.834a.833.833 0 110-1.667h8.321l-2.91-2.911a.833.833 0 010-1.179z"
																	fill="#fff">
																</path>
															</svg>
														</span>
													</div>
													<div class="ml-4 name text-left">
														<h3 class="text-dark">Blast text message</h3>
														<h6 class="m-0 text-muted font-weight-normal">
															1 sent
														</h6>
													</div>
												</div>
												<div>
													<h4 class="text-dark font-weight-bolder">- CA $0.5</h4>
													<h6 class="text-muted text-right">
														08:21pm
													</h6>
												</div>
											</li>
											<li data-toggle="modal" data-target="#detailModal"
												class="p-3 border-0 my-4 bg-hover-grey-o-1 cursor-pointer d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg"
														style="position: relative;background: #f2f2f7;border-radius: 100px;">
														<svg xmlns="http://www.w3.org/2000/svg"
															viewBox="0 0 64 64">
															<g transform="translate(18 23)">
																<ellipse cx="8.615" cy="8.637" rx="8.615"
																	ry="8.637" fill="red"></ellipse>
																<ellipse cx="8.615" cy="8.637" rx="8.615"
																	ry="8.637" transform="translate(10.769)"
																	fill="#ff9a00"></ellipse>
																<path
																	d="M14 1.87a8.645 8.645 0 000 13.536A8.645 8.645 0 0014 1.87z"
																	fill="#ff5000">
																</path>
															</g>
														</svg>
														<span class=""
															style="border:3px solid #FFF;position: absolute;background: #00a36d;height: 26px;bottom: 0px;width: 26px;left: 38px;border-radius: 100px;text-align: center;    align-items: center;">
															<svg style="width: 14px;height: 15px;"
																xmlns="http://www.w3.org/2000/svg">
																<path
																	d="M5.756 1.077a.833.833 0 00-1.179 0L.244 5.411a.833.833 0 000 1.178l4.333 4.333a.833.833 0 101.179-1.178l-2.911-2.91h8.322a.833.833 0 100-1.667H2.845l2.91-2.911a.833.833 0 000-1.179z"
																	fill="#fff"></path>
															</svg>
														</span>
													</div>
													<div class="ml-4 name text-left">
														<h3 class="text-dark">Wallet top-up</h3>
														<h6 class="m-0 text-muted font-weight-normal">
															Mastercard •••• 4736
														</h6>
													</div>
												</div>
												<div>
													<h4 class="text-dark font-weight-bolder">CA $31.21</h4>
													<h6 class="text-muted text-right">
														02:18am
													</h6>
												</div>
											</li>
										</ul>
									</div>
								</div>

								<div class="text-center ">
									<a href="overview_wallet_history.html">
										<h3 style="color: #0c79bf;text-decoration: underline;"> View all
											history</h3>
									</a>
								</div>
							</div>
							<div class=" col-md-4 col-12">
								<div class="card shadow-sm" style="position: sticky;top: 140px;">
									<div class="card-body py-6 px-8">
										<div class="d-flex justify-content-between">
											<div>
												<h4 class="font-weight-bolder">Gym area</h4>
												<p class="text-muted">2 locations</p>
											</div>
											<span
												style="width: 56px;height: 56px;background-color: #cde4ff;display: flex;color: #037aff;position: relative;align-items: center;justify-content: center;border-radius: 16px;">
												<svg fill="#037aff"
													style="width: 24px;height:24px;position:absolute;"
													xmlns="http://www.w3.org/2000/svg">
													<path
														d="M9.75 20.75V15.5a.75.75 0 0 1 .75-.75h4a.75.75 0 0 1 .75.75v5.25h4.5V12.5a.75.75 0 1 1 1.5 0v9a.75.75 0 0 1-.75.75h-16a.75.75 0 0 1-.75-.75v-9a.75.75 0 1 1 1.5 0v8.25h4.5zm1.5 0h2.5v-4.5h-2.5v4.5zm-1.416-10.5a3.727 3.727 0 0 1-2.669-1.124A3.743 3.743 0 0 1 .75 6.498a.75.75 0 0 1 .107-.384l3-5A.75.75 0 0 1 4.5.75h16a.75.75 0 0 1 .643.364l3 5a.75.75 0 0 1 .107.384 3.742 3.742 0 0 1-6.415 2.628 3.727 3.727 0 0 1-5.335.001 3.727 3.727 0 0 1-2.666 1.123zm-4.91-8L2.259 6.695a2.242 2.242 0 0 0 4.238.816.75.75 0 0 1 1.343.003 2.227 2.227 0 0 0 3.99 0 .75.75 0 0 1 1.343 0 2.227 2.227 0 0 0 3.99 0 .75.75 0 0 1 1.342-.003 2.242 2.242 0 0 0 4.238-.816L20.075 2.25H4.925z">
													</path>
												</svg>
											</span>
										</div>
									</div>
									<hr class="m-0" />
									<div>
										<ul class="list-group border-0 p-4">
											<li class="list-group-item border-0">
												<h4>
													<a href="overview_wallet_billing_detail.html"
														class="text-blue text-underline">
														Billing Detail
													</a>
												</h4>
												<h6 class="text-muted">Jayesh Gohel</h6>
											</li>
											<li class="list-group-item border-0">
												<h4>
													<a href="overview_wallet_paymentmethod.html"
														class="text-blue text-underline">
														Payment Method
													</a>
												</h4>
												<h6 class="text-muted"><span class=""
														style="width: 20px;height: 16px;"><svg
															style="width: 20px;height: 16px;"
															xmlns="http://www.w3.org/2000/svg">
															<path fill-rule="evenodd" clip-rule="evenodd"
																fill="#ff5f00" d="M6.6 10.4h5.7V1.2H6.6z">
															</path>
															<path
																d="M7.2 5.8c0-1.8.8-3.4 2.2-4.5-2.5-2-6.2-1.6-8.2 1-2 2.5-1.6 6.2 1 8.2 2.1 1.7 5.1 1.7 7.2 0C8 9.3 7.2 7.6 7.2 5.8"
																fill-rule="evenodd" clip-rule="evenodd"
																fill="#eb001b"></path>
															<path
																d="M19.2 10.2v-1h-.1l-.1.7-.1-.7h-.1v1h.1v-.7l.1.7.1-.7.1.7zm-.7 0v-.8h.2v-.2h-.4v.2h.2v.8z"
																fill-rule="evenodd" clip-rule="evenodd"
																fill="#f79e1b"></path>

															<path
																d="M18.9 5.8c0 3.2-2.6 5.8-5.8 5.8-1.3 0-2.6-.4-3.6-1.2 2.5-2 3-5.7 1-8.2-.3-.4-.6-.7-1-1 2.5-2 6.2-1.6 8.2 1 .8 1.1 1.2 2.3 1.2 3.6z"
																mask="url(#b)" fill-rule="evenodd"
																clip-rule="evenodd" fill="#f79e1b"></path>
														</svg></span>
													•••• 4736</h6>
											</li>
											<li class="list-group-item border-0">
												<h4>
													<a data-toggle="modal"
														data-target="#addBankAccountModal"
														class="text-blue text-underline cursor-pointer">
														Add bank account
													</a>
												</h4>
											</li>
											<li class="list-group-item border-0">
												<h4>
													<a href="overview_wallet_statements.html"
														class="text-blue text-underline">
														Monthly statements and fees
													</a>
												</h4>
											</li>
											<li class="list-group-item border-0">
												<h4>
													<a href="overview_wallet_history.html"
														class="text-blue text-underline">
														Wallet history
													</a>
												</h4>
											</li>
											<li class="list-group-item border-0">
												<h4>
													<a data-target="#pricingModal" data-toggle="modal"
														href="#" class="text-blue text-underline">
														ScheduleDown Pricing
													</a>
												</h4>
											</li>
										</ul>
									</div>
									<hr class="m-0" />
									<div class="card-body px-8 py-6">
										<h6 class="text-muted">To learn more about how to use your Wallet <a
												href="#" class="text-blue">click here.</a></h6>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
				<!--end::Row-->
				<!--end::Sales-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
	</div>
	
@endsection
{{-- Scripts Section --}}
@section('scripts')
@endsection