<?php
	use Illuminate\Support\Str;
	use App\Models\Setting;
	use App\Models\Bank;
	use App\Models\User;

	$setting = Setting::first();
	$user = User::find($payment->user_id);

	$bank = Bank::find($payment->bank_id);
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
					Hello {{$setting->receiver_email_name}},<br>

					<br>
					You have new payment confirmation:<br>

					<table border="0" style="font-size: 14px; color: #595959; font-family: arial;">
						<tr>
							<td style="padding: 5px 10px; padding-left: 0px;">Transaction ID</td>
							<td style="padding: 5px 10px;">{{$payment->transaction_number}}</td>
						</tr>
						<tr>
							<td style="padding: 5px 10px; padding-left: 0px;">From</td>
							<td style="padding: 5px 10px;">{{$user->name}}</td>
						</tr>
						<tr>
							<td style="padding: 5px 10px; padding-left: 0px;">Amount</td>
							<td style="padding: 5px 10px;">IDR {{number_format($payment->amount)}}</td>
						</tr>
						<tr>
							<td style="padding: 5px 10px; padding-left: 0px;">Transfer To</td>

							<td style="padding: 5px 10px;">{{$bank->name . ' | ' . $bank->account_number . ' | ' . $bank->account_name}}</td>
						</tr>
						<tr>
							<td style="padding: 5px 10px; padding-left: 0px;">Transfer From</td>
							<td style="padding: 5px 10px;">{{$payment->bank . ' | ' . $payment->account_number . ' | ' . $payment->account_name}}</td>
						</tr>
						<tr>
							<td style="padding: 5px 10px; padding-left: 0px;">Transfer Date</td>
							<td style="padding: 5px 10px;">{{tanggal2($payment->date_transfer)}}</td>
						</tr>
						
					</table>

					See Payment Confirmation data <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment/view/' . $payment->id)}}" target="_blank" style="color: #FDD000; text-decoration: none;">here</a>.
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