{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
	
	
	<!--begin::Header-->
	
	<!--end::Header-->
	
	<!--begin::Content-->
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container">
				<!--begin::Dashboard-->
				<!--begin::Row-->
				<div class="row my-4">
					<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
						<!--begin::List Widget 3-->
						<div class="card-custom card-stretch gutter-b">
							<!--begin::Body-->
							<!--begin::Item-->
							<div class="">
								<div>
									<a class="text-blue" href="{{ route('setup') }}">
										<h4>Back to setup</h4>
									</a>
									<div class="d-flex justify-content-between flex-wrap">
										<div>
											<h2 class="font-weight-bolder">Taxes</h2>
											<h6 class="text-dark-50">
												Add your tax rates and use groups for multiple taxes, for
												example
												combining city and state taxes
											</h6>
										</div>
										<div>
											<div class="dropdown dropdown-inline mr-2">
												<button type="button"
													class="btn btn-lg btn-primary my-2 font-weight-bolder dropdown-toggle my-2"
													data-toggle="dropdown" aria-haspopup="true"
													aria-expanded="false">
													Add New
												</button>
												<div
													class="dropdown-menu dropdown-menu-sm text-center dropdown-menu-right">
													<ul class="navi flex-column navi-hover">
														<li class="navi-item">
															<a data-toggle="modal"
																data-target="#addNewTaxModal"
																class="navi-link">
																<span class="navi-text">New Tax</span>
															</a>
														</li>
														<li class="navi-item">
															<a href="#" class="navi-link" id="opengrouptax" >
																<span class="navi-text">New Tax group</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="mt-6 mb-2 row">
									<div class="col-12 my-4">
										<h3 class="font-weight-bolder">Tax Rates</h3>
										<table class="table table-hover" id="taxestable">
											<tbody id="sortable" class="sortable">
												
											</tbody>
										</table>
										@if(isset($tax) && count($tax)==0)
											<div class="card">
												<div class="text-center p-8 w-50 m-auto">
													<div class="mx-auto my-4" style="height: 50px;width:50px;">
														<svg viewBox="0 0 90 90"
															xmlns="http://www.w3.org/2000/svg">
															<g fill="none" fill-rule="evenodd">
																<circle cx="45" cy="45" r="45" fill="#EBF5FD">
																</circle>
																<path fill="#A4ADBA" fill-rule="nonzero"
																	d="M61.3636 79.7727V71.591h8.1819z"></path>
																<path fill="#FFF" fill-rule="nonzero"
																	d="M17.3864 7.159h52.159v61.3637H59.1137V78.75H17.3864V50.625z">
																</path>
																<g fill="#DCDDDE" fill-rule="nonzero">
																	<path
																		d="M54.8182 25.5682h7.5682v7.159h-7.5682zM39.6819 25.5682H47.25v7.159h-7.5682zM24.5455 25.5682h7.5682v7.159h-7.5682z">
																	</path>
																</g>
																<g fill="#0C3847" fill-rule="nonzero">
																	<path
																		d="M54.9716 34.7727h10.483v-9.2045h-10.483v9.2045zm2.6207-6.5746h5.2415v3.9448h-5.2415V28.198zM39.2472 25.5682v9.2045h10.483v-9.2045h-10.483zm7.8622 6.5747h-5.2415V28.198h5.2415v3.9448zM34.0057 25.5682h-10.483v9.2045h10.483v-9.2045zm-2.6208 6.5747h-5.2414V28.198h5.2414v3.9448z">
																	</path>
																</g>
																<g fill="#0C3847" fill-rule="nonzero">
																	<path
																		d="M23.5227 41.9318h37.841v2.63h-37.841zM23.5227 47.1915H50.642v2.63H23.5227zM23.5227 52.4513h33.4261v2.63h-33.426zM23.5227 57.711H48.75v2.63H23.5227z">
																	</path>
																</g>
																<path
																	d="M15.4983 51.1364v30.6818h45.97l11.1453-10.9764V5.1136H15.4983v46.0228zM62.229 77.4537v-5.8628h5.953l-5.953 5.8628zM18.0944 7.6705h51.923V69.034H59.633v10.2273H18.0944V7.6704z"
																	fill="#101928" fill-rule="nonzero"></path>
																<g transform="translate(54.2045 53.1818)">
																	<circle stroke="#101928" stroke-width="3"
																		fill="#DEE3E7" fill-rule="nonzero"
																		cx="16.3636" cy="16.3636" r="16.3636">
																	</circle>
																	<g transform="translate(8.1818 6.1364)">
																		<ellipse stroke="#101928"
																			stroke-width="2.5" cx="3.1361"
																			cy="4.3243" rx="2.3764" ry="3.5647">
																		</ellipse>
																		<rect fill="#101928"
																			transform="rotate(38 8.3517 10.0597)"
																			x="6.8176" y="-.6789" width="3.0682"
																			height="21.4773" rx="1.5341"></rect>
																		<ellipse stroke="#101928"
																			stroke-width="2.5" cx="12.6418"
																			cy="16.8006" rx="2.3764"
																			ry="3.5647">
																		</ellipse>
																	</g>
																</g>
															</g>
														</svg>
													</div>
													<h6>Add your tax rates and use groups for multiple taxes,
														for example combining city and state taxes</h6>
													<button class="btn btn-primary" data-toggle="modal"
														data-target="#addNewTaxModal">Add Tax</button>
												</div>
											</div>
										@endif
									</div>
									<div class="col-12 my-6">
										<div class="d-flex my-3 justify-content-between flex-wrap">
											<div>
												<h3 class="font-weight-bolder">Tax Defaults</h3>
												<h6 class="text-dakr-50">
													Setup the default taxes for your business, you can still
													override defaults in the settings of individual products
													and
													services
												</h6>
											</div>
											<!-- <button class="btn btn-md btn-white font-weight-bolder">Apply
												defaults</button> -->
										</div>
										<table class="table table-hover" id="loctable">
											<tbody id="sortable" class="sortable">
												
											</tbody>
										</table>
										<div class="card shadow-sm mt-8" style="background-color: #e8f6ff;">
											<div class="d-flex align-items-center flex-wrap p-4">
												<span style="height: 35px;width: 35px;">
													<svg xmlns="http://www.w3.org/2000/svg"
														viewBox="0 0 30 30">
														<g fill="none" fill-rule="evenodd" stroke="#2B72BD"
															stroke-linecap="round" stroke-linejoin="round">
															<path fill="#FFF" stroke-width="2"
																d="M22.78 7.22A11 11 0 1 1 7.23 22.78 11 11 0 0 1 22.78 7.22z">
															</path>
															<path fill="#FFF" stroke-width="4"
																d="M24.2 5.8A13 13 0 1 1 5.8 24.2 13 13 0 0 1 24.2 5.8zm0 0z"
																opacity=".32">
															</path>
															<g stroke-width="2">
																<path
																	d="M15.04 14.15v4.16M15.04 14.15H14M15.38 10.7a.7.7 0 1 1-1.38 0 .7.7 0 0 1 1.38 0zM16.41 18.3h-1.37">
																</path>
															</g>
														</g>
													</svg>
												</span>
												<div class="ml-4">
													<h3>
														Tax Calculation
													</h3>
													<h6>Your retail prices are including taxes. <span
															class="text-blue cursor-pointer"
															data-toggle="modal"
															data-target="#taxCalculationModal">Change this
															setting</span>
													</h6>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--end:Item-->
							<!--end::Body-->
						</div>
						<!--end::List Widget 3-->
					</div>
				</div>
				<!--end::Row-->
				<!--end::Dashboard-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
	</div>
	<!--end::Content-->

	<!-- Modal -->
	<div class="modal fade" id="addNewTaxModal" tabindex="-1" role="dialog" aria-labelledby="addNewTaxModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addNewTaxModalLabel">New Tax</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<form name="savenewtax" id="savenewtax" action="{{ route('saveTaxes') }}" >
					@csrf
					<div class="modal-body">
						<h5 class="text-dakr-50">Set the tax name and percentage rate. To apply this to your products and
							services, adjust your tax defaults settings.
						</h5>
						<div class="mt-3 d-flex flex-wrap justify-content-between">
							<div class="form-group">
								<label class="font-weight-bolder">Tax name</label>
								<input type="text" name="tax_name" class="form-control" placeholder="">
							</div>
							<div class="form-group">
								<label class="font-weight-bolder">Tax rate</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text bg-white"><i class="fa fa-percent"></i></span>
									</div>
									<input type="text" name="tax_rates" class="form-control">
								</div>
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="savetax">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- end -->


	<!-- group add tax Modal -->
	<div class="modal fade" id="addNewGroupTaxModal" tabindex="-1" role="dialog" aria-labelledby="addNewGroupTaxModal"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addNewGroupTaxModalLabel">New Tax Group</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<form name="savegrouptax" id="savegrouptax" action="{{ route('saveGroupTax') }}">
					@csrf
					<div class="modal-body">
						<h5 class="text-dakr-50">Combine multiple taxes into a group, each tax still shows individually on invoices and reports. To apply this group to your products and services, adjust your tax default settings.
						</h5>
						<div class="mt-3 d-flex flex-wrap justify-content-between">
							<div class="form-group col-md-12">
								<label class="font-weight-bolder">Tax name</label>
								<input type="text" name="tax_name" class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-12" >
								<div id="append">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="savenewgrouptax">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- end -->

	<!-- group edit tax Modal -->
	<div class="modal fade" id="editNewGroupTaxModal" tabindex="-1" role="dialog" aria-labelledby="editNewGroupTaxModal"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editNewGroupTaxModalLabel">New Tax Group</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<form name="updategrouptax" id="updategrouptax" action="{{ route('updateGroupTax') }}">
					@csrf
					<input type="hidden" name="id" id="upgroupid">
					<div class="modal-body">
						<h5 class="text-dakr-50">Combine multiple taxes into a group, each tax still shows individually on invoices and reports. To apply this group to your products and services, adjust your tax default settings.
						</h5>
						<div class="mt-3 d-flex flex-wrap justify-content-between">
							<div class="form-group col-md-12">
								<label class="font-weight-bolder">Tax name</label>
								<input type="text" name="tax_name" id="uptaxname" class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-12" >
								<div id="upappend">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="updatenewgrouptax">Update</button>
						<button type="button" class="btn btn-danger" data-id="" id="deletetax">Delete</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- end -->

	<!--edit Modal -->
	<div class="modal fade" id="editNewTaxModal" tabindex="-1" role="dialog" aria-labelledby="editNewTaxModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editNewTaxModal">Update Tax</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<form name="updatenewtax" id="updatenewtax" action="{{ route('updatetax') }}" >
					@csrf
					<div class="modal-body">
						<h5 class="text-dakr-50">Set the tax name and percentage rate. To apply this to your products and
							services, adjust your tax defaults settings.
						</h5>
						<div class="mt-3 d-flex flex-wrap justify-content-between">
							<div class="form-group">
								<label class="font-weight-bolder">Tax name</label>
								<input type="hidden" name="id" id="updateid" >
								<input type="text" name="tax_name" id="updatetaxname" class="form-control" placeholder="">
							</div>
							<div class="form-group">
								<label class="font-weight-bolder">Tax rate</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text bg-white"><i class="fa fa-percent"></i></span>
									</div>
									<input type="text" name="tax_rates" id="updatetaxrate" class="form-control">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="updatetax">Update</button>
						<button type="button" class="btn btn-danger" data-id="" id="deletetax">Delete</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--begin::end-->

	<!--location update Modal -->
	<div class="modal fade" id="editlocTaxModal" tabindex="-1" role="dialog" aria-labelledby="editlocTaxModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" >Edit Tax Defaults</h5><br>
					<h6 class="modal-title" id="locname"></h6>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<h5 class="text-dakr-50">Once saved, changes will automatically apply to all products and services which are already assigned to default taxes
					</h5>
					<form name="updatloctax" id="updatloctax">
					<div class="mt-3 d-flex flex-wrap justify-content-between" style="border-bottom: 1px;">
						<div class="form-group col-12">
							<label class="font-weight-bolder">Products Default Tax</label>
							<input type="hidden" name="locid" id="updatelocid" >
							<select name="poducts_default_tax" id="producttax" class="form-control" >
								<option value="0">No tax</option>
								@foreach($tax as $value)
								@php
								if ($value->is_group=='1') {
									$expgames_ids = explode(',', $value['tax_rates']);
									$tax_arr = array();
									foreach($expgames_ids as $taxids){
				
										$tax = App\Models\Taxes::where('user_id',Auth::id())->where('id',$taxids)->where('is_deleted','0')->first();
										array_push($tax_arr, $tax['tax_rates']);
									}
				
								}
								@endphp
									<option value="{{ $value->id }}">{{ $value->tax_name }} @php if($value->is_group=='1'){ print_r(implode('%, ', $tax_arr).'%'); }else{ print_r($value->tax_rates.'%'); }@endphp</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="mt-3 d-flex flex-wrap justify-content-between">
						<div class="form-group col-12">
							<label class="font-weight-bolder">Services Default Tax</label>
							<div class="input-group">
								<select name="service_default_tax" id="servicetax" class="form-control" >
									<option value="0">No tax</option>
									@foreach($taxs as $value2)
									@php
									if ($value2->is_group=='1') {
										$expgames_ids = explode(',', $value2['tax_rates']);
										$tax_arr = array();
										foreach($expgames_ids as $taxids){
					
											$tax = App\Models\Taxes::where('user_id',Auth::id())->where('id',$taxids)->where('is_deleted','0')->first();
											array_push($tax_arr, $tax['tax_rates']);
										}
					
									}
									@endphp
										<option value="{{ $value2->id }}">{{ $value2->tax_name }} @php if($value2->is_group=='1'){ print_r(implode('%, ', $tax_arr).'%'); }else{ print_r($value2->tax_rates.'%'); }@endphp</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="saveloctax">Save</button>
				</div>
			</div>
		</div>
	</div>
	<!--begin::end-->

	<div class="modal" id="taxCalculationModal" tabindex="-1" role="dialog" aria-labelledby="taxculationionModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="taxCalculationModalLabel">Change Tax Calculation</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<div class="bagde badge-secondary p-2 d-flex">
						<h6>
							This change will have a big impact on your sales. Make sure you understand all implications
						</h6>
						<div style="width: 30px;height: 30px;">
							<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<g fill-rule="nonzero" fill="none">
									<path
										d="M12.053 1.955c3.875.372 6.118 2.394 6.731 6.064.613 3.67-.51 6.06-3.365 7.17l-.45 5.064-.42 1.842-1.656 1.033h-2.476l-1.219-1.66v-3.727l-.787-2.552-2.432-2.648-1.147-3.603L5.979 6.06l.98-2.165L9.2 2.53l2.854-.574z"
										fill="#FFF"></path>
									<path d="M22.615.646a.5.5 0 0 1 .707.708l-2 2a.5.5 0 0 1-.707-.708l2-2z"
										fill="#101928"></path>
									<path
										d="M22.615.646a.5.5 0 0 1 .707.708l-2 2a.5.5 0 0 1-.707-.708l2-2zM23.322 17.646a.5.5 0 0 1-.707.708l-2-2a.5.5 0 0 1 .707-.708l2 2z"
										fill="#101928"></path>
									<path
										d="M23.322 17.646a.5.5 0 0 1-.707.708l-2-2a.5.5 0 0 1 .707-.708l2 2zM22.969 8.5a.5.5 0 1 1 0 1h-1a.5.5 0 1 1 0-1h1z"
										fill="#101928"></path>
									<path
										d="M22.969 8.5a.5.5 0 1 1 0 1h-1a.5.5 0 1 1 0-1h1zM1.322 18.354a.5.5 0 0 1-.707-.708l2-2a.5.5 0 0 1 .707.708l-2 2z"
										fill="#101928"></path>
									<path
										d="M1.322 18.354a.5.5 0 0 1-.707-.708l2-2a.5.5 0 0 1 .707.708l-2 2zM.615 1.354a.5.5 0 0 1 .707-.708l2 2a.5.5 0 1 1-.707.708l-2-2z"
										fill="#101928"></path>
									<path
										d="M.615 1.354a.5.5 0 0 1 .707-.708l2 2a.5.5 0 1 1-.707.708l-2-2zM.969 10.5a.5.5 0 1 1 0-1h1a.5.5 0 0 1 0 1h-1z"
										fill="#101928"></path>
									<path
										d="M.969 10.5a.5.5 0 1 1 0-1h1a.5.5 0 0 1 0 1h-1zM9.469 16.864a.5.5 0 1 1-1 0c0-.05-.005-.152-.019-.285a3.314 3.314 0 0 0-.129-.645c-.08-.254-.188-.45-.316-.573a.5.5 0 1 1 .693-.721c.265.254.45.592.576.992.147.464.195.915.195 1.232zM15.467 16.92a.5.5 0 0 1-1 0c0-.083.006-.217.022-.386a4.65 4.65 0 0 1 .153-.832c.128-.443.32-.81.605-1.071a.5.5 0 0 1 .675.738c-.127.116-.238.327-.319.609a3.674 3.674 0 0 0-.119.653 3.357 3.357 0 0 0-.017.29z"
										fill="#101928"></path>
									<path
										d="M8.643 14.594a.5.5 0 0 1-.583.812c-2.291-1.647-3.591-3.837-3.591-6.404A7.5 7.5 0 0 1 11.968 1.5a.5.5 0 1 1 0 1 6.5 6.5 0 0 0-6.5 6.502c0 2.22 1.13 4.122 3.175 5.592z"
										fill="#101928"></path>
									<path
										d="M15.877 15.406a.5.5 0 0 1-.584-.812c2.046-1.47 3.176-3.373 3.176-5.592A6.501 6.501 0 0 0 11.968 2.5a.5.5 0 1 1 0-1 7.501 7.501 0 0 1 7.501 7.502c0 2.567-1.3 4.757-3.592 6.404zM11.968 22.5a.5.5 0 1 1 0 1c-1.316 0-2.272-.468-2.888-1.25a2.87 2.87 0 0 1-.611-1.368V16.92a.5.5 0 1 1 1 0l-.007 3.882c.03.188.15.508.403.83.428.542 1.095.868 2.103.868z"
										fill="#101928"></path>
									<path
										d="M11.968 23.5a.5.5 0 1 1 0-1c1.008 0 1.675-.326 2.103-.869.253-.32.372-.64.396-.749V16.92a.5.5 0 1 1 1 0l-.007 4.042c-.055.341-.23.815-.604 1.289-.616.781-1.572 1.249-2.888 1.249z"
										fill="#101928"></path>
									<path
										d="M8.729 17.939a.5.5 0 1 1 .48-.878c.142.078.421.203.808.33 1.526.503 3.141.51 4.645-.293l.065-.036a.5.5 0 0 1 .483.876l-.076.041c-1.78.952-3.667.942-5.43.362a6.124 6.124 0 0 1-.975-.402z"
										fill="#101928"></path>
									<path d="M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" fill="#ffe78c"></path>
									<path d="M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"
										fill="#101928"></path>
								</g>
							</svg>
						</div>
					</div>
					<div class="mt-3 d-flex flex-wrap justify-content-between">
						<div class="form-group">
							<form name="saveformula" id="saveformula" >
							<div class="radio-list">
								<label class="radio">
									<input type="radio" @if(isset($formula->tax_formula) && $formula->tax_formula == '0') checked @endif  name="formula" id="formula1" value="0">
									<span></span>
									<p class="text-dark-50">
										<span class="text-dark">Retail Prices Exclude Tax</span><br>
										If selected, all taxes will be calculated using this formula:<br>
										<span class="font-weight-bolder">Tax = Retail * Tax</span>
										For example: 20% tax on a $10.00 item comes to $2.00
									</p>
								</label>
								<label class="radio">
									<input type="radio" @if(isset($formula->tax_formula) && $formula->tax_formula == '1') checked @endif name="formula" id="formula2" value="1">
									<span></span>
									<p class="text-dark-50">
										<span class="text-dark">Retail Prices Include Tax</span><br>
										If selected, all taxes will be calculated using this formula:<br>
										<span class="font-weight-bolder">Tax = (Tax Rate * Retail Price) / (1 + Tax
											Rate)</span>
										For example: 20% tax on a $10.00 item comes to $1.67
									</p>
								</label>
							</div>
							</form>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="updateformula">Save</button>
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
@section("scripts")
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/addtaxes.js') }}"></script>

	<script>
		//show table data
		$(document).ready( function(){
			var table = $('#taxestable').DataTable({
				processing: true,
				serverSide: true,
				"paging": true,
				"ordering": false,
				"info":     false,
				'searching' : true,
				ajax: {
					type: "POST",
					url : "{{ route('taxTates') }}",
					data: {_token : "{{csrf_token()}}"}
				},
				columns: [
						{data: 'tax_name' , name: 'tax_name'},
				]			
			});	
		
		});

		//show loc. table data
		$(document).ready( function(){
			var table = $('#loctable').DataTable({
				processing: true,
				serverSide: true,
				"paging": true,
				"ordering": false,
				"info":     false,
				'searching' : true,
				ajax: {
					type: "POST",
					url : "{{ route('locTates') }}",
					data: {_token : "{{csrf_token()}}"}
				},
				columns: [
						{data: 'location_name' , name: 'location_name'},
				]			
			});	
		
		});
		

		//edit tax
		$(document).on('click','.edittaxes',function(){
			
			var edittax = $(this).data('id');
			var name = $(this).data('name');
			var rates = $(this).data('rates');
			$('#updatetaxname').val(name);
			$('#updatetaxrate').val(rates);
			$('#updateid').val(edittax);
			$('#deletetax').data('id',edittax);

			
			$('#editNewTaxModal').modal('show');
		});

		//edit group tax
		$(document).on('click','.editgrouptaxes',function(){
			var edittax = $(this).data('id');
			var name = $(this).data('name');
			var rates = $(this).data('rates');
			$.ajaxSetup({
			   headers: {
			     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			   }
			});
			$.ajax({
				type: "post",
				url: "{{ route('editGroupTaxes') }}",
				data: {id:edittax},
				success: function (data) {

					$('#uptaxname').val(name);
					$('#upgroupid').val(edittax);
					$('#deletetax').data('id',edittax);

					$('#upappend').empty();

					$('#upappend').append(data);
					$('#editNewGroupTaxModal').modal('show');

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
			//end
			
		});

		//open group tax
		$(document).on('click','#opengrouptax', function(){
			
			$.ajax({
				type: "get",
				url: "{{ route('getgrouptax') }}",
				
				success: function (data) {
					console.log(data);
					$('#append').append(data);

					$('#addNewGroupTaxModal').modal('show');
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
			//end
		});

		//delete
		$(document).on('click','#deletetax', function(){
			var id = $(this).data('id');
			alert(id);

			$.ajax({
				type: "POST",
				url: "{{ route('deletetax') }}",
				headers:{
							'X-CSRF-Token': '{{ csrf_token() }}',
						},
				data: {id:id},
				success: function (data) {
					KTApp.unblockPage();
					toastr.success(data.message);
					$('#editNewTaxModal').modal('hide');
					var table = $('#taxestable').DataTable();
					table.ajax.reload();
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
		//end

		//edit loc tax
		$(document).on('click','.editloctaxes',function(){
			
			var locid = $(this).data('id');
			var prtax = $(this).data('pro');
			var sertax = $(this).data('ser');
			$('#updatelocid').val(locid);
			$('#producttax').val(prtax);
			$('#servicetax').val(sertax);
			
			$('#editlocTaxModal').modal('show');
		});

		//save loc tax
		$(document).on('click','#saveloctax', function(){
			var form = $("#updatloctax");
			$.ajax({
				type: "POST",
				url: "{{ route('saveLocTax') }}",
				headers:{
							'X-CSRF-Token': '{{ csrf_token() }}',
						},
				data: form.serialize(),
				success: function (data) {
					KTApp.unblockPage();
					toastr.success(data.message);
					$('#editlocTaxModal').modal('hide');
					var table = $('#loctable').DataTable();
					table.ajax.reload();
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

		//save tax formula
		$(document).on('click','#updateformula', function(){
			var form = $("#saveformula");
			$.ajax({
				type: "POST",
				url: "{{ route('savetaxformula') }}",
				headers:{
							'X-CSRF-Token': '{{ csrf_token() }}',
						},
				data: form.serialize(),
				success: function (data) {
					KTApp.unblockPage();
					toastr.success(data.message);
					$('#taxCalculationModal').modal('hide');
					var table = $('#loctable').DataTable();
					table.ajax.reload();
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