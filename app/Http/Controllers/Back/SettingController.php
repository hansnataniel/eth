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

use Artisan;


class SettingController extends Controller
{
    /* 
    	EDIT A RESOURCE
    */
	public function getEdit(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->setting_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/setting')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = false;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$setting = Setting::first();
		$data['setting'] = $setting;

		$request->session()->put('fav', false);
		$request->session()->put('logo', false);

		$data['request'] = $request;

        return view('back.settings.edit', $data);
	}

	public function postEdit(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'back_session_lifetime'		=> 'required|numeric',
			'front_session_lifetime'		=> 'required|numeric',
			'admin_url'				=> 'required',
			'contact_email'			=> 'required|email',
			'receiver_email'		=> 'required|email',
			'receiver_email_name'		=> 'required',
			'sender_email'			=> 'required|email',
			'sender_email_name'			=> 'required',
			'total_mh'			=> 'required',
			'minimum_fee'			=> 'required',
			'charge'			=> 'required',

			'eter_charge'			=> 'required',
			'idr_charge'			=> 'required',
			'usd_kurs'			=> 'required',
			'mh_price'			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$setting = Setting::first();
			$setting->back_session_lifetime = htmlspecialchars($request->input('back_session_lifetime'));
			$setting->front_session_lifetime = htmlspecialchars($request->input('front_session_lifetime'));
			$setting->visitor_lifetime = htmlspecialchars($request->input('visitor_lifetime'));
			$setting->admin_url = htmlspecialchars(Crypt::encrypt($request->input('admin_url')));
			$setting->google_analytics = $request->input('google_analytics');

			$setting->phone = htmlspecialchars($request->input('phone'));
			$setting->address = htmlspecialchars($request->input('address'));

			$setting->contact_email = htmlspecialchars($request->input('contact_email'));
			$setting->receiver_email = htmlspecialchars($request->input('receiver_email'));
			$setting->receiver_email_name = htmlspecialchars($request->input('receiver_email_name'));
			$setting->sender_email = htmlspecialchars($request->input('sender_email'));
			$setting->sender_email_name = htmlspecialchars($request->input('sender_email_name'));

			$setting->facebook = htmlspecialchars($request->input('facebook'));
			$setting->twitter = htmlspecialchars($request->input('twitter'));
			$setting->instagram = htmlspecialchars($request->input('instagram'));

			$setting->totalmh = htmlspecialchars($request->input('total_mh'));
			$setting->minfee = htmlspecialchars($request->input('minimum_fee'));
			$setting->charge = htmlspecialchars($request->input('charge'));

			$setting->eter_charge = htmlspecialchars($request->input('eter_charge'));
			$setting->idr_charge = htmlspecialchars($request->input('idr_charge'));
			$setting->usd_kurs = htmlspecialchars($request->input('usd_kurs'));
			$setting->mh_price = htmlspecialchars($request->input('mh_price'));
			$setting->cloud_mining_walletid = htmlspecialchars($request->input('cloud_mining_wallet_id'));
			$setting->machine_walletid = htmlspecialchars($request->input('dedicated_machine_wallet_id'));

			$setting->update_by = Auth::user()->id;
			
			$setting->save();

			define('STDIN',fopen("php://stdin","r"));
			if ($setting->maintenance == 1) {
				Artisan::call('down');
				// touch(storage_path().'/meta/my.down');
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('success-message', 'Setting has been updated with Maintenance "ON"');
			} else {
				Artisan::call('up');
				// @unlink(storage_path().'/meta/my.down');
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('success-message', 'Setting has been updated with Maintenance "OFF"');
			}

		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/setting/edit')->withInput()->withErrors($validator);
		}
	}
}