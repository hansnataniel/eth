<?php
	use App\Models\Visitorcounter;
?>

@extends('back.template.master')

@section('title')
	Dashboard
@endsection

@section('head_additional')
	{!!HTML::style('css/back/dashboard.css')!!}

	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
        google.charts.load('current', {'packages':['line']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();

            data.addColumn('number', '(Hours)');
            data.addColumn('number', ' ');

            data.addRows([
                @foreach($mininghistories as $mininghistory)
                    [{{date('h', strtotime($mininghistory->created_at))}}, {{$mininghistory->balance}}],
                @endforeach
            ]);

            var options = {
                chart: {
                    title: 'Mining Performance'
                }
            };

            var chart = new google.charts.Line(document.getElementById('curve_chart'));

            chart.draw(data, google.charts.Line.convertOptions(options));
        }
    </script>
@endsection

@section('js_additional')
@endsection

@section('page_title')
	Dashboard
	<span>
		Statistic
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Klik Shortcut yang ada di halaman ini untuk mempercepat menuju halaman yang dituju
		</li>
		<li>
			Gunakan tombol Sign Out di dalam menu atau di halaman dashboard bagian shortcut untuk keluar dari halaman Admin
		</li>
	</ul>
@endsection

@section('content')
	@foreach($mininghistories as $mininghistory)
        <div class="mininghistory-list" hourdata="{{date('H:i', strtotime($mininghistory->created_at))}}" miningdata="{{$mininghistory->balance}}"></div>
    @endforeach
	<div class="page-group">
		<div class="page-item col-1" id="curve_chart" style="height: 500px;">
		</div>
	</div>
	<div class="page-group">
		<div class="page-item col-2-4">
			<div class="page-item-title">
				Navigation Shortcut
			</div>
			<div class="page-item-content dash-short-container">
				<a class="dash-short-item" title="Member" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user')}}">
					<div class="mid">
						{!!HTML::image('img/admin/dashboard/member.png', 'Member', ['class'=>'dash-short-image'])!!}
					</div>
					<div class="dash-short-title">
						User
					</div>
				</a>
				<a class="dash-short-item" title="News" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment')}}">
					<div class="mid">
						{!!HTML::image('img/admin/dashboard/payment_confirmation.png', 'News', ['class'=>'dash-short-image'])!!}
					</div>
					<div class="dash-short-title">
						Payment
					</div>
				</a>
				<a class="dash-short-item" title="Product" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/purchase')}}">
					<div class="mid">
						{!!HTML::image('img/admin/dashboard/purchase.png', 'Product', ['class'=>'dash-short-image'])!!}
					</div>
					<div class="dash-short-title">
						Purchase
					</div>
				</a>
			</div>
		</div>
		<div class="page-item col-2-4">
			<div class="page-item-title">
				Master Shortcut
			</div>
			<div class="page-item-content dash-short-container">
				<a class="dash-short-item" title="Edit Profile" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user/edit-profile')}}">
					<div class="mid">
						{!!HTML::image('img/admin/dashboard/edit_profile.png', 'Member', ['class'=>'dash-short-image'])!!}
					</div>
					<div class="dash-short-title">
						Edit Profile
					</div>
				</a>
				<a class="dash-short-item" title="Setting" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/setting/edit')}}">
					<div class="mid">
						{!!HTML::image('img/admin/dashboard/setting.png', 'Setting', ['class'=>'dash-short-image'])!!}
					</div>
					<div class="dash-short-title">
						Setting
					</div>
				</a>
				<a class="dash-short-item" title="Sign out" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/logout')}}">
					<div class="mid">
						{!!HTML::image('img/admin/dashboard/logout.png', 'Logout', ['class'=>'dash-short-image'])!!}
					</div>
					<div class="dash-short-title">
						Sign out
					</div>
				</a>
			</div>
		</div>
	</div>
@endsection