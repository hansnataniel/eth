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
			MH PURCHASE HISTORY
		</h1>
		
		<div class="content-group" style="max-width: 820px; font-size: 0px;">
			@if(($usermhs->isEmpty()) AND ($paidusermhs->isEmpty()))
				<span class="empty">
					You don't have any purchase
				</span>
			@endif

			@if(!$usermhs->isEmpty())
				<div class="regis-group-list satu">
					@foreach($usermhs as $usermh)
						<div class="regis-item-list">
							{{date('d-m-Y', strtotime($usermh->created_at))}}<br>
							<span style="font-size: 14px;">
								Transaction Code: {{$usermh->no_nota}}<br>
								<strong>{{$usermh->mh}} MH</strong>
							</span><br>
							<span>Status: {{$usermh->status}}</span>
							@if($usermh->status == "Waiting for Payment")
								<a class="regis-pay" href="{{URL::to('payment/' . str_replace('/', '-', $usermh->no_nota))}}">
									PAY
								</a>
								<a class="regis-del" href="{{URL::to('subscription/del/' . str_replace('/', '-', $usermh->no_nota))}}">
									CANCEL
								</a>
							@endif
							@if($usermh->status == "Active")
								<span style="line-height: 16px;">
									Started at: {{date('d-m-Y', strtotime($paidusermh->active_time))}}<br>
									Due date: {{date('d-m-Y', strtotime($paidusermh->date . '+1 Year'))}}
								</span>
							@endif
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