<?php
	use App\Models\Setting;
?>

@extends('front.template.master')

@section('title')
	Forgot Password
@endsection

@section('head_additional')
	{!!HTML::style('css/front/remind.css')!!}
@endsection

@section('js_additional')
	
@endsection

@section('content')
	<div class="content-container">
		<h1 class="content-title" style="font-size: 26px;">
			RESET YOUR PASSWORD
			<span>
				Fill this form to reset your password
			</span>
		</h1>
		
		<div class="content-group">
			<div class="validation-container">
				@foreach ($errors->all() as $error)
					<div class='validation-message'>
						{{$error}}
					</div>
				@endforeach
			</div>
				
			{!!Form::open(['url' => URL::current(), 'method'=>'POST', 'class'=>'content-form'])!!}
				{!!Form::email('old_email', null, ['class'=>'content-text mh-text', 'placeholder'=>'Old Email', 'required', 'autofocus'])!!}
				{!!Form::password('new_password', ['class'=>'content-text', 'placeholder'=>'New Password'])!!}
				{!!Form::password('new_password_confirmation', ['class'=>'content-text', 'placeholder'=>'New Password Confirmation', 'style'=>'margin-bottom: 30px;'])!!}
				{!!Form::submit('SEND', ['class'=>'content-submit'])!!}
			{!!Form::close()!!}
		</div>
	</div>
@endsection