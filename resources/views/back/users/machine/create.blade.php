<?php
	use Illuminate\Support\Str;

	use App\Models\User;
	use App\Models\Machine;
?>

@extends('back.template.master')

@section('title')
	New Machine
@endsection

@section('head_additional')
	{!!HTML::style('css/back/edit.css')!!}
@endsection

@section('js_additional')
	<script type="text/javascript">
		$(function(){
			
		});
	</script>
@endsection

@section('page_title')
	New Machine
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user')}}">User</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user/machine/' . $id)}}">Machine</a> / <span>New Machine</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Field <i>Machine ID</i> digunakan untuk mengenali machine berdasarkan ID
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
					<a class="edit-button-item edit-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user/machine/' . $id)}}">
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
				{!!Form::model($machine, ['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/user/machine-create/' . $id), 'method' => 'POST', 'files' => true])!!}
					<div class="page-group">
						<div class="page-item col-1">
							<div class="page-item-title">
								Detail Information
							</div>
							<div class="page-item-content edit-item-content">
								<?php
									$lastmachine = Machine::orderBy('id', 'desc')->first();
									if($lastmachine == null)
									{
										$no_nota = 'M/' . date('ymd') . '/1001';
									}
									else
									{
										$no_nota = 'M/' . date('ymd') . '/' . ($lastmachine->id + 1001);
									}
								?>
								<div class="edit-form-group">
									{!!Form::label('machine_id', 'Machine ID', ['class'=>'edit-form-label'])!!}
									{!!Form::text('machine_id', $no_nota, ['class'=>'edit-form-text large', 'readonly', 'required', 'style'=>'font-size: 18px; padding: 0px; border: 0px !important; font-weight: bold;'])!!}
								</div>
								<div class="edit-form-group">
									{!!Form::label('mh', 'MH', ['class'=>'edit-form-label'])!!}
									{!!Form::text('mh', null, ['class'=>'edit-form-text large', 'required'])!!}
									<span class="edit-form-note">
										*Required, Mumeric
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