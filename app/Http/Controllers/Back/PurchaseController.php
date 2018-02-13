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


class PurchaseController extends Controller
{
    /*
		GET THE RESOURCE LIST
	*/
		public function index(Request $request)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->purchase_r != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}

			$data['messageModul'] = false;
			$data['alertModul'] = true;
			$data['searchModul'] = false;
			$data['helpModul'] = true;
			$data['navModul'] = true;
			
			$query = Purchase::query();

			$data['criteria'] = '';

			$status = htmlspecialchars($request->input('status'));
			if ($status != null)
			{
				$query->where('status', '=', $status);
				$data['criteria']['status'] = $status;
			}

			$mh = htmlspecialchars($request->input('src_mh'));
			if ($mh != null)
			{
				$query->where('mh', 'LIKE', '%' . $mh . '%');
				$data['criteria']['src_mh'] = $mh;
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
			$purchases = $query->paginate($per_page);
			$data['purchases'] = $purchases;

			$request->flash();

			$request->session()->put('last_url', URL::full());

			$data['request'] = $request;

	        return view('back.purchase.index', $data);
		}

	/*
		GET THE RESOURCE LIST
	*/
		public function getPending(Request $request)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->purchase_r != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}

			$data['messageModul'] = false;
			$data['alertModul'] = true;
			$data['searchModul'] = false;
			$data['helpModul'] = true;
			$data['navModul'] = true;
			
			$query = Purchase::query()->where('status', '=', 'Waiting for Payment');

			$data['criteria'] = '';

			$status = htmlspecialchars($request->input('status'));
			if ($status != null)
			{
				$query->where('status', '=', $status);
				$data['criteria']['status'] = $status;
			}

			$mh = htmlspecialchars($request->input('src_mh'));
			if ($mh != null)
			{
				$query->where('mh', 'LIKE', '%' . $mh . '%');
				$data['criteria']['src_mh'] = $mh;
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
			$purchases = $query->paginate($per_page);
			$data['purchases'] = $purchases;

			$request->flash();

			$request->session()->put('last_url', URL::full());

			$data['request'] = $request;

	        return view('back.purchase.pending', $data);
		}

	


	/*
		SHOW A RESOURCE
	*/
		public function show(Request $request, $id)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->purchase_r != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/purchase')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}

			$data['messageModul'] = false;
			$data['alertModul'] = true;
			$data['searchModul'] = false;
			$data['helpModul'] = true;
			$data['navModul'] = true;
			
			$purchase = Purchase::find($id);
			if ($purchase != null)
			{
				$data['request'] = $request;
				
				$data['purchase'] = $purchase;
		        return view('back.purchase.view', $data);
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/purchase')->with('error-message', "Can't find Purchase with ID " . $id);
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
			if ($admingroup->purchase_d != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}
			
			$purchase = Purchase::find($id);
			if ($purchase != null)
			{
				$checkusermh = Usermh::where('purchase_id', '=', $id)->first();

				if($checkusermh != null)
				{
					return redirect($request->session()->get('last_url'))->with('error-message', "Can't delete Purchase <strong>" . Str::words($purchase->name, 5) . "</strong>, because this purchase is in use in other data");
				}
					
				$purchase->delete();

	            if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "Purchase <strong>" . Str::words($purchase->name, 5) . "</strong> has been Deleted");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/purchase')->with('success-message', "Purchase <strong>" . Str::words($purchase->name, 5) . "</strong> has been Deleted");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/purchase')->with('error-message', "Can't find Purchase with ID " . $id);
			}
		}
}