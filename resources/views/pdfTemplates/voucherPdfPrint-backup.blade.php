<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <base href="">
    <meta charset="utf-8" />
    <title>ScheduleDown</title>
	<style>
	.page-break {
		page-break-after: always;
	}
	</style>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="bg-white">
    <div class="">
		@if(!empty($VoucherSold))
			<div style="margin:auto;position: relative;display: -webkit-box;display: -ms-flexbox;display: flex;text-align: center;justify-content: center;-webkit-box-orient: vertical;-webkit-box-direction: normal;-ms-flex-direction: column;flex-direction: column;min-width: 0;word-wrap: break-word;background-color: rgb(255, 255, 255);background-clip: border-box;border: 1px solid #d1d1d4;border-radius: 0.42rem;width: 50%;">
			@foreach($VoucherSold as $VoucherSoldData)
				<div style="-webkit-box-flex: 1;-ms-flex: 1 1 auto;flex: 1 1 auto;min-height: 1px;">
					<div style="width: 70%;margin: auto;position: relative;">
						<h4 style="text-align: center;">{{ ($RecipientPersonalizedEmail) ? $RecipientPersonalizedEmail : (($VoucherSoldData['name']) ? $VoucherSoldData['name'] : '') }}</h4>
						<h5 style="text-align: center;">Voucher for</h5>
						<h4 style="text-align: center;">{{ ($RecipientFirstName) ? $RecipientFirstName : '' }} {{ ($RecipientLastName) ? $RecipientLastName : '' }}</h4>
						<div style="border-radius: 6px;padding: 2.2em;background: linear-gradient(-45deg, rgb(190, 74, 244) 0%, rgb(92, 55, 246) 100%);color: rgb(125, 59, 230);">
							<div style="pad: 8px;text-align: center;">
								<img alt="voucher-thumb" src="{{ asset('./assets/images/thumb.jpg') }}" width="80px" height="80px" style="border-radius: 10px;margin-bottom: 10px;">
								<h3 style="font-weight: bold;color: black;">{{ ($VoucherSoldData['location_name']) ? $VoucherSoldData['location_name'] : '' }}</h3>
								<h5 style="color: black;">{{ ($VoucherSoldData['location_address']) ? $VoucherSoldData['location_address'] : '' }}</h5>
							</div>
							<div style="border-bottom: 1px solid rgb(235, 237, 243) !important;width: 100%;"></div>
							<div style="margin-bottom: 2rem">
								<p style="color:black;font-weight: bolder;">Voucher Value</p>
								<h1 style="color:black;font-weight-bolder;">CA $<span id="vaoucher-price">{{ ($VoucherSoldData['price']) ? $VoucherSoldData['price'] : 0 }}</span>
								</h1>
							</div> 
							<div style="border-bottom: 1px solid rgb(235, 237, 243) !important;width: 100%;"></div>
							<div style="color: black;margin-bottom: 1rem">
								<p style="margin-bottom: 1rem;font-size: 1.08rem;">Voucher Code : <span style="font-weight:bolder">{{ ($VoucherSoldData['voucher_code']) ? $VoucherSoldData['voucher_code'] : '' }}</span></p>
								<p style="font-weight:bolder">Redeem on <span class="font-weight-bolder cursor-pointer">all services</span> <i class="fa fa-chevron-right icon-sm"></i></p>
								<p style="font-weight:bolder">Valid until {{ ($VoucherSoldData['end_date']) ? date("d M Y",strtotime($VoucherSoldData['end_date'])) : '' }}</p>
								<p style="font-weight:bolder">For multiple-use</p>
							</div>
						</div>
					</div>
				</div>
				<div class="page-break"></div>
			@endforeach
			</div>
		@endif
    </div>
</body>

</html>