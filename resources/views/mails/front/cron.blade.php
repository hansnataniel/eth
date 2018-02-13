<?php
	use App\Models\Setting;
	use App\Models\Usermininghistory;

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
								<td style="padding: 5px 10px; vertical-align: top;">Last Mining History</td>
								<td style="padding: 5px 1px; width: 5px; vertical-align: top;">:</td>
								<td style="padding: 5px 2px;">
									Mining ID = {{$mininghistory->id}}<br>
									{{-- MH = {{$mininghistory->id}}<br> --}}
									Balance API = {{$mininghistory->balance_api}}<br>
									Increment = {{$mininghistory->inc}}<br>
									Balance Real = {{$mininghistory->balance_real}}<br>
									Selisih Real = {{$mininghistory->selisih_real}}<br>
									Balance = {{$mininghistory->balance}}<br>
									Selisih = {{$mininghistory->selisih}}
								</td>
							</tr>
							<tr>
								<td style="padding: 5px 10px; vertical-align: top;">AVG</td>
								<td style="padding: 5px 1px; width: 5px; vertical-align: top;">:</td>
								<td style="padding: 5px 2px;">
									Average = {{$avg->average}}<br>
									Counter = {{$avg->counter}}
								</td>
							</tr>
							<tr>
								<td colspan="3" style="padding: 5px 10px; vertical-align: top;">User Mining History</td>
							</tr>
							@foreach($users as $user)
								<tr>
									<td style="padding: 5px 10px; vertical-align: top;">{{$user->name}}</td>
									<td style="padding: 5px 1px; width: 5px; vertical-align: top;">:</td>
									<td style="padding: 5px 2px;">
										<?php
											$lastusermininghistory = Usermininghistory::where('user_id', '=', $user->id)->orderBy('id', 'desc')->first();
										?>

										@if($lastusermininghistory != null)
											Balance = {{$lastusermininghistory->balance}}<br>
											Selisih = {{$lastusermininghistory->selisih}}
										@endif
									</td>
								</tr>
							@endforeach
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