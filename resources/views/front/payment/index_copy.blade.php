@extends('front.template.master')

@section('title')
	Payment
@endsection

@section('head_additional')
	{!!HTML::style('css/front/registration.css')!!}

	<style>
		.regis-content {
			margin: auto;
			padding: 100px 0px 50px;
		}

		.regis-content {
			max-width: none;
		}

		.regis-group {
			position: relative;
			display: block;
			width: 100%;
			max-width: 920px;
			font-size: 0px;
			margin: auto;
		}

		.regis-group-item {
			position: relative;
			display: inline-block;
			vertical-align: top;
			width: 50%;
		}

		.regis-group-item:first-child {
			padding-right: 30px;
		}

		.regis-group-list {
			position: relative;
			display: block;
			margin-bottom: 20px;
		}

		h2 {
			position: relative;
			display: block;
			font-size: 20px;
			font-weight: normal;
			margin-bottom: 20px;
		}

		h3 {
			position: relative;
			display: block;
			font-size: 16px;
			font-weight: normal;
			margin-bottom: 10px;
		}

		.regis-item-list {
			position: relative;
			display: block;
			font-size: 10px;
			padding: 10px;
			border: 1px solid #d2d2d2;
			border-top: 0px;
			line-height: 18px;
		}

		.regis-item-list:nth-child(2) {
			border-top: 1px solid #d2d2d2;
		}

		.empty {
			position: relative;
			display: block;
			font-size: 14px;
			color: red;
		}

		table {
			position: relative;
			width: 100%;
			font-size: 12px;
		}

		.list-first > tr th, td {
			padding: 5px 10px;
			vertical-align: baseline;
		}

		th {
			border-bottom: double #d2d2d2;
			text-align: left;
		}

		td {
			border-bottom: 1px solid #d2d2d2;
		}

		tr:nth-child(even) {
			background: #f0f0f0;
		}
	</style>
@endsection

@section('js_additional')
	{!!HTML::style('css/jquery.datetimepicker.css')!!}
	{!!HTML::script('js/jquery.datetimepicker.js')!!}
	
	<script>
		$(function(){
		    $('.datetimepicker').datetimepicker({
				timepicker: false,
				format: 'Y-m-d'
			});
		});
	</script>
@endsection

@section('content')
		<div class="regis-content">
			<h1 class="regis-title">
				PAYMENT
			</h1>
			
			<div class="regis-group">
				<div class="regis-group-item">
					<h2>
						Payment History
					</h2>

					@if($paymenthistories->isEmpty())
						<span class="empty">
							You don't have payment history
						</span>
					@endif

					@if(!$paymenthistories->isEmpty())
						<div class="regis-group-list">
							<table class="list-first">
								<tr>
									<th>
										#
									</th>
									<th>
										No Nota
									</th>
									<th>
										Date
									</th>
									<th style="text-align: right;">
										Amount
									</th>
									<th>
										Status
									</th>
								</tr>
								<?php
									$no = 0;
								?>
								@foreach($paymenthistories as $paymenthistory)
									<?php
										$no++;
									?>
									<tr>
										<td>
											{{$no}}
										</td>
										<td>
											{{$paymenthistory->no_nota}}
										</td>
										<td>
											{{date('d-m-Y', strtotime($paymenthistory->date))}}
										</td>
										<td style="text-align: right;">
											{{number_format($paymenthistory->amount)}}
										</td>
										<td>
											{{$paymenthistory->status}}
										</td>
									</tr>
								@endforeach
							</table>
						</div>
					@endif
				</div>

				<div class="regis-group-item">
					<div class="validation-container">
						@foreach ($errors->all() as $error)
							<div class='validation-message'>
								{{$error}}
							</div>
						@endforeach
					</div>
						
					{!!Form::model($payment, [URL::to('payment'), 'class'=>'regis-form'])!!}
						@if(isset($id))
							{!!Form::text('purchase_no_nota', $id, ['class'=>'regis-text', 'required', 'placeholder'=>'Purchase No Nota'])!!}
						@else
							{!!Form::text('purchase_no_nota', null, ['class'=>'regis-text', 'required', 'placeholder'=>'Purchase No Nota'])!!}
						@endif
						{!!Form::select('transfer_to', $bank_options, null, ['class'=>'regis-text'])!!}
						{!!Form::text('name', null, ['class'=>'regis-text', 'required', 'placeholder'=>'Name'])!!}
						{!!Form::email('email', null, ['class'=>'regis-text', 'required', 'placeholder'=>'Email'])!!}
						{!!Form::text('your_bank', null, ['class'=>'regis-text', 'required', 'placeholder'=>'Your Bank'])!!}
						{!!Form::text('account_name', null, ['class'=>'regis-text', 'required', 'placeholder'=>'Account Name'])!!}
						{!!Form::text('account_number', null, ['class'=>'regis-text', 'required', 'placeholder'=>'Account Number'])!!}
						{!!Form::input('number', 'amount', null, ['class'=>'regis-text', 'required', 'placeholder'=>'Amount'])!!}
						{!!Form::text('transfer_at', null, ['class'=>'regis-text datetimepicker', 'required', 'readonly', 'placeholder'=>'Transfer at'])!!}
						{!!Form::submit('CONFIRM', ['class'=>'regis-submit'])!!}
					{!!Form::close()!!}
				</div>
			</div>
		</div>
@endsection