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
use App\Models\Avg;
use App\Models\Purchase;
use App\Models\Mininghistory;
use App\Models\Usermininghistory;
use App\Models\Machine;
use App\Models\Machinemininghistory;

/*
	Call Mail file & mail facades
*/
use App\Mail\Front\Cron;
use App\Mail\Front\Cronfail;

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
use DB;


class CronController extends Controller
{
    /* 
    	GET THE LIST OF THE RESOURCE
    */
	public function index(Request $request)
	{
		DB::transaction(function(){
			// global $mininghistory;
			// global $avg;
			// global $users;


			$setting = Setting::first();

			/*
				CLOUD MINING
			*/
				// $cloudsendlink = getData("https://api.nanopool.org/v1/eth/balance/" . $setting->cloud_mining_walletid);
				$cloudsendlink = getData("https://api.nanopool.org/v1/eth/user/" . $setting->cloud_mining_walletid);
				
				/*
					Pengecekkan status API
				*/
				if($cloudsendlink["status"] != false)
				{
					$cloudgetbalance = $cloudsendlink["data"]["balance"];

					/*
						Create new record of mining history
					*/
					$mininghistory = new Mininghistory;
					
					$mininghistory->balance_api = $cloudgetbalance;

					/*
						Get last mining history
					*/
					$lastmininghistory = Mininghistory::orderBy('id', 'desc')->first();

					/*
						Pengecekkan data pertama dari table Mininghistories
					*/
					if($lastmininghistory != null)
					{
						if($cloudgetbalance < $lastmininghistory->balance_api)
						{
							$inc = $lastmininghistory->inc + $setting->payout;
						}
						else
						{
							$inc = $lastmininghistory->inc;
						}
						$mininghistory->inc = $inc;

						$balance_real = $cloudgetbalance + $inc;
						$mininghistory->balance_real = $balance_real;
						$selisih_real = $balancereal - $lastmininghistory->balance_real;
						$mininghistory->selisih_real = $selisih_real;
						$mininghistory->balance = $lastmininghistory->balance + ((100 - $setting->charge) / 100 * $selisih_real);
						$mininghistory->selisih = (100 - $setting->charge) / 100 * $selisih_real;
					}
					else
					{
						$mininghistory->inc = 0;
						$mininghistory->balance_real = $cloudgetbalance;
						$mininghistory->selisih_real = $cloudgetbalance;
						$mininghistory->balance = $cloudgetbalance - ($cloudgetbalance * $setting->charge) / 100;
						$mininghistory->selisih = $cloudgetbalance - ($cloudgetbalance * $setting->charge) / 100;
					}
					$mininghistory->save();

					/*
						Create new User Mining History
					*/
					$users = User::where('is_active', '=', true)->where('is_suspended', '=', false)->where(function($qr){
						$qr->where('cloudminingmh', '!=', 0);
						$qr->orWhere('cloudminingmh', '!=', null);	
					})->get();
					if($users != null)
					{
						foreach ($users as $user) {
							$lastusermininghistory = Usermininghistory::where('user_id', '=', $user->id)->orderBy('id', 'desc')->first();

							$usermhs = Usermh::where('user_id', '=', $user->id)->where('status', '=', 'Active')->where('is_active', '=', true)->get();
							$uncountmh = 0;
							foreach ($usermhs as $usermh)
							{
								if (strtotime($usermh->active_time) > date("Y-m-d H:i:s", strtotime('-1 hour')))
								{
									$uncountmh = $uncountmh + $usermh->mh;
								}
							}

							$userminingselisih = $lastmininghistory->selisih * (($user->cloudminingmh - $uncountmh) / $setting->totalmh);

							$usermininghistory = new Usermininghistory;
							$usermininghistory->user_id = $user->id;
							if($lastusermininghistory != null)
							{
								$usermininghistory->balance = $userminingselisih + $lastusermininghistory->balance;
							}
							else
							{
								$usermininghistory->balance = $userminingselisih;
							}
							$usermininghistory->selisih = $userminingselisih;
								
							$usermininghistory->save();
						}
					}
					
					/*
						Pengiriman email jika status TRUE
					*/
					$emails = [
						'1' => 'hans@creids.net',
						'2' => 'anggi@creids.net'
					];

					foreach ($emails as $email) {
						Mail::to($email)
						    ->send(new Cron($mininghistory, $avg, $users));
					}
				}
				else
				{
					/*
						Pengiriman email jika status FALSE
					*/
					$emails = [
						'1' => 'hans@creids.net',
						'2' => 'anggi@creids.net'
					];

					foreach ($emails as $email) {
						Mail::to($email)
						    ->send(new Cronfail());
					}
				}

			/*
				MACHINE MINING
			*/
				// $machinesendlink = getData("https://api.nanopool.org/v1/eth/balance/" . $setting->machine_walletid);
				$machinesendlink = getData("https://api.nanopool.org/v1/eth/user/" . $setting->machine_walletid);
				if($machinesendlink["status"] != false)
				{
					$machinegetbalance = $machinesendlink["data"]["balance"];
					$machinegetavgone = $machinesendlink["data"]["avgHashrate"]["h1"];



					/*
						Machine Mining History
					*/

						// $machines = Machine::where('is_active', '=', true)->get();
						// if($machines != null)
						// {

						// 	/*
						// 		Create new machine mining history
						// 	*/
						// 		foreach ($machines as $machine) {
						// 		/*
						// 			Get last machine mining history
						// 		*/
						// 			$lastmachinemininghistory = Machinemininghistory::where('user_id', '=', $machine->user_id)->orderBy('id', 'desc')->first();

						// 			$machinemininghistory = new Machinemininghistory;
						// 			$machinemininghistory->machine_id = $machine->machine_id;
						// 			$machinemininghistory->balance_api = $machinegetbalance;

						// 			if($lastmachinemininghistory != null)
						// 			{
						// 				if($machinegetbalance < $lastmachinemininghistory->balance_api)
						// 				{
						// 					$machinemininghistory->inc = $lastmachinemininghistory->inc++;
						// 				}
						// 				else
						// 				{
						// 					$machinemininghistory->inc = $lastmachinemininghistory->inc;
						// 				}

						// 				$balancereal = $machinegetbalance + $lastmachinemininghistory->inc;

						// 				$machinemininghistory->balance_real = $balancereal;
						// 				$machinemininghistory->selisih_real = $balancereal - $lastmachinemininghistory->balance_real;
						// 				$machinemininghistory->balance = $balancereal - ($balancereal * $setting->charge) / 100;
						// 				$machinemininghistory->selisih = ($balancereal - ($balancereal * $setting->charge) / 100) - $lastmachinemininghistory->balance;
						// 			}
						// 			else
						// 			{
						// 				$machinemininghistory->inc = 0;
						// 				$machinemininghistory->balance_real = $machinegetbalance;
						// 				$machinemininghistory->selisih_real = 0;
						// 				$machinemininghistory->balance = $machinegetbalance - ($machinegetbalance * $setting->charge) / 100;
						// 				$machinemininghistory->selisih = 0;
						// 			}
						// 			$machinemininghistory->save();
						// 		}
						// }
				}

		});

		// global $mininghistory;
		// global $avg;
		// global $users;
	}
}