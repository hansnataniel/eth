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
use App\Models\Usermininghistory;
use App\Models\Machine;
use App\Models\Machinemininghistory;
use App\Models\Mininghistory;


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


class DashboardController extends Controller
{
    /* 
    	GET THE LIST OF THE RESOURCE
    */
		public function index(Request $request)
		{
			$data['request'] = $request;

			$checkmhs = Purchase::where('user_id', '=', Auth::user()->id)->where('is_active', '=', true)->where('status', '=', 'Paid')->get();
			$checkmachines = Machine::where('user_id', '=', Auth::user()->id)->where('is_active', '=', true)->get();
			if((!$checkmhs->isEmpty()) AND (!$checkmachines->isEmpty()))
			{
		        return view('front.dashboard.index', $data);
			}
			else
			{
				if((!$checkmhs->isEmpty()) AND ($checkmachines->isEmpty()))
				{
					return redirect('dashboard/mh');
				}
				if(($checkmhs->isEmpty()) AND (!$checkmachines->isEmpty()))
				{
					return redirect('dashboard/machine');
				}
				if(($checkmhs->isEmpty()) AND ($checkmachines->isEmpty()))
				{
					return redirect('/');
				}
			}
		}

	/*
		Get MH list
	*/
		public function getMh(Request $request)
		{
			$data['request'] = $request;

			$usermininghistories = Usermininghistory::where('user_id', '=', Auth::user()->id)->where('is_active', '=', true)->take(24)->get();
			$data['usermininghistories'] = $usermininghistories;

			$mininghistories = Mininghistory::orderBy('id', 'desc')->get();
			$data['mininghistories'] = $mininghistories;

	        return view('front.dashboard.mh', $data);
		}

	/*
		Get Machine list
	*/
		public function getMachine(Request $request)
		{
			$machines = Machine::where('user_id', '=', Auth::user()->id)->where('is_active', '=', true)->get();
			$data['machines'] = $machines;

			$data['request'] = $request;

	        return view('front.dashboard.machine', $data);
		}

	/*
		Get Machine list
	*/
		public function getMachineDetail(Request $request, $machine_id)
		{
			$machine_id = str_replace('-', '/', $machine_id);
			$checkmachine = Machine::where('machine_id', '=', $machine_id)->first();

			if($checkmachine == null)
			{
				return redirect('dashboard')->with('success-message', "Can't find Machine with ID $machine_id");
			}

			$machine = $checkmachine;
			$data['machine'] = $machine;
					
			$machinemininghistories = Machinemininghistory::where('machine_id', '=', $machine->id)->where('is_active', '=', true)->take(24)->get();
			$data['machinemininghistories'] = $machinemininghistories;
			
			$data['request'] = $request;

	        return view('front.dashboard.machinedetail', $data);
		}

	/*
		Ajax Get AVG
	*/
		public function getavg(Request $request)
		{
			$usermininghistory = Usermininghistory::where('user_id', '=', Auth::user()->id)->orderBy('id', 'desc')->first();

			$return = json_encode($usermininghistory);
			return $return;
		}
}