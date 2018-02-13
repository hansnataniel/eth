<?php
	use Illuminate\Support\Str;

	use App\Models\User;
?>

@extends('back.template.master')

@section('title')
	Slideshow Edit
@endsection

@section('head_additional')
	{!!HTML::style('css/back/edit.css')!!}
@endsection

@section('js_additional')
	
@endsection

@section('page_title')
	New Slideshow
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/slideshow')}}">Slideshow</a> / <span>New Slideshow</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Filename akan digunakan untuk memberi nama pada file gambar yang Anda upload
		</li>
		<li>
			Caption digunakan untuk memberi caption yang akan ditampilkan di front end
		</li>
		<li>
			Field URL harus di isi dengan URL web page, contoh: http://creids.net/contact_us
		</li>
		<li>
			Field URL berfungsi sebagai link ketika Slideshow di klik
		</li>
	</ul>
@endsection

@section('content')
	<div class="page-group">
		<div class="page-item col-1">
			<div class="page-item-content">
				@if($request->session()->has('last_url'))
					<a class="edit-button-item edit-button-back" href="{{URL::to($request->session()->get('last_url'))}}">
						Back
					</a>
				@else
					<a class="edit-button-item edit-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/slideshow')}}">
						Back
					</a>
				@endif

				<div class="page-item-error-container">
					@foreach ($errors->all() as $error)
						<div class='page-item-error-item'>
							{{$error}}
						</div>
					@endforeach
				</div>
				{!!Form::model($slideshow, ['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/slideshow/' . $slideshow->id), 'method' => 'POST', 'files' => true])!!}

					<div class="page-group">
						<div class="page-item col-1">
							<div class="page-item-title">
								Detail Information
							</div>
							<div class="page-item-content edit-item-content">
								<div class="edit-form-group">
									{!!Form::label('filename', 'Filename', ['class'=>'edit-form-label'])!!}
									{!!Form::text('filename', null, ['class'=>'edit-form-text large', 'required', 'autofocus'])!!}
									<span class="edit-form-note">
										*Required
									</span>
								</div>
								<div class="edit-form-group">
									{!!Form::label('caption', 'Caption', ['class'=>'edit-form-label'])!!}
									{!!Form::textarea('caption', null, ['class'=>'edit-form-text large area'])!!}
								</div>
								<div class="edit-form-group">
									{!!Form::label('url', 'URL', ['class'=>'edit-form-label'])!!}
									{!!Form::text('url', null, ['class'=>'edit-form-text large'])!!}
									<span class="edit-form-note">
										*URL Format
									</span>
								</div>
								<div class="edit-form-group">
									{!!Form::label('image', 'Image', ['class'=>'edit-form-label'])!!}									
									{!!Form::file('image', ['class'=>'edit-form-text image'])!!}
									<span class="edit-form-note">
										*Required
									</span>
								</div>
								<div class="edit-form-group">
									{!!Form::label('is_active', 'Active Status', ['class'=>'edit-form-label'])!!}
									<div class="edit-form-radio-group">
										<div class="edit-form-radio-item">
											{!!Form::radio('is_active', 1, true, ['class'=>'edit-form-radio', 'id'=>'true'])!!} 
											{!!Form::label('true', 'Active', ['class'=>'edit-form-radio-label'])!!}
										</div>
										<div class="edit-form-radio-item">
											{!!Form::radio('is_active', 0, false, ['class'=>'edit-form-radio', 'id'=>'false'])!!} 
											{!!Form::label('false', 'Not Active', ['class'=>'edit-form-radio-label'])!!}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="page-group">
						<div class="edit-button-group">
							{{Form::submit('Save', ['class'=>'edit-button-item'])}}
							{{Form::reset('Reset', ['class'=>'edit-button-item reset'])}}
						</div>
					</div>
				{!!Form::close()!!}
			</div>
		</div>
	</div>
@endsection