{{-- Extends layout --}}
@extends('layouts.email')

@section('content')
<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
   <tr>
	  <td valign="middle" class="hero bg_white" style="padding: 2em 0 4em 0;">
		 <table>
			<tr>
			   <td>
				  <div class="text" style="padding: 0 2.5em;">
					 <p>Hello, {{ $name }}!</p><br>

					 <p>Someone has requested a link to change your password. You can do this by clicking <a href="{{ route('reset-password', ['token' => $token, 'email' => urlencode($email)]) }}">here</a>.</p><br>

					 <p>If you didn't request this, please ignore this email. Your password won't change until you access the link above and create a new one.</p><br>

					 <p>Best regards,<br>
					 ScheduleDown Team</p>
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