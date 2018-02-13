<?php

/*
	Use Name Space Here
*/
namespace App\Http\Controllers\Front;

/*
	Call Model Here
*/
use App\Models\Setting;
use App\Models\User;
use App\Models\Purchase;

/*
	Call Mail file & mail facades
*/
use App\Mail\Front\Subscription;

use Illuminate\Support\Facades\Mail;


/*
	Call Another Function  you want to use
*/
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Validator;
use URL;
use Image;
use Session;


class SubscriptionController extends Controller
{
    /* 
    	GET THE LIST OF THE RESOURCE
    */
	public function index(Request $request)
	{
		$usermh = new Purchase;
		$data['usermh'] = $usermh;

		$getusermhs  = Purchase::where('user_id', '=', Auth::user()->id)->where('status', '=', 'Waiting for Payment')->where('is_active', '=', true)->get();
		$data['getusermhs'] = $getusermhs;

		$paidgetusermhs  = Purchase::where('user_id', '=', Auth::user()->id)->where('status', '!=', 'Waiting for Payment')->where('is_active', '=', true)->get();
		$data['paidgetusermhs'] = $paidgetusermhs;
		
		$data['request'] = $request;

		$type_options = [
			'' => 'Select Type',
			'1' => 'MH',
			'2' => 'Dedicated Machine'
		];
		$data['type_options'] = $type_options;

		$setting = Setting::first();
		$data['setting'] = $setting;

		$usermhs = Purchase::where('is_active', '=', true)->get();
		$usermhtotal = 0;
		foreach ($usermhs as $usermh) {
			$usermhtotal = $usermhtotal + $usermh->mh;
		}

		$mhs[''] = 'Select MH';

		if($setting->totalmh > 0)
		{
			if(!$usermhs->isEmpty())
			{
				$gettotalmh = ($setting->totalmh - $usermhtotal) / 100;
			}
			else
			{
				$gettotalmh = $setting->totalmh / 100;
			}
				
			for ($i=1; $i <= $gettotalmh; $i++) { 
				$mhs[100 * $i] = 100 * $i;
			}
		}

		$data['mhs'] = $mhs;

        return view('front.subscription.index', $data);
	}

	public function store(Request $request)
	{
		$inputs = $request->all();
		$rules = array(
			// 'type' 				=> 'required',
			'mh' 			=> 'required|min:0',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$type = htmlspecialchars($request->input('type'));

			$getmh = htmlspecialchars($request->input('mh'));
			if($getmh <= 0)
			{
				return redirect('subscription')->withErrors("MH field must be greater than 0");
			}
				
			$lastpurchase = Purchase::orderBy('id', 'desc')->first();
			if($lastpurchase == null)
			{
				$no_nota = 'S/' . date('ymd') . '/1001';
			}
			else
			{
				$no_nota = 'S/' . date('ymd') . '/' . ($lastpurchase->id + 1001);
			}

			// if($type == '1')
			// {
				$usermh = new Purchase;
				$usermh->no_nota = $no_nota;
				$usermh->user_id = Auth::user()->id;
				$usermh->mh = htmlspecialchars($request->input('mh'));
				$usermh->date = '0000-00-00';
				$usermh->status = 'Waiting for Payment';
				$usermh->is_active = true;
				$usermh->save();
			// }
			// else
			// {
			// 	$usermh = new Purchase;
			// 	$usermh->no_nota = $no_nota;
			// 	$usermh->user_id = Auth::user()->id;
			// 	$usermh->mh = htmlspecialchars($request->input('mh'));
			// 	$usermh->date = '0000-00-00';
			// 	$usermh->status = 'Waiting for Payment';
			// 	$usermh->is_active = true;
			// 	$usermh->save();
			// }

			$user = User::find(Auth::user()->id);
			$subject = "Subscription Confirmation";

			Mail::to($user->email)
			    ->send(new Subscription($subject, $user, $usermh));

			return redirect('subscription')->with('success-message', "Your MH purchase has been succeed<br> Please make a payment and confirm your payment");
		}
		else
		{
			return redirect('subscription')->withInput()->withErrors($validator);
		}
	}

	public function getHistory(Request $request)
	{
		$usermh = new Purchase;
		$data['usermh'] = $usermh;

		$getusermhs  = Purchase::where('user_id', '=', Auth::user()->id)->where('status', '=', 'Waiting for Payment')->where('is_active', '=', true)->get();
		$data['getusermhs'] = $getusermhs;

		$paidgetusermhs  = Purchase::where('user_id', '=', Auth::user()->id)->where('status', '!=', 'Waiting for Payment')->where('is_active', '=', true)->get();
		$data['paidgetusermhs'] = $paidgetusermhs;
		
		$data['request'] = $request;

		$type_options = [
			'' => 'Select Type',
			'1' => 'MH',
			'2' => 'Dedicated Machine'
		];
		$data['type_options'] = $type_options;

		$setting = Setting::first();
		$data['setting'] = $setting;

		$usermhs = Purchase::where('is_active', '=', true)->get();
		$usermhtotal = 0;
		foreach ($usermhs as $usermh) {
			$usermhtotal = $usermhtotal + $usermh->mh;
		}

		$mhs[''] = 'Select MH';

		if($setting->totalmh > 0)
		{
			if(!$usermhs->isEmpty())
			{
				$gettotalmh = ($setting->totalmh - $usermhtotal) / 100;
			}
			else
			{
				$gettotalmh = $setting->totalmh / 100;
			}
				
			for ($i=1; $i <= $gettotalmh; $i++) { 
				$mhs[100 * $i] = 100 * $i;
			}
		}

		$data['mhs'] = $mhs;

        return view('front.subscription.history', $data);
	}

	public function getDel(Request $request, $id)
	{
		if($id != null)
		{
			$id = str_replace('-', '/', $id);
			$subscription = Purchase::where('no_nota', '=', $id)->first();

			if($subscription == null)
			{
				return redirect('subscription/history')->with('success-message', "Can't find Purchase history with no. nota $id");
			}
				
			$data['id'] = $id;
		}
		$subscription->delete();
		
		return redirect('subscription/history')->with('success-message', "Your purchase has been deleted");
	}
}