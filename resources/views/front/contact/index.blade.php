@extends('front.template.master')

@section('title')
	Contact Us
@endsection

@section('head_additional')
	{!!HTML::style('css/front/contact.css')!!}
@endsection

@section('js_additional')
	
@endsection

@section('content')
	<div class="content-container">
		<h1 class="content-title">
			ANY QUESTION? CONTACT US AT
		</h1>

		@if(($setting->contact_email != null) OR ($setting->phone != null))
			<h2 class="contact-desc">
				@if($setting->contact_email != null)
					info@ethminingpool.net<br>
				@endif
				@if($setting->phone != null)
					+62 811 111 111
				@endif
			</h2>
		@endif
		
		<div class="content-group">
			<div class="validation-container">
				@foreach ($errors->all() as $error)
					<div class='validation-message'>
						{{$error}}
					</div>
				@endforeach
			</div>
				
			{!!Form::model($contact, ['url'=>URL::current(), 'class'=>'content-form'])!!}
				
				{!!Form::text('name', null, ['class'=>'content-text', 'required', 'placeholder'=>'Name'])!!}
				{!!Form::email('email', null, ['class'=>'content-text', 'required', 'placeholder'=>'Email'])!!}
				{!!Form::text('phone', null, ['class'=>'content-text', 'placeholder'=>'Phone'])!!}
				{!!Form::text('subject', null, ['class'=>'content-text', 'required', 'placeholder'=>'Subject'])!!}
				{!!Form::textarea('message', null, ['class'=>'content-text area', 'required', 'placeholder'=>'Message', 'style'=>'margin-bottom: 30px;'])!!}
				{!!Form::submit('SEND', ['class'=>'content-submit'])!!}

			{!!Form::close()!!}
		</div>
	</div>
@endsection