{{-- 
	User Information
 --}}

<div class="menu-group">
	<div class="menu-user menu-link">
		<span>
			Hello!
		</span>
		<span>
			{{Auth::user()->name}}
		</span>
	</div>
	<div class="menu-user-icon-group">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admin/edit-profile')}}" class="menu-user-icon">
			{!!HTML::image('img/admin/edit_profile.png', 'Edit Profile', ['class'=>'menu-user-img'])!!}
			<span>
				Edit Profile
			</span>
		</a>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/logout')}}" class="menu-user-icon logout">
			{!!HTML::image('img/admin/logout.png', 'Sign Out', ['class'=>'menu-user-img'])!!}
			<span>
				Sign Out
			</span>
		</a>
	</div>
</div>


{{-- 
	Navigation goes here
 --}}

<div class="menu-group">
	<div class="menu-title">
		Navigation
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}" class="menu-link-hov">
			Dashboard
		</a>
	</div>

	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/withdrawal')}}" class="menu-link-hov">
			Withdrawal
		</a>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/purchase')}}" class="menu-link-hov">
			Purchase
		</a>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment')}}" class="menu-link-hov">
			Payment
		</a>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/contact')}}" class="menu-link-hov">
			Contact
		</a>
	</div>
</div>


{{-- 
	Preference goes here
 --}}

<div class="menu-group">
	<div class="menu-title">
		Preference
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/bank')}}" class="menu-link-hov">
			Bank
		</a>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/bank/create')}}" class="menu-add"></a>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user')}}" class="menu-link-hov">
			User
		</a>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user/create')}}" class="menu-add"></a>
	</div>
	<div class="menu-link menu-switch">
		<span class="menu-link-hov">
			Admin
		</span>

		<div class="menu-sub-menu-container">
			<div class="menu-sub-menu">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup')}}" class="menu-sub-menu-link">
					Admin Group
				</a>
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup/create')}}" class="menu-add"></a>
			</div>
			<div class="menu-sub-menu">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admin')}}" class="menu-sub-menu-link">
					Admin
				</a>
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admin/create')}}" class="menu-add"></a>
			</div>
		</div>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/setting/edit')}}" class="menu-link-hov">
			Setting
		</a>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/logout')}}" class="menu-link-hov logout">
			Sign Out
		</a>
	</div>
</div>
<div class="menu-group">
	<div class="nav-powered-group menu-link">
		<span>
			Powered by
		</span>
		<a href="http://www.creids.net" class="nav-powered" title="CREIDS" target="_blank">
			{!!HTML::image('img/admin/creids_logo.png', 'CREIDS')!!}
		</a>
	</div>
</div>