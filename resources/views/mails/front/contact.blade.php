<?php
	use App\Models\Setting;

	$setting = Setting::first();
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
						<table border="0" style="font-size: 14px; color: #595959; font-family: arial;">
							<tr>
								<td style="padding: 5px 10px; vertical-align: top;">Name</td>
								<td style="padding: 5px 1px; width: 5px; vertical-align: top;">:</td>
								<td style="padding: 5px 2px;">{{$contact->name}}</td>
							</tr>
							<tr>
								<td style="padding: 5px 10px; vertical-align: top;">Email</td>
								<td style="padding: 5px 1px; width: 5px; vertical-align: top;">:</td>
								<td style="padding: 5px 2px;">{{$contact->email}}</td>
							</tr>
							<tr>
								<td style="padding: 5px 10px; vertical-align: top;">Phone</td>
								<td style="padding: 5px 1px; width: 5px; vertical-align: top;">:</td>
								@if($contact->phone != null)
									<td style="padding: 5px 2px;">{{$contact->phone}}</td>
								@else
									<td style="padding: 5px 2px;">-</td>
								@endif
							</tr>
							<tr>
								<td style="padding: 5px 10px; vertical-align: top;">Message</td>
								<td style="padding: 5px 1px; width: 5px; vertical-align: top;">:</td>
								<td style="padding: 5px 2px;">{{nl2br($contact->message)}}</td>
							</tr>
						</table>
					<br><br>

					Best regards, <br>
						
					ETH Mining Pool
					<br><br>
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