<?php
	use Illuminate\Support\Str;

	use App\Models\Admin;
?>

@extends('back.template.master')

@section('title')
	Purchase View
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
	Purchase View
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/purchase')}}">Purchase</a> / <span>Purchase View</span>
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
						<a class="view-button-item view-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/purchase')}}"></a>
					@endif
					{{$purchase->name}}
				</h1>
				
				@if (file_exists(public_path() . '/usr/img/purchase/' . $purchase->id . '_' . Str::slug($purchase->name, '_') . '_thumb.jpg'))
					{!!HTML::image('usr/img/purchase/' . $purchase->id . '_' . Str::slug($purchase->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'view-photo'])!!}
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
										No. Nota
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{$purchase->no_nota}}
									</td>
								</tr>
								<tr>
									<td>
										User
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{$purchase->user->name}}
									</td>
								</tr>
								<tr>
									<td>
										Purchase Date
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{date('d-m-Y', strtotime($purchase->created_at))}}
									</td>
								</tr>
								<tr>
									<td>
										Status
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										@if($purchase->status == 'Waiting for Payment')
											<span style="color: orange;">{{$purchase->status}}</span>
										@elseif($purchase->status == 'Declined')
											<span style="color: red;">{{$purchase->status}}</span>
										@else
											<span style="color: green;">{{$purchase->status}}</span>
										@endif
									</td>
								</tr>
								@if($purchase->status == 'Paid')
									<tr>
										<td>
											Active Date
										</td>
										<td class="view-info-mid">
											:
										</td>
										<td>
											{{date('d-m-Y', strtotime($purchase->date))}}
										</td>
									</tr>
									<tr>
										<td>
											Due Date
										</td>
										<td class="view-info-mid">
											:
										</td>
										<td>
											{{date('d-m-Y', strtotime($purchase->date))}}
										</td>
									</tr>
								@endif
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
								{{date('l, d F Y G:i:s', strtotime($purchase->created_at))}}
							</span>
						</div>
					</div>

					@if($purchase->status == 'Confirmed')
						<?php
							$confirmuser = Admin::find($purchase->confirm_by);
						?>
						<div class="view-last-edit-group">
							<div class="view-last-edit-title">
								Confirm
							</div>
							<div class="view-last-edit-item">
								<span>
									Confirmed at
								</span>
								<span>
									:
								</span>
								<span>
									{{date('l, d F Y G:i:s', strtotime($purchase->confirm_at))}}
								</span>
							</div>
							<div class="view-last-edit-item">
								<span>
									Last Confirmed by
								</span>
								<span>
									:
								</span>
								<span>
									{{$confirmuser->name}}
								</span>
							</div>
						</div>
					@endif

					@if($purchase->status == 'Declined')
						<?php
							$declineuser = Admin::find($purchase->decline_by);
						?>
						<div class="view-last-edit-group">
							<div class="view-last-edit-title">
								Decline
							</div>
							<div class="view-last-edit-item">
								<span>
									declined at
								</span>
								<span>
									:
								</span>
								<span>
									{{date('l, d F Y G:i:s', strtotime($purchase->decline_at))}}
								</span>
							</div>
							<div class="view-last-edit-item">
								<span>
									Last Declined by
								</span>
								<span>
									:
								</span>
								<span>
									{{$declineuser->name}}
								</span>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection