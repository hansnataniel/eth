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
use App\Models\Bank;
use App\Models\Payment;


/*
	Call Mail file & mail facades
*/
use App\Mail\Front\Pay;

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


class PaymentController extends Controller
{
    /* 
    	GET THE LIST OF THE RESOURCE
    */
	public function getPayment(Request $request, $id = null)
	{
		if($id != null)
		{
			$id = str_replace('-', '/', $id);
			$checkpurchase = Purchase::where('no_nota', '=', $id)->first();

			if($checkpurchase == null)
			{
				return redirect('subscription')->with('success-message', "Can't find Purchase history with no. nota $id");
			}
				
			$data['id'] = $id;
		}

		$banks = Bank::where('is_active', '=', true)->get();
		$bank_options[''] = 'Transfer to';
		foreach ($banks as $bank) {
			$bank_options[$bank->id] = $bank->name;
		}
		$data['bank_options'] = $bank_options;

		$payment = new Payment;
		$data['payment'] = $payment;

		$paymenthistories = Payment::where('user_id', '=', Auth::user()->id)->get();
		$data['paymenthistories'] = $paymenthistories;

		$data['request'] = $request;

        return view('front.payment.index', $data);
	}

	public function store(Request $request, $id = null)
	{
		$setting = Setting::first();

		$inputs = $request->all();
		$rules = array(
			'transaction_id'	=> 'required',
			'amount_to_pay' 		=> 'required',
			'transfer_to'	=> 'required',
			// 'name' 			=> 'required|regex:/^[A-z ]+$/',
			// 'email' 		=> 'required|email',
			'your_bank_name' 			=> 'required',
			'your_account_name' 			=> 'required',
			'your_account_number' 			=> 'required',
			'transfer_date' 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$lastpayment = Payment::orderBy('id', 'desc')->first();
			if($lastpayment == null)
			{
				$no_nota = 'P/' . date('ymd') . '/1001';
			}
			else
			{
				$no_nota = 'P/' . date('ymd') . '/' . ($lastpayment->id + 1001);
			}

			$usermh = Purchase::where('no_nota', '=', htmlspecialchars($request->input('transaction_id')))->first();
			if($usermh == null)
			{
				return redirect('payment')->withInput()->withErrors("Can't find Transaction with ID '" . htmlspecialchars($request->input('transaction_id')) . "'");
			}

			$payment = new Payment;
			$payment->no_nota = $no_nota;
			$payment->usermh_id = $usermh->id;
			$payment->user_id = Auth::user()->id;
			// $payment->name = htmlspecialchars($request->input('name'));
			// $payment->email = htmlspecialchars($request->input('email'));
			$payment->amount = htmlspecialchars($request->input('amount_to_pay'));
			$payment->date = htmlspecialchars($request->input('transfer_date'));
			$payment->status = 'Waiting for Confirmation';
			$payment->bank_id = htmlspecialchars($request->input('transfer_to'));
			$payment->bank = htmlspecialchars($request->input('your_bank_name'));
			$payment->account_name = htmlspecialchars($request->input('your_account_name'));
			$payment->account_number = htmlspecialchars($request->input('your_account_number'));
			$payment->confirm_at = '0000-00-00';
			$payment->confirm_by = 0;
			$payment->decline_at = '0000-00-00';
			$payment->decline_by = 0;
			$payment->save();

			$subject = "Payment Confirmation";

			Mail::to($setting->sender_email)
			    ->send(new Pay($subject, $payment, $usermh));

			return redirect('payment')->with('success-message', "Your payment has been sent");
		}
		else
		{
			return redirect('payment')->withInput()->withErrors($validator);
		}
	}

	public function getHistory(Request $request)
	{
		$paymenthistories = Payment::where('user_id', '=', Auth::user()->id)->orderBy('id', 'desc')->get();
		$data['paymenthistories'] = $paymenthistories;

		$data['request'] = $request;

        return view('front.payment.history', $data);
	}
}