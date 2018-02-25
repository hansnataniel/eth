<?php

/**
 * Use Name Space Here
 */
namespace App\Http\Controllers\Front;

/**
 * Call Model Here
 */
use App\Models\Setting;
use App\Models\User;
use App\Models\Avg;
use App\Models\Purchase;
use App\Models\Mininghistory;
use App\Models\Usermininghistory;

/**
 * Call Mail file & mail facades
 */
use App\Mail\Front\Cron;
use App\Mail\Front\Cronfail;

use Illuminate\Support\Facades\Mail;

/**
 * Call Another Function  you want to use
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
	public function index(Request $request)
	{
		DB::transaction(function(){

			$setting = Setting::first();

			$cloudsendlink = getData("https://api.nanopool.org/v1/eth/user/" . $setting->cloud_mining_walletid);
			
			/**
			 * Pengecekkan status API
			 */
			if($cloudsendlink["status"] != false)
			{
				$cloudgetbalance = $cloudsendlink["data"]["balance"];

				/**
				 * Create new record of mining history
				 */
				$mininghistory = new Mininghistory;
				
				$mininghistory->balance_api = $cloudgetbalance;

				/**
				 * Get last mining history and 2nd last mining history to be shared to users
				 */
				$lastmininghistory = Mininghistory::orderBy('id', 'desc')->first();
				$sharedmininghistory = Mininghistory::orderBy('id', 'desc')->skip(1)->first();

				/**
				 * Pengecekkan data pertama dari table Mininghistories
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

				/**
				 * Create new User Mining History
				 */
				$users = User::where('is_active', '=', true)->where('is_suspended', '=', false)->where(function($qr){
					$qr->where('cloudminingmh', '!=', 0);
					$qr->orWhere('cloudminingmh', '!=', null);	
				})->get();
				if($users != null)
				{
					foreach ($users as $user) {
						$lastusermininghistory = Usermininghistory::where('user_id', '=', $user->id)->orderBy('id', 'desc')->first();

						$uncount_time = date("Y-m-d H:i:s", strtotime('-1 hour'));

						$usermh = Usermh::select(DB::raw('sum(mh) as total_mh'))->where('user_id', '=', $user->id)->where('status', '=', 'Active')->where('is_active', '=', true)->where('active_time', '<', $uncount_time)->first();
						// $uncountmh = 0;
						// foreach ($usermhs as $usermh)
						// {
						// 	if (strtotime($usermh->active_time) > date("Y-m-d H:i:s", strtotime('-1 hour')))
						// 	{
						// 		$uncountmh = $uncountmh + $usermh->mh;
						// 	}
						// }

						$userminingselisih = $sharedmininghistory->selisih * ($usermh->total_mh / $setting->totalmh);

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

						$user->balance = $user->balance + $userminingselisih;
					}
				}
				
				/**
				 * Pengiriman email jika status TRUE
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
				/**
				 * Pengiriman email jika status FALSE
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
		});
	}
}