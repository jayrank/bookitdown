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
					 <p style="display: block; color: #000; font-size: 1.4em; line-height: 1.4em; font-weight: bold; margin: 0;">Hi {{ $name }}, reset your log in password for {{ $locationName }}'s partner account on {{ config('app.name', 'Laravel') }}</p><br>

					 <p style="display: block; color: #000; font-size: 1em; line-height: 1.5em; margin: 0;">There was a request to securely reset your login password, click the button below to continue.</p><br>

					 <a href="{{ route('partner-reset-password', ['token' => $token, 'email' => urlencode($email)]) }}" style="font-size: 1.05em; line-height: 1.4em; background-color: #101928; display: inline-block; color: #ffffff; -webkit-text-decoration: none; text-decoration: none; border-radius: 4px; font-weight: bold; box-shadow: 0px 4px 12px 0px rgb(103 118 140 / 50%); padding: 13px 24px 15px;">Reset password</a>

					 <!-- <p>If you didn't request this, please ignore this email. Your password won't change until you access the link above and create a new one.</p><br>

					 <p>Best regards,<br>
					 ScheduleDown Team</p> -->
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