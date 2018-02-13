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
			FORGOT YOUR PASSWORD
			<span>
				Fill this form with your registered email, and we will send a
				confirmation email to change your password.
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
				{!!Form::email('email', null, ['class'=>'content-text mh-text', 'placeholder'=>'Email', 'required', 'autofocus'])!!}
				{!!Form::submit('SEND', ['class'=>'content-submit'])!!}
			{!!Form::close()!!}
		</div>
	</div>
@endsection