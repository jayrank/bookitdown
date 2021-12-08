{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')

	<style>
		
		.font-weight-bolder{
			position: relative;
    		right: 27%;
		}
		.text-muted{
			font-size: 30px;
		}
		.mr-2{
			padding: 18px;
		}
		
	</style>
@endsection

@section('content')
<div class="container-fluid p-0" id="addNewSingleServiceModal" tabindex="-1" role="dialog" aria-labelledby="addNewSingleServiceModalLabel" >
	<div class="my-custom-body-wrapper">
		<div class="my-custom-body bg-secondary">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-12 col-md-10 p-2 pr-4">
						<div class="my-custom-header">
							<div class="d-flex justify-content-between align-items-center border-bottom">
								<p class="cursor-pointer m-0 px-10" onclick="history.back();"><i
										class="text-dark fa fa-times icon-lg"></i>
								</p>
								<h1 class="font-weight-bolder">Change the staff order</h1>
								{{-- <p>Drag and drop staff members to change the order. This order will reflect calendar visibility and the online booking flow.</p> --}}
							</div>
						</div>
						<div class="services">
							<ul id="Sersortable" class="sortable p-0 collapse show multi-collapse my-2" >
								@foreach($staff as $value)
								<li class="sortindex" data-id="{{ $value->id }}">
									<span onclick="window.location='{{ route('getservice',$value->id) }}'" ><i class="fa fa-bars px-2 my-2 mr-2"></i></span>
									<span class="text-muted">{{ $value->first_name }} {{ $value->last_name }}</span>
								</li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
//service order
$(".sortable").sortable({
	placeholder: 'placeholder',
	forcePlaceholderSize: true,
	tolerance: 'pointer',
	revert: true,
	update: function() {
		saveServiceOrder();
	}
});
$("ul, li").disableSelection();

function saveServiceOrder() {
	var order = [];
	$('#Sersortable .sortindex').each(function(index,element) {
	  order.push({
		id: $(this).attr('data-id'),
		position: index+1
	  });
	});
	console.log(order);
	$.ajax({
		  type: "POST", 
		  dataType: "json", 
		  headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		 url: "{{ route('stafftorder') }}",
		data: {
			order: order,
		},
		success: function(response) {
			if (response.status == "success") {
				console.log(response);
			} else {
				console.log(response);
			}
		}
	});
}
</script>
@endsection
