{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
#productTable_filter{
	display:none;
}
</style>
@endsection

{{-- CSS Section --}}
@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<!--begin::Tabs-->
	<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
		<div class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1 scroll">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5 scroll-manu"
				role="tablist">
				<li class="nav-item pl-3">
					<a class="nav-link active" href="{{ route('inventory') }}">Products</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('orders') }}">Orders</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('brands') }}">Brands</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('categories') }}">Categories</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('suppliers') }}">Suppliers</a>
				</li>
			</ul>
		</div>
	</div>
	<!--end::Tabs-->
	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid">
		<!--begin::Container-->
		<div class="container">
			<!--begin::nventory-->
			<!--begin::Row-->
			<div class="content-header ">
				<div class="justify-content-between display-investory" style="margin-top: 14px;margin-bottom: 10px;">
					<div class="calender-div my-2">
						<div class="input-group date">
							<div class="form-group mb-0 width-100">
								<div class="input-icon">
									<input type="text" class="font-weight-500 form-control form-control-lg width-100" placeholder="Search by product or barcode" id="myInputTextField">
									<span>
										<i class="flaticon2-search-1 icon-md"></i>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="action-btn-div width-300px">
						<div class="dropdown dropdown-inline mr-2">
							<button type="button"
								class="btn btn-white dropdown-toggle"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
							<div class="dropdown-menu dropdown-menu-xs">
								<!--begin::Navigation-->
								<ul class="navi flex-column navi-hover py-2">
									<li class="navi-item">
										<a href="{{route('productinfoexcel')}}" class="navi-link">
											<span class="navi-icon">
												<i class="la la-file-excel-o"></i>
											</span>
											<span class="navi-text">Excel</span>
										</a>
									</li>
									<li class="navi-item">
										<a href="{{ route('productinfocsv') }}" class="navi-link">
											<span class="navi-icon">
												<i class="la la-file-text-o"></i>
											</span>
											<span class="navi-text">CSV</span>
										</a>
									</li>
									<!-- <li class="navi-item">
										<a href="#" class="navi-link">
											<span class="navi-icon">
												<i class="la la-file-pdf-o"></i>
											</span>
											<span class="navi-text">PDF</span>
										</a>
									</li> -->
								</ul>
								<!--end::Navigation-->
							</div>
							<!--end::Dropdown Menu-->
						</div>
						<button class="font-weight-bold btn btn-white px-3"
							onclick="openNav()">
							Filter <i class="fa fa-filter"></i>
						</button>
						<a class="font-weight-bold btn btn-primary px-4 my-2" href="{{ route('addProduct') }}">
							New Product
						</a>
					</div>
				</div>
			</div>
			<div class="row my-4">
				<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
					<!--begin::List Widget 3-->
					<div class="card card-custom card-stretch gutter-b p-4">
						<!--begin::Body-->
						<!--begin::Item-->
						<div class="table-responsive brand-table">
							<table class="table table-hover" id="productTable">
								<thead>
									<tr>
										<th>Product name</th>
										<th>Barcode</th>
										<th>Retail price</th>
										<th>Stock On Hand</th>
										<th>Updated at</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
						<!--end:Item-->
						<!--end::Body-->
					</div>
					<!--end::List Widget 3-->
				</div>
			</div>
			<!--end::Row-->
			<!-- Filter Sidebar -->
			<div id="myFilter" class="filter-overlay" style="width: 0px;">
				<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
				<div class="filter-header pt-2">
					<h2 class="text-center py-3">Filter</h2>
				</div>
				<form id="filterProduct" name="filterProduct">
					<div class="filter-overlay-content">
						<div class="filter-overlay-content p-4">
							<div class="form-group font-weight-bold mb-0">
								<label for=" select-location font-weight-bold">Brand</label>
								<select class="form-control" id="select-brand" name="brand">
									<option value="">All Brands</option>
									@foreach($inventoryBrand as $value)
										<option value="{{ $value->id }}">{{ $value->brand_name }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group font-weight-bold mt-4 mb-0">
								<label for=" select-location font-weight-bold">Category</label>
								<select class="form-control" id="select-cat" name="cate">
									<option value="">All Categories</option>
									@foreach($categories as $value)
										<option value="{{ $value->id }}">{{ $value->category_name }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group font-weight-bold mt-4 mb-0">
								<label for=" select-location font-weight-bold">Supplier</label>
								<select class="form-control" id="select-sup" name="supp">
									<option value="">All Suppliers</option>
									@foreach($ProductSupplier as $value)
										<option value="{{ $value->id }}">{{ $value->supplier_name }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group font-weight-bold mt-4 mb-0">
								<label for="instock" class="checkbox">
									<input type="checkbox" name="instock" id="instock">
									<span></span>Show out of stock products only
								</label>
							</div>
						</div>
						<div class="button-action d-flex justify-content-between px-5">
							<button type="button" onclick="closeNav()" class="btn btn-white w-100 mr-4">
								Clear
							</button>
							<button type="button" class="btn btn-primary w-100" id="apply">
								Apply
							</button>
						</div>
					</div>
				</form>
			</div>
			<!-- End Filter Sidebar -->
			<!--end::nventory-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Content-->
@endsection
{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script>
	$(function() {
		table = $('#productTable').DataTable({
			processing: true,
			serverSide: true,
			"ordering": false,
			"info":     false,
			ajax: {
				type: "POST",
				url : "{{ route('getProducts') }}",
				data: function(d){

                    return $.extend( {}, d, {
						_token : "{{csrf_token()}}",
						brand : $("#select-brand").val(),
						cat : $("#select-cat").val(),
						sup : $("#select-sup").val(),
						instock : $("#instock:checked").val()
					});
				}
			},
			columns: [
				{data: 'product_name', profile: 'product_name'},
				{data: 'barcode', name: 'barcode'},
				{data: 'retail_price', name: 'retail_price'},
				{data: 'initial_stock', name: 'initial_stock'},
				{data: 'updated_at', name: 'updated_at'},
			]			
		});	
		
		$('#myInputTextField').keyup(function(){
			  table.search($(this).val()).draw();
		});

		/*$(document).on('change','#select-brand, #select-cat, #select-sup', function(){
			table.draw();
		});*/
	});
	$(document).on('click','#apply',function(){
		document.getElementById("myFilter").style.width = "0%";
		table.draw();
		/*var data = $('#filterProduct').serialize();
		console.log(data);
		//
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		});
		$.ajax({
			type:'POST',
           	url:"{{ route('filterProductList') }}",
           	data: data,
			success: function (data) {
				KTApp.unblockPage();
				html = '';
				for(var j=0;j < data.Data.length; j++) {
					html +='<tr>'
					   +'<td>'+data.Data[j].product_name+'</td>'
					   +'<td>'+data.Data[j].barcode+'</td>'
					   +'<td>'+data.Data[j].retail_price+'</td>'
					   +'<td>'+data.Data[j].initial_stock+'</td>'
					   +'<td>'+data.Data[j].updated_at+'</td>'
					   +'</tr>';
				}
				if(data.Data.length==0){
					tableB = $("table tbody").empty();
					tableBody = $("table tbody");
					tableBody.append('<tr><td> No results found </td></tr>');
				} else {
					tableB = $("table tbody").empty();
					tableBody = $("table tbody");
					tableBody.append(html);
				}
			}
		});*/
	});

	$(document).on('click','.editProduct',function(){
		var thisID = $(this).data('id');
		var url = "{{ url('partners/viewProduct') }}";
		if(thisID != "" && typeof thisID !== "undefined") 
		{
			window.location = url+'/'+thisID;
		}
	});
</script>
<script type="text/javascript">

	function openNav() {
		document.getElementById("myFilter").style.width = "300px";
	}

	function closeNav() {
		$('#select-brand').val('');
		$('#select-sup').val('');
		$('#select-cat').val('');
		document.getElementById("myFilter").style.width = "0%";
		table.draw();
	}
</script>
@if(Session::has('message'))
<script>
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

	toastr.success('{{ Session::get('message') }}');
</script>

@endif

@endsection