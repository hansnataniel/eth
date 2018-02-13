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

use App\Models\Contact;


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


class ContactController extends Controller
{
    /*
		GET THE RESOURCE LIST
	*/
		public function index(Request $request)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->contact_r != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}

			$data['messageModul'] = false;
			$data['alertModul'] = true;
			$data['searchModul'] = false;
			$data['helpModul'] = true;
			$data['navModul'] = true;
			
			$query = Contact::query();

			$data['criteria'] = '';

			$name = htmlspecialchars($request->input('name'));
			if ($name != null)
			{
				$query->where('name', '=', $name);
				$data['criteria']['name'] = $name;
			}

			$email = htmlspecialchars($request->input('email'));
			if ($email != null)
			{
				$query->where('email', '=', $email);
				$data['criteria']['email'] = $email;
			}

			$subject = htmlspecialchars($request->input('subject'));
			if ($subject != null)
			{
				$query->where('subject', '=', $subject);
				$data['criteria']['subject'] = $subject;
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
			$contacts = $query->paginate($per_page);
			$data['contacts'] = $contacts;

			$request->flash();

			$request->session()->put('last_url', URL::full());

			$data['request'] = $request;

	        return view('back.contact.index', $data);
		}

	/*
		GET THE RESOURCE LIST
	*/
		public function getPending(Request $request)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->contact_r != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}

			$data['messageModul'] = false;
			$data['alertModul'] = true;
			$data['searchModul'] = false;
			$data['helpModul'] = true;
			$data['navModul'] = true;
			
			$query = Contact::query()->where('name', '=', 'Waiting for Payment');

			$data['criteria'] = '';

			$name = htmlspecialchars($request->input('name'));
			if ($name != null)
			{
				$query->where('name', '=', $name);
				$data['criteria']['name'] = $name;
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
			$contacts = $query->paginate($per_page);
			$data['contacts'] = $contacts;

			$request->flash();

			$request->session()->put('last_url', URL::full());

			$data['request'] = $request;

	        return view('back.contact.pending', $data);
		}

	


	/*
		SHOW A RESOURCE
	*/
		public function show(Request $request, $id)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->contact_r != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/contact')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}

			$data['messageModul'] = false;
			$data['alertModul'] = true;
			$data['searchModul'] = false;
			$data['helpModul'] = true;
			$data['navModul'] = true;
			
			$contact = Contact::find($id);
			if ($contact != null)
			{
				$data['request'] = $request;
				
				$data['contact'] = $contact;
		        return view('back.contact.view', $data);
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/contact')->with('error-message', "Can't find Contact with ID " . $id);
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
			if ($admingroup->contact_d != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}
			
			$contact = Contact::find($id);
			if ($contact != null)
			{
				$contact->delete();

	            if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "Contact <strong>" . Str::words($contact->name, 5) . "</strong> has been Deleted");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/contact')->with('success-message', "Contact <strong>" . Str::words($contact->name, 5) . "</strong> has been Deleted");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/contact')->with('error-message', "Can't find Contact with ID " . $id);
			}
		}
}