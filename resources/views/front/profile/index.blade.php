@extends('front.template.master')

@section('title')
	Edit Profile
@endsection

@section('head_additional')
	{!!HTML::style('css/front/registration.css')!!}
@endsection

@section('content')
	<div class="content-container">
		<h1 class="content-title" style="font-size: 26px;">
			EDIT PROFILE
		</h1>
		
		<div class="content-group">
			<div class="validation-container">
				@foreach ($errors->all() as $error)
					<div class='validation-message'>
						{{$error}}
					</div>
				@endforeach
			</div>
			
			{!!Form::model($user, ['url' => URL::to('my-profile/' . Auth::user()->id), 'method'=>'PUT', 'class'=>'content-form'])!!}
				{!! Form::label('wallet_id', 'Wallet ID', ['class'=>'content-label']) !!}
				{!!Form::text('wallet_id', null, ['class'=>'content-text', 'required', 'placeholder'=>'Wallet ID'])!!}

				{!! Form::label('name', 'Name', ['class'=>'content-label']) !!}
				{!!Form::text('name', null, ['class'=>'content-text', 'required', 'placeholder'=>'Name'])!!}

				{!! Form::label('email', 'Email', ['class'=>'content-label']) !!}
				{!!Form::email('email', null, ['class'=>'content-text', 'required', 'placeholder'=>'Email'])!!}
				
				{!! Form::label('phone', 'Phone', ['class'=>'content-label']) !!}
				{!!Form::text('phone', null, ['class'=>'content-text', 'placeholder'=>'Phone'])!!}

				{!! Form::label('new_password', 'New Password', ['class'=>'content-label']) !!}
				{!!Form::password('new_password', ['class'=>'content-text', 'placeholder'=>'New Password'])!!}

				{!! Form::label('new_password_confirmation', 'New Password Confirmation', ['class'=>'content-label']) !!}
				{!!Form::password('new_password_confirmation', ['class'=>'content-text', 'placeholder'=>'New Password Confirmation'])!!}
				{!!Form::submit('UPDATE', ['class'=>'content-submit'])!!}
			{!!Form::close()!!}
		</div>
	</div>
@endsection