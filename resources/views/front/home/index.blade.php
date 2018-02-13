@extends('front.template.master')

@section('title')
	Home
@endsection

@section('head_additional')
	{!!HTML::style('css/front/home.css')!!}
@endsection

@section('js_additional')
	{!!HTML::script('js/front/home.js')!!}
@endsection

@section('content')
	<div class="home-container">
		{!!HTML::image('img/front/logo_home_gradient.png', '', ['class'=>'home-logo-gradient'])!!}
		<div class="home-item home-logo"></div>
		<div class="home-item">
			<article class="home-list-item desc">
				Welcome to the ethminingpool.net, the high performance Ethereum Mining Pool. Payouts are instant and you will receive your Ether as soon as you reach your configured payment threshold.
			</article>
			<div class="home-list-item list">
				<span>
					Features
				</span>
				<ul>
					<li>
						Anonymous mining
					</li>
					<li>
						Real time PPLNS payout scheme
					</li>
					<li>
						Accurate hashrate reporting
					</li>
					<li>
						We pay all Ethereum rewards (Blocks, Uncles &amp; Fees)
					</li>
					<li>
						Instant payout
					</li>
					<li>
						Efficient mining engine, low uncle rates
					</li>
					<li>
						Detailed global and per-worker statistics
					</li>
					<li>
						Email notification system, invalid shares warnings
					</li>
					<li>
						1% fee
					</li>
					<li>
						Professional helpdesk
					</li>
				</ul>
			</div>
		</div>
		@if(Auth::guest())
			<a class="home-mining" href="{{ url('register') }}">
				CREATE ACCOUNT
			</a>
		@endif
	</div>
@endsection