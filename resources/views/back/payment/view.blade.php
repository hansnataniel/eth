<?php
	use Illuminate\Support\Str;

	use App\Models\Admin;
?>

@extends('back.template.master')

@section('title')
	Payment View
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
	Payment View
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment')}}">Payment</a> / <span>Payment View</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Anda dapat melihat detail dari payment dihalaman ini
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
						<a class="view-button-item view-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment')}}"></a>
					@endif
					{{$payment->no_nota}}
				</h1>
				
				@if (file_exists(public_path() . '/usr/img/payment/' . $payment->id . '_' . Str::slug($payment->name, '_') . '_thumb.jpg'))
					{!!HTML::image('usr/img/payment/' . $payment->id . '_' . Str::slug($payment->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'view-photo'])!!}
				@endif
				<div class="page-group">
					<div class="page-item col-2-4">
						<div class="page-item-title">
							Information Detail
						</div>
						<div class="page-item-content view-item-content">
							<table class="view-detail-table">
								
								<tr>
									<td>
										Transfer From
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{$payment->bank . ' | ' . $payment->account_number . ' | ' . $payment->account_name}}
									</td>
								</tr>
								<tr>
									<td>
										Transfer To
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{$bank->name . ' | ' . $bank->account_number . ' | ' . $bank->account_name}}
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
										{{tanggal2($payment->date_transfer)}}
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
										@if($payment->status == 'Waiting for Confirmation')
											<span style="color: orange;">{{$payment->status}}</span>
										@elseif($payment->status == 'Declined')
											<span style="color: red;">{{$payment->status}}</span>
										@else
											<span style="color: green;">{{$payment->status}}</span>
										@endif
									</td>
								</tr>
								<tr>
									<td>
										Amount Transfered
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{rupiah($payment->amount)}}
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="page-item col-2-4">
						<div class="page-item-title">
							Purchase Detail
						</div>
						<div class="page-item-content view-item-content">
							<table class="view-detail-table">
								<tr>
									<td>
										Purchase No. Nota
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
								@if($payment->usermh->status == 'Paid')
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
								{{date('l, d F Y G:i:s', strtotime($payment->created_at))}}
							</span>
						</div>
					</div>

					@if($payment->status == 'Confirmed')
						<?php
							$confirmuser = Admin::find($payment->confirm_by);
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
									{{date('l, d F Y G:i:s', strtotime($payment->confirm_at))}}
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

					@if($payment->status == 'Declined')
						<?php
							$declineuser = Admin::find($payment->decline_by);
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
									{{date('l, d F Y G:i:s', strtotime($payment->decline_at))}}
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