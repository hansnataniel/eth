@extends('front.template.master')

@section('title')
	Purchase
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
			CLOUD MINING HISTORY
		</h1>
		
		<div class="content-group" style="max-width: 820px; font-size: 0px;">
			@if(($getusermhs->isEmpty()) AND ($paidgetusermhs->isEmpty()))
				<span class="empty">
					You don't have purchase history
				</span>
			@endif

			@if(!$getusermhs->isEmpty())
				<div class="regis-group-list satu">
					<h3>
						Waiting for Payment
					</h3>
					@foreach($getusermhs as $getusermh)
						<div class="regis-item-list">
							{{date('d-m-Y', strtotime($getusermh->created_at))}}<br>
							<span style="font-size: 14px;">
								No Nota : {{$getusermh->no_nota}}<br>
								<strong>{{$getusermh->mh}} MH</strong>
							</span>
							<a class="regis-pay" href="{{URL::to('payment/' . str_replace('/', '-', $getusermh->no_nota))}}">
								PAY
							</a>
							<a class="regis-del" href="{{URL::to('subscription/del/' . str_replace('/', '-', $getusermh->no_nota))}}">
								DELETE
							</a>
						</div>
					@endforeach
				</div>
			@endif

			@if(!$paidgetusermhs->isEmpty())
				<div class="regis-group-list dua">
					<h3>
						Paid
					</h3>
					@foreach($paidgetusermhs as $paidgetusermh)
						<div class="regis-item-list">
							{{date('d-m-Y', strtotime($paidgetusermh->created_at))}}<br>
							<span style="font-size: 14px; padding-bottom: 5px; position: relative; display: block;">
								No Nota : {{$paidgetusermh->no_nota}}<br>
								<strong>{{$paidgetusermh->mh}} MH</strong>
							</span>
							<span style="line-height: 16px;">
								Confirmed at: {{date('d-m-Y', strtotime($paidgetusermh->date))}}<br>
								Due date: {{date('d-m-Y', strtotime($paidgetusermh->date . '+ 3 Year'))}}
							</span>
						</div>
					@endforeach
				</div>
			@endif
		</div>

		<a class="content-history" href="{{ url('subscription') }}">
			BACK
		</a>
	</div>
@endsection