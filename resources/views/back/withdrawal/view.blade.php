<?php
	use Illuminate\Support\Str;

	use App\Models\Admin;
?>

@extends('back.template.master')

@section('title')
	Withdrawal View
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
	Withdrawal View
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/withdrawal')}}">Withdrawal</a> / <span>Withdrawal View</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Anda dapat melihat detail dari withdrawal dihalaman ini
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
						<a class="view-button-item view-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/withdrawal')}}"></a>
					@endif
					{{$withdrawal->name}}
				</h1>
				
				@if (file_exists(public_path() . '/usr/img/withdrawal/' . $withdrawal->id . '_' . Str::slug($withdrawal->name, '_') . '_thumb.jpg'))
					{!!HTML::image('usr/img/withdrawal/' . $withdrawal->id . '_' . Str::slug($withdrawal->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'view-photo'])!!}
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
										{{$withdrawal->no_nota}}
									</td>
								</tr>
								<tr>
									<td>
										Type
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										@if($withdrawal->amount_idr != null)
											IDR Withdrawal
										@else
											Eter Withdrawal
										@endif
									</td>
								</tr>
								<tr>
									<td>
										Amount
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										@if($withdrawal->amount_idr != null)
											IDR {{number_format($withdrawal->amount_idr)}}
										@else
											Eter {{$withdrawal->amount_eter}}
										@endif
									</td>
								</tr>
								<tr>
									<td>
										Amount after charge
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{$withdrawal->amount - (($withdrawal->amount * $setting->charge) / 100)}} Binary
									</td>
								</tr>
								<tr>
									<td>
										Transfer Date
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{tanggal2($withdrawal->created_at)}}
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
										@if($withdrawal->status == 'Waiting for Confirmation')
											<span style="color: orange;">{{$withdrawal->status}}</span>
										@elseif($withdrawal->status == 'Declined')
											<span style="color: red;">{{$withdrawal->status}}</span>
										@else
											<span style="color: green;">{{$withdrawal->status}}</span>
										@endif
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
								{{date('l, d F Y G:i:s', strtotime($withdrawal->created_at))}}
							</span>
						</div>
					</div>

					@if($withdrawal->status == 'Confirmed')
						<?php
							$confirmuser = Admin::find($withdrawal->confirm_by);
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
									{{date('l, d F Y G:i:s', strtotime($withdrawal->confirm_at))}}
								</span>
							</div>
							<div class="view-last-edit-item">
								<span>
									Confirmed by
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

					@if($withdrawal->status == 'Declined')
						<?php
							$declineuser = Admin::find($withdrawal->decline_by);
						?>
						<div class="view-last-edit-group">
							<div class="view-last-edit-title">
								Decline
							</div>
							<div class="view-last-edit-item">
								<span>
									Declined at
								</span>
								<span>
									:
								</span>
								<span>
									{{date('l, d F Y G:i:s', strtotime($withdrawal->decline_at))}}
								</span>
							</div>
							<div class="view-last-edit-item">
								<span>
									Declined by
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