<?php
	use Illuminate\Support\Str;
	use App\Models\Setting;
	use App\Models\User;


	$setting = Setting::first();

	$code = Crypt::encrypt($user->id);
?>

<html>
	<head>
		<title>ETH Mining Pool</title>
	</head>
	<body>
		<table id="wrapper" style="font-size: 14px; color: #0d0f3b; font-family: arial; width: 100%; line-height: 20px;">
			<tr>
				<td id="header-container" style="padding: 10px 20px; text-align: center;">
					{{HTML::image('img/admin/eth_logo.png', '', array('style'=>'width: 200px;'))}}
				</td>
			</tr>
			<tr>
				<td id="section-container" style="padding: 20px;">
					<br>
					Hello {{$user->name}},<br><br>

					<p>
						Thank you for registration in ETH Mining Pool, Please activate your account with click this button below <a href="{{URL::to('activation' . '/' . $code)}}" style="color: #FDD000; text-decoration: none; font-family: arial;">Click Here.</a>
					</p>
					
					<br><br>

					Best regards, <br>
						
					ETH Mining Pool
					<br>

					<br>
				</td>
			</tr>
			<tr>
				<td class="not-reply" style="font-size: 11px; line-height: 13px;padding-left: 20px;">
					<i>
						*This email was sent from a notification-only address that cannot accept incoming emails. Please do not reply to this email.
					</i>
					<br><br>
				</td>
			</tr>
			<tr>
				<td  id="footer-container" style="padding: 10px 20px; color: #222; background: #feeb2d; text-align: center">
					<span>
						© {{date('Y')}} - ETH Mining Pool
					</span>
				</td>
			</tr>
		</table>
	</body>
</html>