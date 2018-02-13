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

use App\Models\Bank;
use App\Models\Purchase;


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


class BankController extends Controller
{
    /*
		GET THE RESOURCE LIST
	*/
	public function index(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->bank_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = false;
		$data['alertModul'] = true;
		$data['searchModul'] = true;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = Bank::query();

		$data['criteria'] = '';

		$name = htmlspecialchars($request->input('src_name'));
		if ($name != null)
		{
			$query->where('name', 'LIKE', '%' . $name . '%');
			$data['criteria']['src_name'] = $name;
		}

		$account_name = htmlspecialchars($request->input('src_account_name'));
		if ($account_name != null)
		{
			$query->where('account_name', 'LIKE', '%' . $account_name . '%');
			$data['criteria']['src_account_name'] = $account_name;
		}

		$account_number = htmlspecialchars($request->input('src_account_number'));
		if ($account_number != null)
		{
			$query->where('account_number', 'LIKE', '%' . $account_number . '%');
			$data['criteria']['src_account_number'] = $account_number;
		}

		$order_by = htmlspecialchars($request->input('order_by'));
		$order_method = htmlspecialchars($request->input('order_method'));
		if ($order_by != null)
		{
			$query->orderBy($order_by, $order_method);
			$data['order_by'] = $order_by;
			$data['order_method'] = $order_method;
		}
		/* Don't forget to adjust the default order */
		$query->orderBy('order', 'asc');

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$banks = $query->paginate($per_page);
		$data['banks'] = $banks;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.bank.index', $data);
	}

	/*
		CREATE A RESOURCE
	*/
	public function create(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->bank_c != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/bank')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = false;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$bank = new Bank;
		$data['bank'] = $bank;

		$data['request'] = $request;

        return view('back.bank.create', $data);
	}

	public function store(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$inputs = $request->all();
		$rules = array(
			'name'			=> 'required',
			'account_name'				=> 'required',
			'account_number'			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$bank = new Bank;
			$bank->name = htmlspecialchars($request->input('name'));
			$bank->account_name = htmlspecialchars($request->input('account_name'));
			$bank->account_number = htmlspecialchars($request->input('account_number'));
			$bank->is_active = htmlspecialchars($request->input('is_active', false));

			$bank->created_by = Auth::user()->id;
			$bank->updated_by = Auth::user()->id;

			$bank->save();

			return redirect(Crypt::decrypt($setting->admin_url) . '/bank')->with('success-message', "Bank <strong>" . Str::words($bank->name, 5) . "</strong> has been Created");
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/bank/create')->withInput()->withErrors($validator);
		}
	}

	/*
		SHOW A RESOURCE
	*/
	public function show(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->bank_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/bank')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = false;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$bank = Bank::find($id);
		if ($bank != null)
		{
			$data['request'] = $request;
			
			$data['bank'] = $bank;
	        return view('back.bank.view', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/bank')->with('error-message', "Can't find Bank with ID " . $id);
		}
	}


	/*
		EDIT A RESOURCE
	*/
	public function edit(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->bank_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/bank')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = false;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$bank = Bank::find($id);
		
		if ($bank != null)
		{
			$data['request'] = $request;

			$data['bank'] = $bank;

	        return view('back.bank.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/bank')->with('error-message', "Can't find Bank with ID " . $id);
		}
	}

	public function update(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'name'		=> 'required',
			'account_name'		=> 'required',
			'account_number'		=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$bank = Bank::find($id);
			if ($bank != null)
			{
				$name_old = $bank->name;

				$bank->name = htmlspecialchars($request->input('name'));
				$bank->account_name = htmlspecialchars($request->input('account_name'));
				$bank->account_number = htmlspecialchars($request->input('account_number'));
				$bank->is_active = htmlspecialchars($request->input('is_active', false));

				$bank->updated_by = Auth::user()->id;

				$bank->save();

				if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "Bank <strong>" . Str::words($bank->name, 5) . "</strong> has been Updated");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/bank')->with('success-message', "Bank <strong>" . Str::words($bank->name, 5) . "</strong> has been Updated");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/bank')->with('error-message', "Can't find Bank with ID " . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . "/bank/$id/edit")->withInput()->withErrors($validator);
		}
	}


	/*
		DELETE A RESOURCE
	*/
	public function destroy(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->bank_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		
		$bank = Bank::find($id);
		if ($bank != null)
		{
			$checkusermh = Purchase::where('bank_id', '=', $id)->first();

			if($checkusermh != null)
			{
				return redirect($request->session()->get('last_url'))->with('error-message', "Can't delete Bank <strong>" . Str::words($bank->name, 5) . "</strong>, because this bank is in use in other data");
			}
				
			$bank->delete();

            if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "Bank <strong>" . Str::words($bank->name, 5) . "</strong> has been Deleted");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/bank')->with('success-message', "Bank <strong>" . Str::words($bank->name, 5) . "</strong> has been Deleted");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/bank')->with('error-message', "Can't find Bank with ID " . $id);
		}
	}
}