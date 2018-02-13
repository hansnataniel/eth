@extends('front.template.master')

@section('title')
	CLOUD MINING
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

				if($('.mh-text').val() <= 0)
				{
					$('.msg-content').html($('.hide_0').html());
				}
				else
				{
					$('.msg-content').html($('.hide').html());
				}

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
			CLOUD MINING
		</h1>
		
		<div class="content-group">
			<div class="validation-container">
				@foreach ($errors->all() as $error)
					<div class='validation-message'>
						{{$error}}
					</div>
				@endforeach
			</div>
				
			{!!Form::model($usermh, ['url' => URL::current(), 'method'=>'POST', 'class'=>'content-form'])!!}
				{{-- {!!Form::select('type', $type_options, null, ['class'=>'content-text select'])!!} --}}
				{{-- {!!Form::select('mh', $mhs, null, ['class'=>'content-text select',])!!} --}}
				{!!Form::input('number', 'mh', null, ['class'=>'content-text mh-text', 'placeholder'=>'Type MH here', 'required'])!!}
				{!!Form::submit('PURCHASE', ['class'=>'content-submit'])!!}
			{!!Form::close()!!}

			<div class="hide_0" style="display: none;">
				<span style="position: relative; display: block; text-align: center; margin-bottom: 20px;">
					MH field must be greater than 0
				</span>
				<div class="hide-button cancel" style="position: relative; display: block; margin: auto;">
					CANCEL
				</div>
			</div>
			<div class="hide">
				<div class="hide-group">
					<div class="hide-item">
						Price per MH
					</div>
					<div class="hide-item separator">
						:
					</div>
					<div class="hide-item">
						IDR {{number_format($setting->mh_price)}}
						<span class="per-mh" style="display: none;">
							{{$setting->mh_price}}
						</span>
					</div>
				</div>
				<div class="hide-group">
					<div class="hide-item">
						MH Amount
					</div>
					<div class="hide-item separator">
						:
					</div>
					<div class="hide-item mh-buy">
						
					</div>
				</div>
				<div class="hide-group">
					<div class="hide-item">
						Amount to Pay
					</div>
					<div class="hide-item separator">
						:
					</div>
					<div class="hide-item total">
						
					</div>
				</div>

				<div class="hide-button-group">
					<div class="hide-button cancel">
						CANCEL
					</div>
					<div class="hide-button submit">
						NEXT
					</div>
				</div>
			</div>
		</div>

		<div class="content-history-container">
			<a class="content-history first" href="{{ url('subscription/history') }}" style="display: inline-block; vertical-align: top; margin-right: 40px;">
				CLOUD MINING HISTORY
			</a>

			<a class="content-history second" href="{{ url('contact-us') }}" style="display: inline-block; vertical-align: top;">
				PURCHASE DEDICATED MACHINE
			</a>
		</div>
	</div>
@endsection