@extends('front.template.master')

@section('title')
	Sorry, The page you're looking for can not be found
@endsection

@section('head_additional')
	{!!HTML::style('css/front/style.css')!!}
	
@endsection

@section('js_additional')
	
@endsection

@section('content')
	<div class="error-container">
		<div class="error-content">
			<div class="error-group">
				<h1>
					Sorry
				</h1>
				<span>
					The page you're looking for can not be found
				</span>
			</div>
		</div>
	</div>
@endsection