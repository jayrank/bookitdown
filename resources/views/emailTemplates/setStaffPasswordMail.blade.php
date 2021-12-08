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
					 <h2>Hi {{ $from_name }}, your log in access was changed for Gym area 's partner account on Fresha</h2>
					 <h3>Your permission level has changed from low to high. In case you need further updates made to your permission level, you can reach out to the account owner directly.</h3>
					 <p><a href="{{ url('/profile') }}" class="btn btn-primary">Login</a></p>
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