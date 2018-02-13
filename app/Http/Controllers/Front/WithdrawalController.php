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
use App\Models\Withdrawal;


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


class WithdrawalController extends Controller
{
    /* 
    	GET THE LIST OF THE RESOURCE
    */
	public function index(Request $request)
	{
		$withdrawal = new Withdrawal;
		$data['withdrawal'] = $withdrawal;

		$getwithdrawals  = Withdrawal::where('user_id', '=', Auth::user()->id)->orderBy('id', 'desc')->get();
		$data['getwithdrawals'] = $getwithdrawals;

		$type_options = [
			'' => 'Select Type',
			'1' => 'IDR Withdrawal',
			'2' => 'Eter Withdrawal'
		];
		$data['type_options'] = $type_options;
		
		$data['request'] = $request;

        return view('front.withdrawal.index', $data);
	}

	public function store(Request $request)
	{
		$setting = Setting::first();

		$inputs = $request->all();
		$rules = array(
			'type' 			=> 'required',
			'amount' 			=> 'required|numeric|min:' . $setting->minfee,
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$lastwithdrawal = Withdrawal::orderBy('id', 'desc')->first();
			if($lastwithdrawal == null)
			{
				$no_nota = 'W/' . date('ymd') . '/1001';
			}
			else
			{
				$no_nota = 'W/' . date('ymd') . '/' . ($lastwithdrawal->id + 1001);
			}

			$withdrawal = new Withdrawal;
			$withdrawal->no_nota = $no_nota;
			$withdrawal->user_id = Auth::user()->id;
			if(htmlspecialchars($request->input('type')) == '1')
			{
				$withdrawal->amount_idr = htmlspecialchars($request->input('amount'));
			}
			else
			{
				$withdrawal->amount_eter = htmlspecialchars($request->input('amount'));
			}
			$withdrawal->date = '0000-00-00';
			$withdrawal->status = 'Waiting for Confirmation';
			$withdrawal->save();

			return redirect('withdrawal')->with('success-message', "Your Withdrawal has been succeed");
		}
		else
		{
			return redirect('withdrawal')->withInput()->withErrors($validator);
		}
	}

	public function getHistory(Request $request)
	{
		$getwithdrawals  = Withdrawal::where('user_id', '=', Auth::user()->id)->orderBy('id', 'desc')->get();
		$data['getwithdrawals'] = $getwithdrawals;
		
		$data['request'] = $request;

        return view('front.withdrawal.history', $data);
	}
}