<?php

/*
	Use Name Space Here
*/
namespace App\Http\Controllers\Back;

/*
	Call Model Here
*/
use App\Models\Setting;
use App\Models\Admingroup;
use App\Models\Que;

use App\Models\User;
use App\Models\Machine;


/*
	Call Another Function  you want to use
*/
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Validator;
use Crypt;
use URL;
use Image;
use Session;
use File;


class UserController extends Controller
{
    /* 
    	GET THE LIST OF THE RESOURCE
    */
	public function index(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->user_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = false;
		$data['alertModul'] = true;
		$data['searchModul'] = true;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = User::query();

		$data['criteria'] = '';

		$name = htmlspecialchars($request->input('src_name'));
		if ($name != null)
		{
			$query->where('name', 'LIKE', '%' . $name . '%');
			$data['criteria']['src_name'] = $name;
		}

		$wallet_id = htmlspecialchars($request->input('src_wallet_id'));
		if ($wallet_id != null)
		{
			$query->where('wallet_id', 'LIKE', '%' . $wallet_id . '%');
			$data['criteria']['src_wallet_id'] = $wallet_id;
		}

		$email = htmlspecialchars($request->input('src_email'));
		if ($email != null)
		{
			$query->where('email', 'LIKE', '%' . $email . '%');
			$data['criteria']['src_email'] = $email;
		}

		$is_suspended = htmlspecialchars($request->input('src_is_suspended'));
		if ($is_suspended != null)
		{
			$query->where('is_suspended', '=', $is_suspended);
			$data['criteria']['src_is_suspended'] = $is_suspended;
		}

		$is_active = htmlspecialchars($request->input('src_is_active'));
		if ($is_active != null)
		{
			$query->where('is_active', '=', $is_active);
			$data['criteria']['src_is_active'] = $is_active;
		}

		$order_by = htmlspecialchars($request->input('order_by'));
		$order_method = htmlspecialchars($request->input('order_method'));
		if ($order_by != null)
		{
			if ($order_by == 'is_active')
			{
				$query->orderBy($order_by, $order_method)->orderBy('name', 'asc');
			}
			else
			{
				$query->orderBy($order_by, $order_method);
			}
			$data['criteria']['order_by'] = $order_by;
			$data['criteria']['order_method'] = $order_method;
		}
		else
		{
			$query->orderBy('name', 'asc');
		}

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$users = $query->paginate($per_page);
		$data['users'] = $users;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.users.index', $data);
	}

	/* 
		CREATE A NEW RESOURCE
	*/
	public function create(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->user_c != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = false;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$user = new User;
		$data['user'] = $user;

		$data['scripts'] = array('js/jquery-ui.js');
        $data['styles'] = array('css/jquery-ui-back.css');

        $data['request'] = $request;

        return view('back.users.create', $data);
	}

	public function store(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'wallet_id' 		=> 'required|unique:users,wallet_id',
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'email' 			=> 'required|email|unique:users,email',
			'password'	 		=> 'required|confirmed|min:6',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$user = new User;
			$user->wallet_id = $request->input('wallet_id');
			$user->name = htmlspecialchars($request->input('name'));
			$user->email = htmlspecialchars($request->input('email'));
			$user->new_password = htmlspecialchars($request->input('password'));
			$user->is_suspended = false;
			$user->is_active = htmlspecialchars($request->input('is_active', 0));

			$user->suspended_by = 0;
			$user->unsuspended_by = 0;

			$user->suspended_at = date('Y-m-d H:i:s');
			$user->unsuspended_at = date('Y-m-d H:i:s');

			$user->created_by = Auth::user()->id;
			$user->updated_by = Auth::user()->id;
			
			$user->save();

			return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('success-message', "User <strong>$user->name</strong> has been Created.");
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/user/create')->withInput()->withErrors($validator);
		}
	}

	/* 
		SHOW A RESOURCE
	*/
	public function show(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->user_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = false;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$user = User::find($id);
		if ($user != null)
		{
			$data['request'] = $request;

			$data['user'] = $user;
	        return view('back.users.view', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any user with ID ' . $id);
		}
	}

	/* 
		EDIT A RESOURCE
	*/
	public function edit(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->user_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = false;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$user = User::find($id);

		if ($user != null)
		{
			// if($user->id == Auth::user()->id)
			// {
				// return redirect(Crypt::decrypt($setting->admin_url) . '/user/edit-profile');
			// }
				
			$data['request'] = $request;

			$data['user'] = $user;

			$data['scripts'] = array('js/jquery-ui.js');
	        $data['styles'] = array('css/jquery-ui-back.css');

	        return view('back.users.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any user with ID ' . $id);
		}
	}

	public function update(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'wallet_id'			=> 'required|unique:users,wallet_id,' . $id,
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'email' 			=> 'required|email|unique:users,email,' . $id,
			'new_password' 		=> 'nullable|confirmed|min:6',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$user = User::find($id);
			if ($user != null)
			{
				$user->wallet_id = $request->input('wallet_id');
				$user->name = htmlspecialchars($request->input('name'));
				$user->email = htmlspecialchars($request->input('email'));
				if ($request->input('new_password') != null) {
					$user->new_password = htmlspecialchars($request->input('new_password'));
				}
				$user->is_active = htmlspecialchars($request->input('is_active', 0));

				$user->updated_by = Auth::user()->id;
				
				$user->save();

				if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "User <strong>$user->name</strong> has been Updated.");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('success-message', "User <strong>$user->name</strong> has been Updated.");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any user with ID ' . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . "/user/$id/edit")->withInput()->withErrors($validator);
		}
	}

	/* 
		DELETE A RESOURCE
	*/
	public function destroy(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->user_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		if ($admingroup->user_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		
		$user = User::find($id);
		if ($user != null)
		{
			$checkusermh = Usermh::where('user_id', '=', $id)->first();
			if ($checkusermh != null)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', "Can't delete user <strong>$user->name</strong>, because this data is in use in other data");
			}

			$user_name = $user->name;
			$user->delete();

			if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "User <strong>$user->name</strong> has been Deleted.");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('success-message', "User <strong>$user->name</strong> has been Deleted.");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any user with ID ' . $id);
		}
	}

	/*
		SUSPENDED USER
	*/
	public function getsuspended($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->user_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$user = User::find($id);

		if ($user != null)
		{
			if (Auth::user()->id == $id)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'You can not delete yourself from your own account');
			}

			if($user->is_suspended == true)
			{
				$getsuspended_time = $user->suspended_at;
				$user->is_suspended = false;

				$user->unsuspended_by = Auth::user()->id;
				$user->unsuspended_at = date('Y-m-d H:i:s');
				$user->suspended_at = $user->suspended_at;

				$user->save();

				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('success-message', "User <strong>$user->name</strong> has been unsuspended");
			}
			else
			{
				$getunsuspended_time = $user->unsuspended_at;
				$user->is_suspended = true;

				$user->suspended_by = Auth::user()->id;
				$user->suspended_at = date('Y-m-d H:i:s');
				$user->unsuspended_at = $getunsuspended_time;

				$user->save();

				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('success-message', "User <strong>$user->name</strong> has been suspended");
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any user with ID ' . $id);
		}
	}

	/*
		List Machine
	*/
		public function getMachine(Request $request, $id)
		{
			// $ini = getData("https://api.nanopool.org/v1/eth/balance/0x9f3d97d8887eb429c0c500d4084f151796212e1e");
			// dd($ini);

			$setting = Setting::first();
			$data['setting'] = $setting;
			
			/*User Authentication*/

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->user_r != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}

			/*Menu Authentication*/

			$data['messageModul'] = false;
			$data['alertModul'] = true;
			$data['searchModul'] = false;
			$data['helpModul'] = true;
			$data['navModul'] = true;

			$user = User::find($id);
			if($user == null)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any user with ID ' . $id);
			}

			$machines = Machine::where('user_id', '=', $user->id)->where('is_active', '=', true)->get();
			$records_count = count($machines);
			$data['records_count'] = $records_count;
			$data['machines'] = $machines;

			$data['user'] = $user;
			$data['request'] = $request;

			$request->session()->put('last_url', URL::full());

			return view('back.users.machine.index', $data);
		}

	/*
		Get Create Machine
	*/
		public function getMachineCreate(Request $request, $id)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;
			
			/*User Authentication*/

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->user_r != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}

			/*Menu Authentication*/

			$data['messageModul'] = false;
			$data['alertModul'] = true;
			$data['searchModul'] = false;
			$data['helpModul'] = true;
			$data['navModul'] = true;

			$user = User::find($id);
			if($user == null)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any user with ID ' . $id);
			}

			$machine = new Machine;
			$data['machine'] = $machine;

			$data['user'] = $user;
			$data['request'] = $request;

			$data['id'] = $id;

			return view('back.users.machine.create', $data);
		}

	/*
		Post Create Machine
	*/
		public function postMachineCreate(Request $request, $id)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;
			
			$inputs = $request->all();
			$rules = array(
				'machine_id' 		=> 'required|unique:machines,machine_id',
				'mh' 				=> 'required|numeric',
			);

			$validator = Validator::make($inputs, $rules);
			if ($validator->passes())
			{
				$getmh = htmlspecialchars($request->input('mh'));
				if($getmh <= 0)
				{
					return redirect(Crypt::decrypt($setting->admin_url) . '/user/machine-create/' . $id)->withInput()->withErrors("MH field must be greater than 0");
				}

				$machine = new Machine;
				$machine->user_id = $id;
				$machine->machine_id = htmlspecialchars($request->input('machine_id'));
				$machine->mh = htmlspecialchars($request->input('mh'));
				$machine->is_active = htmlspecialchars($request->input('is_active', 0));

				$machine->created_by = Auth::user()->id;
				$machine->updated_by = Auth::user()->id;
				
				$machine->save();

				return redirect(Crypt::decrypt($setting->admin_url) . '/user/machine/' . $id)->with('success-message', "Machine <strong>$machine->machine_id</strong> has been Created.");
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user/machine-create/' . $id)->withInput()->withErrors($validator);
			}
		}

	/* 
		SHOW A RESOURCE
	*/
		public function getMachineShow(Request $request, $id)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;
			
			/*User Authentication*/

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->user_r != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}

			/*Menu Authentication*/

			$data['messageModul'] = false;
			$data['alertModul'] = true;
			$data['searchModul'] = false;
			$data['helpModul'] = true;
			$data['navModul'] = true;
			
			$machine = Machine::find($id);
			if ($machine != null)
			{
				$data['request'] = $request;

				$data['machine'] = $machine;
		        return view('back.users.machine.view', $data);
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any machine with ID ' . $id);
			}
		}

	/* 
		EDIT A RESOURCE
	*/
		public function getMachineEdit(Request $request, $id)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;
			
			/*User Authentication*/

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->user_u != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}

			/*Menu Authentication*/

			$data['messageModul'] = false;
			$data['alertModul'] = true;
			$data['searchModul'] = false;
			$data['helpModul'] = true;
			$data['navModul'] = true;
			
			$machine = Machine::find($id);

			if ($machine != null)
			{
				$data['request'] = $request;

				$data['machine'] = $machine;

		        return view('back.users.machine.edit', $data);
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any machine with ID ' . $id);
			}
		}

		public function postMachineEdit(Request $request, $id)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;
			
			$inputs = $request->all();
			$rules = array(
				'machine_id'		=> 'required|unique:machines,machine_id,' . $id,
				'mh' 				=> 'required|numeric',
			);

			$validator = Validator::make($inputs, $rules);
			if ($validator->passes())
			{
				$machine = Machine::find($id);
				if ($machine != null)
				{
					$getmh = htmlspecialchars($request->input('mh'));
					if($getmh <= 0)
					{
						return redirect(Crypt::decrypt($setting->admin_url) . '/user/machine-edit/' . $id)->withInput()->withErrors("MH field must be greater than 0");
					}

					$machine->machine_id = htmlspecialchars($request->input('machine_id'));
					$machine->mh = htmlspecialchars($request->input('mh'));
					$machine->is_active = htmlspecialchars($request->input('is_active', 0));

					$machine->updated_by = Auth::user()->id;
					
					$machine->save();

					if($request->session()->has('last_url'))
		            {
						return redirect($request->session()->get('last_url'))->with('success-message', "Machine <strong>$machine->machine_id</strong> has been Updated.");
		            }
		            else
		            {
						return redirect(Crypt::decrypt($setting->admin_url) . '/user/machine/' . $machine->user_id)->with('success-message', "Machine <strong>$machine->machine_id</strong> has been Updated.");
		            }
				}
				else
				{
					return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any machine with ID ' . $id);
				}
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . "/user/machine-edit/" . $id)->withInput()->withErrors($validator);
			}
		}

	/* 
		DELETE A RESOURCE
	*/
		public function postMachineDelete(Request $request, $id)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;

			/*User Authentication*/

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->user_r != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}
			if ($admingroup->user_d != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}
			
			$machine = Machine::find($id);
			if ($machine != null)
			{
				$machine_name = $machine->name;
				$machine->delete();

				if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "Machine <strong>$machine->machine_id</strong> has been Deleted.");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('success-message', "Machine <strong>$machine->machine_id</strong> has been Deleted.");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any machine with ID ' . $id);
			}
		}
}
