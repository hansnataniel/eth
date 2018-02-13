<?php
	use Illuminate\Support\Str;

	use App\Models\Admin;
?>

@extends('back.template.master')

@section('title')
	Contact View
@endsection

@section('head_additional')
	{!!HTML::style('css/back/detail.css')!!}
@endsection

@section('js_additional')
	<script type="text/javascript">
		$(document).ready(function(){
			
		});
	</script>
@endsection

@section('page_title')
	Contact View
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/contact')}}">Contact</a> / <span>Contact View</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Anda dapat melihat detail dari pembelian MH dihalaman ini
		</li>
	</ul>
@endsection

@section('content')
	<div class="page-group">
		<div class="page-item col-1">
			<div class="page-item-content">
				<h1 class="view-title">
					@if($request->session()->has('last_url'))
						<a class="view-button-item view-button-back" href="{{URL::to($request->session()->get('last_url'))}}"></a>
					@else
						<a class="view-button-item view-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/contact')}}"></a>
					@endif
					{{$contact->name}}
				</h1>
				
				@if (file_exists(public_path() . '/usr/img/contact/' . $contact->id . '_' . Str::slug($contact->name, '_') . '_thumb.jpg'))
					{!!HTML::image('usr/img/contact/' . $contact->id . '_' . Str::slug($contact->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'view-photo'])!!}
				@endif
				<div class="page-group">
					<div class="page-item col-1">
						<div class="page-item-title">
							Information Detail
						</div>
						<div class="page-item-content view-item-content">
							<table class="view-detail-table">
								<tr>
									<td>
										Email
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{$contact->email}}
									</td>
								</tr>
								<tr>
									<td>
										Phone
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{$contact->phone}}
									</td>
								</tr>
								<tr>
									<td>
										Subject
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{$contact->subject}}
									</td>
								</tr>
								<tr>
									<td>
										Message
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{nl2br($contact->subject)}}
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="view-last-edit">

					<div class="page-item-title" style="margin-bottom: 20px;">
						Basic Information
					</div>

					<div class="view-last-edit-group">
						<div class="view-last-edit-title">
							Create
						</div>
						<div class="view-last-edit-item">
							<span>
								Created at
							</span>
							<span>
								:
							</span>
							<span>
								{{date('l, d F Y G:i:s', strtotime($contact->created_at))}}
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection