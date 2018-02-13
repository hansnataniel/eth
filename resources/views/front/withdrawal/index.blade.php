@extends('front.template.master')

@section('title')
	Withdrawal
@endsection

@section('head_additional')
	
@endsection

@section('content')
	<div class="content-container">
		<h1 class="content-title" style="font-size: 26px;">
			WITHDRAWAL
		</h1>
		
		<div class="content-group">
			<div class="validation-container">
				@foreach ($errors->all() as $error)
					<div class='validation-message'>
						{{$error}}
					</div>
				@endforeach
			</div>
				
			{!!Form::model($withdrawal, ['url' => URL::current(), 'method'=>'POST', 'class'=>'content-form'])!!}
				{!! Form::select('type', $type_options, null, ['class'=>'content-text select']) !!}
				{!!Form::input('number', 'amount', null, ['class'=>'content-text', 'required', 'placeholder'=>'Amount'])!!}
				{!!Form::submit('SEND', ['class'=>'content-submit'])!!}
			{!!Form::close()!!}
		</div>

		<a class="content-history" href="{{ url('withdrawal/history') }}">
			WITHDRAWAL HISTORY
		</a>
	</div>
@endsection