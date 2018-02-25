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

		$setting = Setting::first();
		$data['setting'] = $setting;

		$mhs[''] = 'Select MH';

		if($setting->totalmh > 0)
		{
			$availablemh = ($setting->totalmh - $setting->usedmh) / 20;
				
			for ($i=1; $i <= $availablemh; $i++) { 
				$mhs[20 * $i] = 20 * $i;
			}
		}

		$data['mhs'] = $mhs;

        return view('front.subscription.index', $data);
	}

	public function store(Request $request)
	{
		$inputs = $request->all();
		$rules = array(
			'mh' 			=> 'required|min:20',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$type = htmlspecialchars($request->input('type'));

			$getmh = htmlspecialchars($request->input('mh'));
				
			$lastpurchase = Purchase::orderBy('id', 'desc')->first();
			if($lastpurchase == null)
			{
				$no_nota = 'S/' . date('ymd') . '/1001';
			}
			else
			{
				$nomors = explode("/", $lastpurchase->no_nota);
				$last_number = $nomor[2];
				$no_nota = 'S/' . date('ymd') . '/' . ($lastnumber + 1);
			}

			$usermh = new Purchase;
			$usermh->no_nota = $no_nota;
			$usermh->user_id = Auth::user()->id;
			$usermh->mh = htmlspecialchars($request->input('mh'));
			$usermh->active_time = '0000-00-00 00:00:00';
			$usermh->status = 'Waiting for Payment';
			$usermh->is_active = false;
			$usermh->save();

			$user = User::find(Auth::user()->id);
			$subject = "Subscription Confirmation";

			Mail::to($user->email)
			    ->send(new Subscription($subject, $user, $usermh));

			return redirect('subscription')->with('success-message', "Your MH purchase order has been succeed<br> Please make a payment and confirm your payment");
		}
		else
		{
			return redirect('subscription')->withInput()->withErrors($validator);
		}
	}

	public function getHistory(Request $request)
	{
		$usermhs  = Purchase::where('user_id', '=', Auth::user()->id)->get();
		$data['usermhs'] = $usermhs;

		$data['request'] = $request;

		$setting = Setting::first();
		$data['setting'] = $setting;

        return view('front.subscription.history', $data);
	}

	public function getCancel(Request $request, $id)
	{
		if($id != null)
		{
			$id = str_replace('-', '/', $id);
			$subscription = Purchase::where('no_nota', '=', $id)->first();

			if($subscription == null)
			{
				return redirect('subscription/history')->with('success-message', "Can't find Purchase history with Transaction Code $id");
			}
				
			$data['id'] = $id;
			$subscription->status = "Cancelled by Member";
			$subscription->is_active = false;
		}
		
		return redirect('subscription/history')->with('success-message', "Your purchase has been cancelled");
	}
}