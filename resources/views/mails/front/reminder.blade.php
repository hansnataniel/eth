<?php
	use App\Models\Setting;

	$setting = Setting::first();
?>

<html>
	<head>
		<title>ETH Mining Pool</title>
	</head>
	<body>
		<table id="wrapper" style="font-size: 14px; color: #222; font-family: arial; width: 100%; line-height: 20px;">
			<tr>
				<td id="header-container" style="padding: 10px 20px; text-align: center;">
					{{HTML::image('img/admin/eth_logo.png', '', array('style'=>'width: 200px;'))}}
				</td>
			</tr>
			<tr>
				<td id="section-container" style="padding: 20px;">
					<div>
						Click button below to reset your password, this link will expired in one houre latter.Forget this email if you dont't want reset your password.<br><br>

						<a href="{{URL::to('password/reset/' . $token)}}" style="position: relative; display: table; padding: 10px 15px; background: #feeb2d; color: #fff; font-size: 12px; text-decoration: none;">
							Reset Password
						</a>
					</div>
					<br><br>

					Best regards, <br>
						
					ETH Mining Pool
					<br><br>
					
					<p>
						
						If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser.
						<br><br>
						<span style="color: blue;">
							{{ URL::to('password/reset/' . $token) }}
						</span>
					</p>

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