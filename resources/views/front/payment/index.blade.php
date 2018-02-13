@extends('front.template.master')

@section('title')
	Payment
@endsection

@section('head_additional')
	
@endsection

@section('js_additional')
	{!!HTML::style('css/jquery.datetimepicker.css')!!}
	{!!HTML::script('js/jquery.datetimepicker.js')!!}
	
	<script>
		$(function(){
		    $('.datetimepicker').datetimepicker({
				timepicker: false,
				format: 'Y-m-d'
			});
		});
	</script>
@endsection

@section('content')
	<div class="content-container">
		<h1 class="content-title" style="font-size: 26px;">
			CONFIRM YOUR PAYMENT
		</h1>
		
		<div class="content-group">
			<div class="validation-container">
				@foreach ($errors->all() as $error)
					<div class='validation-message'>
						{{$error}}
					</div>
				@endforeach
			</div>
				
			{!!Form::model($payment, [URL::to('payment'), 'class'=>'content-form'])!!}
				@if(isset($id))
					{!!Form::text('transaction_id', $id, ['class'=>'content-text', 'required', 'placeholder'=>'Transaction ID'])!!}
				@else
					{!!Form::text('transaction_id', null, ['class'=>'content-text', 'required', 'placeholder'=>'Transaction ID'])!!}
				@endif
				{!!Form::input('number', 'amount_to_pay', null, ['class'=>'content-text', 'required', 'placeholder'=>'Amount'])!!}
				{!!Form::select('transfer_to', $bank_options, null, ['class'=>'content-text'])!!}
				{{-- {!!Form::text('name', null, ['class'=>'content-text', 'required', 'placeholder'=>'Name'])!!} --}}
				{{-- {!!Form::email('email', null, ['class'=>'content-text', 'required', 'placeholder'=>'Email'])!!} --}}
				{!!Form::text('your_bank_name', null, ['class'=>'content-text', 'required', 'placeholder'=>'Your Bank Name'])!!}
				{!!Form::text('your_account_number', null, ['class'=>'content-text', 'required', 'placeholder'=>'Your Account Number'])!!}
				{!!Form::text('your_account_name', null, ['class'=>'content-text', 'required', 'placeholder'=>'Your Account Name'])!!}
				{!!Form::text('transfer_date', null, ['class'=>'content-text datetimepicker', 'required', 'readonly', 'placeholder'=>'Transfer Date'])!!}
				{!!Form::submit('CONFIRM MY PAYMENT', ['class'=>'content-submit'])!!}
			{!!Form::close()!!}
		</div>

		<a class="content-history" href="{{ url('payment/history') }}">
			PAYMENT HISTORY
		</a>
	</div>
@endsection