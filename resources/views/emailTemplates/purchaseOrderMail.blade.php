{{-- Extends layout --}}
@extends('layouts.email')

@section('content')
<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
   <tr>
	  <td valign="middle" class="hero bg_white" style="padding: 2em 0 4em 0;">
		 <table>
			<tr>
			   <td>
				  <div class="text" style="padding: 0 2.5em; text-align: center;">
					 <h2>{{$email_message}}</h2>
					 <h3>{{ route('saveOrderPdf', ['id' => $orderid]) }}</h3>
				  </div>
			   </td>
			</tr>
		 </table>
	  </td>
   </tr>
   <!-- end tr -->
   <!-- 1 Column Text + Button : END -->
</table>
@endsection