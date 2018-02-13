@extends('front.template.master')

@section('title')
	Create Account
@endsection

@section('head_additional')
	
@endsection

@section('content')
	<div class="content-container">
		<h1 class="content-title" style="font-size: 26px;">
			CREATE ACCOUNT
		</h1>
		
		<div class="content-group">
			<div class="validation-container">
				@foreach ($errors->all() as $error)
					<div class='validation-message'>
						{{$error}}
					</div>
				@endforeach
			</div>
			
			{!!Form::model($user, ['url'=>URL::current(), 'class'=>'content-form'])!!}
				{!!Form::text('wallet_id', null, ['class'=>'content-text', 'placeholder'=>'Wallet ID'])!!}
				{!!Form::email('email', null, ['class'=>'content-text', 'placeholder'=>'Email'])!!}
				{!!Form::text('name', null, ['class'=>'content-text', 'placeholder'=>'Name'])!!}
				{!!Form::text('phone', null, ['class'=>'content-text', 'placeholder'=>'Phone'])!!}
				{!!Form::password('password', ['class'=>'content-text', 'placeholder'=>'Password'])!!}
				{!!Form::password('password_confirmation', ['class'=>'content-text', 'placeholder'=>'Confirm Your Password', 'style'=>'margin-bottom: 30px;'])!!}
				{!!Form::submit('CREATE ACCOUNT', ['class'=>'content-submit'])!!}
			{!!Form::close()!!}
		</div>
	</div>
@endsection