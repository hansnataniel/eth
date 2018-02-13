@extends('front.template.master')

@section('title')
	Withdrawal History
@endsection

@section('head_additional')
	
@endsection

@section('content')
	<div class="content-container">
		<h1 class="content-title" style="font-size: 26px;">
			WITHDRAWAL HISTORY
		</h1>
		
		<div class="content-group" style="max-width: 820px; font-size: 0px;">
			@if($getwithdrawals->isEmpty())
				<span class="empty">
					You don't have withdrawal history
				</span>
			@endif

			@if(!$getwithdrawals->isEmpty())
				<div class="content-group-list">
					<table class="list-first">
						<tr>
							<th>
								#
							</th>
							<th>
								No Nota
							</th>
							<th>
								Date
							</th>
							<th>
								Type
							</th>
							<th style="text-align: right;">
								Amount
							</th>
							<th>
								Status
							</th>
						</tr>
						<?php
							$no = 0;
						?>
						@foreach($getwithdrawals as $getwithdrawal)
							<?php
								$no++;
							?>
							<tr>
								<td>
									{{$no}}
								</td>
								<td>
									{{$getwithdrawal->no_nota}}
								</td>
								<td>
									{{date('d-m-Y', strtotime($getwithdrawal->date))}}
								</td>
								<td>
									@if($getwithdrawal->amount_eter == null)
										IDR
									@else
										Eter
									@endif
								</td>
								<td style="text-align: right;">
									@if($getwithdrawal->amount_eter == null)
										{{number_format($getwithdrawal->amount_idr)}}
									@else
										{{number_format($getwithdrawal->amount_eter)}}
									@endif
								</td>
								<td>
									{{$getwithdrawal->status}}
								</td>
							</tr>
						@endforeach
					</table>

					@foreach($getwithdrawals as $getwithdrawal)
						<table class="list-first mob">
							<tr>
								<td>
									No Nota
								</td>
								<td class="dot">
									:
								</td>
								<td>
									{{$getwithdrawal->no_nota}}
								</td>
							</tr>
							<tr>
								<td>
									Date
								</td>
								<td class="dot">
									:
								</td>
								<td>
									{{date('d-m-Y', strtotime($getwithdrawal->date))}}
								</td>
							</tr>
							<tr>
								<td>
									Type
								</td>
								<td class="dot">
									:
								</td>
								<td>
									@if($getwithdrawal->amount_eter == null)
										IDR
									@else
										Eter
									@endif
								</td>
							</tr>
							<tr>
								<td>
									Amount
								</td>
								<td class="dot">
									:
								</td>
								<td>
									@if($getwithdrawal->amount_eter == null)
										{{number_format($getwithdrawal->amount_idr)}}
									@else
										{{number_format($getwithdrawal->amount_eter)}}
									@endif
								</td>
							</tr>
							<tr>
								<td>
									Status
								</td>
								<td class="dot">
									:
								</td>
								<td>
									{{$getwithdrawal->status}}
								</td>
							</tr>
						</table>
					@endforeach
				</div>
			@endif
		</div>

		<a class="content-history" href="{{ url('withdrawal') }}">
			BACK
		</a>
	</div>
@endsection