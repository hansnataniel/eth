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

use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Bank;
use App\Models\User;


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

use DB;


class PaymentController extends Controller
{

	/*
		GET THE PENDING RESOURCE LIST
	*/
	public function getPending(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->payment_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = false;
		$data['alertModul'] = true;
		$data['searchModul'] = true;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = Payment::query()->where('status', '=', 'Waiting for Confirmation');

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

		$date = htmlspecialchars($request->input('src_date'));
		if ($date != null)
		{
			$query->where('date', '=', $date);
			$data['criteria']['src_date'] = $name;
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
		$query->orderBy('id', 'desc');

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$payments = $query->paginate($per_page);
		$data['payments'] = $payments;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.payment.pending', $data);
	}

    /*
		GET THE RESOURCE LIST
	*/
	public function index(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->payment_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = false;
		$data['alertModul'] = true;
		$data['searchModul'] = true;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = Payment::query();

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

		$date = htmlspecialchars($request->input('src_date'));
		if ($date != null)
		{
			$query->where('date', '=', $date);
			$data['criteria']['src_date'] = $name;
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
		$query->orderBy('id', 'desc');

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$payments = $query->paginate($per_page);
		$data['payments'] = $payments;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.payment.index', $data);
	}

	/*
		SHOW A RESOURCE
	*/
	public function show(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->payment_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/payment')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = false;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$payment = Payment::find($id);
		if ($payment != null)
		{
			$data['request'] = $request;

			$bank = Bank::find($payment->bank_id);
			$data['bank'] = $bank;

			$purchase = Purchase::find($payment->usermh_id);
			$data['purchase'] = $purchase;
			
			$data['payment'] = $payment;
	        return view('back.payment.view', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/payment')->with('error-message', "Can't find Payment with ID " . $id);
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
		if ($admingroup->payment_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		
		$payment = Payment::find($id);
		if ($payment != null)
		{
			$checkusermh = Purchase::where('payment_id', '=', $id)->first();

			if($checkusermh != null)
			{
				return redirect($request->session()->get('last_url'))->with('error-message', "Can't delete Payment <strong>" . Str::words($payment->name, 5) . "</strong>, because this payment is in use in other data");
			}
				
			$payment->delete();

            if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "Payment <strong>" . Str::words($payment->name, 5) . "</strong> has been Deleted");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/payment')->with('success-message', "Payment <strong>" . Str::words($payment->name, 5) . "</strong> has been Deleted");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/payment')->with('error-message', "Can't find Payment with ID " . $id);
		}
	}


	/*
		CONFIRM
	*/
		public function getConfirm(Request $request, $id)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->payment_u != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}

			$payment = Payment::find($id);
			if(($payment == null) OR ($payment->status != 'Waiting for Confirmation'))
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Can't find Payment with ID $id");
			}

			DB::transaction(function() use ($payment) {
				$payment->status = 'Confirmed';
				$payment->confirm_at = date('Y-m-d');
				$payment->confirm_by = Auth::user()->id;
				$payment->save();

				$allpayments = Payment::where('usermh_id', '=', $payment->usermh_id)->get();
				if(!$allpayments->isEmpty())
				{
					foreach ($allpayments as $allpayment) {
						$allpayment->status = 'Confirmed';
						$allpayment->confirm_at = date('Y-m-d');
						$allpayment->confirm_by = Auth::user()->id;
						$allpayment->save();
					}
				}

				$purchase = Purchase::find($payment->usermh_id);
				$purchase->status = 'Paid';
				$purchase->date = date('Y-m-d');
				$purchase->save();

				$user = User::find($purchase->user_id);
				$user->cloudminingmh = $user->cloudminingmh + $purchase->mh;
				$user->save();
			});

			return redirect(Crypt::decrypt($setting->admin_url) . '/payment')->with('success-message', "Payment <strong>" . Str::words($payment->name, 5) . "</strong> has been Confimed");
		}


	/*
		CONFIRM
	*/
		public function getDecline(Request $request, $id)
		{
			$setting = Setting::first();
			$data['setting'] = $setting;

			$admingroup = Admingroup::find(Auth::user()->admingroup_id);
			if ($admingroup->payment_u != true)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
			}

			$payment = Payment::find($id);
			if(($payment == null) OR ($payment->status != 'Waiting for Confirmation'))
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Can't find Payment with ID $id");
			}

			DB::transaction(function() use ($payment) {
				$payment->status = 'Declined';
				$payment->decline_at = date('Y-m-d');
				$payment->decline_by = Auth::user()->id;
				$payment->save();

				$allpayments = Payment::where('usermh_id', '=', $payment->usermh_id)->get();
				if(!$allpayments->isEmpty())
				{
					foreach ($allpayments as $allpayment) {
						$allpayment->status = 'Declined';
						$allpayment->decline_at = date('Y-m-d');
						$allpayment->decline_by = Auth::user()->id;
						$allpayment->save();
					}
				}
			});

			return redirect(Crypt::decrypt($setting->admin_url) . '/payment')->with('success-message', "Payment <strong>" . Str::words($payment->name, 5) . "</strong> has been Declined");
		}
}