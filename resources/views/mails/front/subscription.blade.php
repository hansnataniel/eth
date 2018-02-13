<?php
	use Illuminate\Support\Str;
	use App\Models\Setting;
	use App\Models\Bank;

	$setting = Setting::first();

	$banks = Bank::where('is_active', '=', true)->get();
?>

<html>
	<head>
		<title>ETH Mining Pool</title>
	</head>
	<body>
		<table id="wrapper" style="font-size: 14px; color: #0d0f3b; font-family: arial; width: 100%; line-height: 20px;">
			<tr>
				<td id="header-container" style="padding: 10px 20px; text-align: center;">
					{{HTML::image('img/admin/eth_logo.png', 'ETH Mining Pool', array('style'=>'width: 200px;'))}}
				</td>
			</tr>
			<tr>
				<td id="section-container" style="padding: 20px;">
					Hello {{$user->name}},<br>

					<p>
		                Thank you for buying MH in ETH Minig Pool. <br>
		                Please make a payment and confirm your payment <a href="{{URL::to('payment/' . str_replace('/', '-', $usermh->no_nota))}}" target="_blank" style="color: #FDD000; text-decoration: none">here.</a> to activate your MH.<br><br>
		                
		                You can do the payment using transfer to our bank account:
		            </p>
					<table border="0" style="font-size: 14px; color: #595959; font-family: arial; border-spacing: 0px; border: solid 1px #dbdbdb; border-top: none; border-bottom: none;">
						<tr>
							<td style="padding: 10px; background: #ff0000; color: #fff;">
								Bank
							</td>
							<td style="padding: 10px; background: #ff0000; color: #fff;">
								Account Name
							</td>
							<td style="padding: 10px; background: #ff0000; color: #fff;">
								Account Number
							</td>
						</tr>
						<?php
							$counter2 = 0;
						?>
						@foreach ($banks as $bank)
							<?php 
								$counter2++;
							?>
							<tr>
	                            <td style="padding: 10px; border-bottom:solid 1px #dbdbdb;">{{$bank->name}}</td>
	                            <td style="padding: 10px; border-bottom:solid 1px #dbdbdb;">{{$bank->account_name}}</td>
	                            <td style="padding: 10px; border-bottom:solid 1px #dbdbdb;">{{$bank->account_number}}</td>
	                        </tr>
						@endforeach
					</table><br>

					<p>
		                <span>Your Subscription data:</span>
		            </p>
					<table border="0" style="font-size: 14px; color: #595959; font-family: arial; border-spacing: 0px; border: solid 1px #dbdbdb; border-top: none;">
						<tr>
							<td style="padding: 10px; background: #FDD000; color: #000;">
								Date
							</td>
							<td style="padding: 10px; background: #FDD000; color: #000;">
								Transaction ID
							</td>
							<td style="padding: 10px; background: #FDD000; color: #000;">
								Price per MH
							</td>
							<td style="padding: 10px; background: #FDD000; color: #000;">
								MH Amount
							</td>
							<td style="padding: 10px; background: #FDD000; color: #000;">
								Amount To Pay
							</td>
						</tr>
						<tr>
							<td style="padding: 10px">{!!date('d F Y', strtotime($usermh->created_at))!!}</td>
							<td style="padding: 10px">{{$usermh->no_nota}}</td>
							<td style="padding: 10px">IDR {!!rupiah3($setting->mh_price)!!}</td>
							<td style="padding: 10px">{{$usermh->mh}}</td>
							<td style="padding: 10px">IDR {!!rupiah3($setting->mh_price * $usermh->mh)!!}</td>
						</tr>
					</table><br><br>

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