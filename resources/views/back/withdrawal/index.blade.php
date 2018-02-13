<?php
	use Illuminate\Support\Str;
?>

@extends('back.template.master')

@section('title')
	Withdrawal
@endsection

@section('head_additional')
	{!!HTML::style('css/back/index.css')!!}
@endsection

@section('js_additional')
	<script type="text/javascript">
		$(document).ready(function(){
			$('.index-action-switch').click(function(e){
				e.stopPropagation();
				
				if($(this).hasClass('active'))
				{
					indexSwitchOff();
				}
				else
				{
					indexSwitchOff();

					$(this).addClass('active');
					$(this).find($('.index-action-child-container')).fadeIn();

					$(this).find($('li')).each(function(e){
						$(this).delay(50*e).animate({
		                    opacity: 1,
		                    top: 0
		                }, 300);
					});
				}
			});

			$('.index-del-switch').click(function(){
				$('.pop-result').html($(this).parent().parent().parent().find('.index-del-content').html());

				$('.pop-container').fadeIn();
				$('.pop-container').find('.index-del-item').each(function(e){
					$(this).delay(70*e).animate({
	                    opacity: 1,
	                    top: 0
	                }, 300);
				});
			});
		});
	</script>
@endsection

@section('page_title')
	Withdrawal
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <span>Withdrawal</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Gunakan tombol View di dalam tombol Action untuk melihat detail dari Withdrawal
		</li>
		<li>
			Gunakan tombol Confirm di dalam tombol Action untuk mengkonfirmasi Withdrawal
		</li>
		<li>
			Gunakan tombol Decline di dalam tombol Action untuk mendecline Withdrawal
		</li>
	</ul>
@endsection

@section('search')
	{!!Form::open(['URL' => URL::current(), 'method' => 'GET'])!!}
		<div class="menu-group">
			<div class="menu-title">
				Search by
			</div>
			<div class="menu-search-group">
				{!!Form::label('src_date', 'Date', ['class'=>'menu-search-label'])!!}	
				{!!Form::text('src_date', '', ['class'=>'menu-search-text datetimepicker', 'readonly'])!!}
			</div>
		</div>

		<div class="menu-group">
			<div class="menu-title">
				Sort by
			</div>
			<div class="menu-search-group">
				{!!Form::select('order_by', ['id'=>'Input Time', 'name'=>'Name'], null, ['class'=>'menu-search-text select'])!!}
			</div>
			<div class="menu-search-group">
				<div class="menu-search-radio-group">
					{!!Form::radio('order_method', 'asc', true, ['class'=>'menu-search-radio'])!!}
					{!!HTML::image('img/admin/sort1.png', '', ['menu-class'=>'search-radio-image'])!!}
				</div>
				<div class="menu-search-radio-group">
					{!!Form::radio('order_method', 'desc', false, ['class'=>'menu-search-radio'])!!}
					{!!HTML::image('img/admin/sort2.png', '', ['class'=>'menu-search-radio-image'])!!}
				</div>
			</div>
		</div>
		<div class="menu-group">
			{!!Form::submit('Search', ['class'=>'menu-search-button'])!!}
		</div>
	{!!Form::close()!!}
@endsection

@section('content')
	<div class="page-group">
		<div class="page-item col-1">
			<div class="page-item-content">
				<div class="index-desc-container">
					<a class="index-desc-item index-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">
						<span>
							Back
						</span>
					</a>

					<span class="index-desc-count">
						{{$records_count}} record(s) found
					</span>
				</div>
				<table class="index-table">
					<tr class="index-tr-title">
						<th>
							#
						</th>
						<th>
							User
						</th>
						<th>
							Type
						</th>
						<th>
							Amount
						</th>
						<th>
							Request Date
						</th>
						<th>
							Status
						</th>
						<th>
						</th>
					</tr>
					<?php
						if ($request->has('page'))
						{
							$counter = ($request->input('page')-1) * $per_page;
						}
						else
						{
							$counter = 0;
						}
					?>
					@foreach ($withdrawals as $withdrawal)
						<?php $counter++; ?>
						<tr>
							<td>
								{{$counter}}
							</td>
							<td>
								{{$withdrawal->user->name}}
							</td>
							<td>
								@if($withdrawal->amount_idr != null)
									IDR Withdrawal
								@else
									Eter Withdrawal
								@endif
							</td>
							<td>
								@if($withdrawal->amount_idr != null)
									IDR {{number_format($withdrawal->amount_idr)}}
								@else
									Eter {{$withdrawal->amount_eter}}
								@endif
							</td>
							<td>
								{{tanggal2($withdrawal->created_at)}}
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
							<td class="index-td-icon">
								<div class="index-action-switch">
									{{-- 
										Switch of ACTION
									 --}}
									<span>
										Action
									</span>
									<div class="index-action-arrow"></div>

									{{-- 
										List of ACTION
									 --}}
									<ul class="index-action-child-container" style="width: 110px">
										<li>
											<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/withdrawal/' . $withdrawal->id)}}">
												{!!HTML::image('img/admin/index/detail_icon.png')!!}
												<span>
													View
												</span>
											</a>
										</li>
										@if($withdrawal->status == 'Waiting for Confirmation')
											<li>
												<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/withdrawal/confirm/' . $withdrawal->id)}}">
													{!!HTML::image('img/admin/index/edit_icon.png')!!}
													<span>
														Confirm
													</span>
												</a>
											</li>
											<li>
												<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/withdrawal/decline/' . $withdrawal->id)}}">
													{!!HTML::image('img/admin/index/edit_icon.png')!!}
													<span>
														Decline
													</span>
												</a>
											</li>
										@endif
									</ul>
								</div>
							</td>
						</tr>
					@endforeach
				</table>

				{{-- 
					Pagination
				 --}}
				{{$withdrawals->appends($criteria)->links()}}
			</div>
		</div>
	</div>
@endsection