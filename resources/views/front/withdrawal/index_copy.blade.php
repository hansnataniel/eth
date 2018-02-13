@extends('front.template.master')

@section('title')
	Withdrawal
@endsection

@section('head_additional')
	{!!HTML::style('css/front/registration.css')!!}

	<style>
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

		.regis-item-list:nth-child(1) {
			border-top: 1px solid #d2d2d2;
		}

		.empty {
			position: relative;
			display: block;
			font-size: 14px;
			color: red;
		}

		.regis-pay {
			position: absolute;
			right: 0px;
			top: 0px;
			padding: 0px 15px;
			font-size: 14px;
			height: 76px;
			line-height: 76px;
			border-left: 1px solid #d2d2d2;
			background: transparent;
			color: #535353;

			-webkit-transition: background 0.4s, color 0.4s;
			-moz-transition: background 0.4s, color 0.4s;
			-ms-transition: background 0.4s, color 0.4s;
			transition: background 0.4s, color 0.4s;
		}

		.regis-pay:hover {
			background: #535353;
			color: #fff;

			-webkit-transition: background 0.4s, color 0.4s;
			-moz-transition: background 0.4s, color 0.4s;
			-ms-transition: background 0.4s, color 0.4s;
			transition: background 0.4s, color 0.4s;
		}
	</style>
@endsection

@section('content')
	<div class="mid">
		<div class="regis-content">
			<h1 class="regis-title">
				WITHDRAWAL
			</h1>
			
			<div class="regis-group">
				<div class="regis-group-item">
					<h2>
						Withdrawal History
					</h2>

					@if($getwithdrawals->isEmpty())
						<span class="empty">
							You don't have withdrawal history
						</span>
					@endif

					@if(!$getwithdrawals->isEmpty())
						<div class="regis-group-list">
							@foreach($getwithdrawals as $getwithdrawal)
								<div class="regis-item-list">
									{{date('d-m-Y', strtotime($getwithdrawal->created_at))}}<br>
									<span style="font-size: 14px;">
										No Nota : {{$getwithdrawal->no_nota}}<br>
										{{$getwithdrawal->amount}} Binary
									</span><br>
									Status: {{$getwithdrawal->status}}
								</div>
							@endforeach
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
						
					{!!Form::model($withdrawal, ['url' => URL::current(), 'method'=>'POST', 'class'=>'regis-form'])!!}
						{!!Form::input('number', 'amount', null, ['class'=>'regis-text select', 'required', 'placeholder'=>'Withdrawal'])!!}
						{!!Form::submit('SEND', ['class'=>'regis-submit'])!!}
					{!!Form::close()!!}
				</div>
			</div>
		</div>
	</div>
@endsection