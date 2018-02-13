@extends('front.template.master')

@section('title')
	Subscription
@endsection

@section('head_additional')
	
@endsection

@section('js_additional')
	<script>
		$(document).ready(function(){
			$('.content-submit').click(function(e){
				e.stopPropagation();
				e.preventDefault();

				$('.mh-buy').text($('.mh-text').val());
				$('.total').text("IDR " + number_format(parseFloat($('.mh-text').val()) * parseInt($('.per-mh').text())));

				$('.msg-content').html($('.hide').html());

				$('.msg-ctn').fadeIn();
				$('.msg-list').each(function(){
					$(this).delay(100*e).animate({
						'top': 0,
						'opacity': 1
					}, 800, "easeInOutCubic");
				});
			});

			$('.hide-button').live('click', function(){
				$('.msg-ctn').fadeOut();
			});

			$('.submit').live('click', function(){
				$('.content-form').submit();
			});
		});
	</script>
@endsection

@section('content')
	<div class="content-container">
		<h1 class="content-title" style="font-size: 26px;">
			PAYMENT HISTORY
		</h1>
		
		<div class="content-group" style="max-width: 820px; font-size: 0px;">
			@if($paymenthistories->isEmpty())
				<span class="empty">
					You don't have payment history
				</span>
			@endif

			@if(!$paymenthistories->isEmpty())
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
						@foreach($paymenthistories as $paymenthistory)
							<?php
								$no++;
							?>
							<tr>
								<td>
									{{$no}}
								</td>
								<td>
									{{$paymenthistory->no_nota}}
								</td>
								<td>
									{{date('d-m-Y', strtotime($paymenthistory->date))}}
								</td>
								<td style="text-align: right;">
									{{number_format($paymenthistory->amount)}}
								</td>
								<td>
									{{$paymenthistory->status}}
								</td>
							</tr>
						@endforeach
					</table>


					@foreach($paymenthistories as $paymenthistory)
						<table class="list-first mob">
							<tr>
								<td>
									No Nota
								</td>
								<td class="dot">
									:
								</td>
								<td>
									{{$paymenthistory->no_nota}}
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
									{{date('d-m-Y', strtotime($paymenthistory->date))}}
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
									{{number_format($paymenthistory->amount)}}
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
									{{$paymenthistory->status}}
								</td>
							</tr>
						</table>
					@endforeach
				</div>
			@endif
		</div>

		<a class="content-history" href="{{ url('payment') }}">
			BACK
		</a>
	</div>
@endsection