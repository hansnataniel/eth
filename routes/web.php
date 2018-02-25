<?php

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Avg;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Mininghistory;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * CUSTOM FUNCTIONS
 */

function getData($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$rawData = curl_exec($curl);
	curl_close($curl);
	return json_decode($rawData, true);
}

Route::get('check-api', function(){
	$callfunction = getData("https://api.nanopool.org/v1/eth/balance/0x9f3d97d8887eb429c0c500d4084f151796212e1e");
	$getfunction = $callfunction["data"];

	dd($getfunction);
});

function limitChar($string, $max) 
{
	/**
	 * Untuk membuat maksimal karakter yang mau di tampilkan 
	 */
	
	$word_length = strlen($string);
    if($word_length > $max)
    {
		$hasil = substr($string, 0, $max) . '...';
    }
    else
    {
		$hasil = $string;
    }
	return $hasil;
};

function digitGroup($var) 
{
	/**
	 * Untuk merubah menjadi number format --> 10.000
	 */
	
	return number_format((float)$var, 2,",",".");
};

function removeDigitGroup($var) 
{
	/**
	 * Untuk merubah dari number format ke number normal --> 10000
	 */
	
	return str_replace(',', '', $var);
};

function tanggal($date) 
{
	return date('d F Y', strtotime($date));
}

function tanggal2($date) 
{
	return date('d/m/Y', strtotime($date));
}

function rupiah($nilai) 
{
	return "Rp " . number_format((float)$nilai, 0,".",",");
}

function rupiah2($nilai) 
{
	return "Rp " . number_format((float)$nilai, 0,",",".");
}

function rupiah3($nilai) 
{
	return number_format((float)$nilai, 0,",",".");
}

Route::get('creidsdb', function() {
    return view('back.template.creidsdb');
});

Route::get('creidsdbmigrate', function()
{
	echo 'Initiating DB Migrate...<br>';
	define('STDIN',fopen("php://stdin","r"));
	Artisan::call('migrate', ['--quiet' => true, '--force' => true]);
	// echo 'DB Migrate done.<br><br>';
	return "DB Migrate done.<br><br>";
});

Route::get('creidsdbfill', function()
{
	echo 'Initiating DB Seed...<br>';
	define('STDIN',fopen("php://stdin","r"));
	Artisan::call('db:seed', ['--quiet' => true, '--force' => true]);
	// echo 'DB Seed done.<br>';
	return "DB Seed done.<br>";
});

Route::get('creidsdbrollback', function()
{
	echo 'Initiating DB Rollback...<br>';
	define('STDIN',fopen("php://stdin","r"));
	Artisan::call('migrate:rollback', ['--quiet' => true, '--force' => true]);
	// echo 'DB Delete done.<br>';
	return "DB Delete done.<br>";
});

// Route::get('/', function () {
	// return "jalan";
// });

if (Schema::hasTable('settings'))
{
	$setting = Setting::first();
	if($setting != null)
	{
		Route::get('maintenance', function () {
		    return view('errors.optimize');
		});

		/*
			ROUTE FOR BACK END
		*/

		Route::group(['namespace' => 'Back', 'guard'=>'admin', 'prefix' => Crypt::decrypt($setting->admin_url)], function() use ($setting) 
		{
			/*
				LOGIN CONTROLLER
			*/
			Route::get('/', 'AuthController@getLogin')->name('login');
			Route::post('/', 'AuthController@postLogin')->name('login');
			Route::get('logout', 'AuthController@getLogout')->name('logout');

			/*
				FORGOT PASSWORD CONTROLLER
			*/
			Route::get('password/remind', 'ReminderController@getRemind');
			Route::post('password/remind', 'ReminderController@postRemind');
			Route::get('password/reset/{token?}', 'ReminderController@getReset');
			Route::post('password/reset/{token?}', 'ReminderController@postReset');

			/**
			 * CROPPING ROUTE
			 */

			Route::get('cropper/{width}/{height}', function(Request $request, $width, $height){
				if ($request->ajax())
				{
					$data['w_ratio'] = $width;
					$data['h_ratio'] = $height;

					return view('back.crop.jquery', $data);
				}
			});

			Route::group(['middleware' => ['authback', 'undoneback', 'sessiontimeback', 'backlastactivity']], function(){

				/* 
					DASHBOARD 
				*/
				Route::get('dashboard', function(Request $request){
					$setting = Setting::first();
					$data['setting'] = $setting;

					$data['messageModul'] = true;
					$data['alertModul'] = true;
					$data['searchModul'] = true;
					$data['helpModul'] = true;
					$data['navModul'] = true;

					$mininghistories = Mininghistory::orderBy('id', 'desc')->take(24)->get();
					$data['mininghistories'] = $mininghistories;

					$data['request'] = $request;

					return view('back.dashboard.index', $data);
				});

				/* 
					SETTING CONTROLLER 
				*/
				Route::get('setting/edit', 'SettingController@getEdit');
				Route::post('setting/edit', 'SettingController@postEdit');

				/*
					USER CONTROLLER
				*/
				Route::get('user/edit-profile', 'UserController@getEditProfile');
				Route::post('user/edit-profile', 'UserController@postEditProfile');
				Route::get('user/suspended/{id}', 'UserController@getsuspended');
				Route::get('user/machine/{id}', 'UserController@getMachine');
				Route::get('user/machine-create/{id}', 'UserController@getMachineCreate');
				Route::post('user/machine-create/{id}', 'UserController@postMachineCreate');
				Route::get('user/machine-show/{id}', 'UserController@getMachineShow');
				Route::get('user/machine-edit/{id}', 'UserController@getMachineEdit');
				Route::post('user/machine-edit/{id}', 'UserController@postMachineEdit');
				Route::post('user/machine-delete/{id}', 'UserController@postMachineDelete');
				Route::resource('user', 'UserController');

				/*
					ADMIN GROUP CONTROLLER
				*/
				Route::resource('admingroup', 'AdmingroupController');

				/*
					ADMIN CONTROLLER
				*/
				Route::get('admin/edit-profile', 'AdminController@getEditProfile');
				Route::post('admin/edit-profile', 'AdminController@postEditProfile');
				Route::get('admin/suspended/{id}', 'AdminController@getsuspended');
				Route::resource('admin', 'AdminController');


				/*
					BANK CONTROLLER
				*/
				Route::resource('bank', 'BankController');

				/* 
					PURCHASE CONTROLLER 
				*/
				Route::get('purchase/pending', 'PurchaseController@getPending');
				Route::resource('purchase', 'PurchaseController');

				/* 
					PAYMENT CONTROLLER 
				*/
				Route::get('payment/pending', 'PaymentController@getPending');
				Route::get('payment/confirm/{id}', 'PaymentController@getConfirm');
				Route::get('payment/decline/{id}', 'PaymentController@getDecline');
				Route::resource('payment', 'PaymentController');

				/* 
					WITHDRAWAL CONTROLLER 
				*/
				Route::get('withdrawal/pending', 'WithdrawalController@getPending');
				Route::get('withdrawal/confirm/{id}', 'WithdrawalController@getConfirm');
				Route::get('withdrawal/decline/{id}', 'WithdrawalController@getDecline');
				Route::resource('withdrawal', 'WithdrawalController');

				/*
					CONTACT CONTROLLER
				*/
				Route::resource('contact', 'ContactController');
			});
		});


		/*
			ROUTE FOR FRONT END
		*/
		Route::group(['namespace' => 'Front', 'guard'=>'web', 'middleware' => ['appisup', 'visitorcounter', 'pageload', 'visitorlastactivity']], function(){

			/*
				REGISTRATION CONTROLLER
			*/
			Route::resource('register', 'RegistrationController', ['only' => ['index', 'store']]);

			/*
				LOGIN CONTROLLER
			*/
			Route::get('login', 'AuthController@getLogin')->name('login');
			Route::post('login', 'AuthController@postLogin')->name('login');
			Route::get('logout', 'AuthController@getLogout')->name('logout');

			/*
				FORGOT PASSWORD CONTROLLER
			*/
			Route::get('password/remind', 'ReminderController@getRemind');
			Route::post('password/remind', 'ReminderController@postRemind');
			Route::get('password/reset/{token?}', 'ReminderController@getReset');
			Route::post('password/reset/{token?}', 'ReminderController@postReset');


			Route::group(['middleware' => ['authfront', 'undonefront', 'sessiontimefront', 'frontlastactivity']], function(){
				/*
					DASHBOARD CONTROLLER
				*/
				Route::get('dashboard/get-avg', 'DashboardController@getavg');
				Route::get('dashboard/mh', 'DashboardController@getMh');
				Route::get('dashboard/machine', 'DashboardController@getMachine');
				Route::get('dashboard/machine/detail/{id}', 'DashboardController@getMachineDetail');
				Route::resource('dashboard', 'DashboardController');

				/*
					PROFILE CONTROLLER
				*/
				Route::resource('my-profile', 'ProfileController', ['only' => ['index', 'update']]);

				/*
					SUBSCRIPTION CONTROLLER
				*/
				Route::get('subscription/cancel/{id}', 'SubscriptionController@getCancel');
				Route::get('subscription/history', 'SubscriptionController@getHistory');
				Route::resource('subscription', 'SubscriptionController');


				/*
					PAYMENT CONTROLLER
				*/
				Route::get('payment/history', 'PaymentController@getHistory');
				Route::get('payment/{id?}', 'PaymentController@getPayment');
				Route::post('payment/{id?}', 'PaymentController@store');
				Route::resource('payment', 'PaymentController');

				/*
					WITHDRAWAL CONTROLLER
				*/
				Route::get('withdrawal/history', 'WithdrawalController@getHistory');
				Route::resource('withdrawal', 'WithdrawalController');
			});

			/*
				CONTACT US CONTROLLER
			*/
			Route::resource('contact-us', 'ContactController');

			Route::get('maintenance', function (Request $request) {
				return view('errors.maintenance');
			});

			Route::get('/', function (Request $request) {
				$data['request'] = $request;
				return view('front.home.index', $data);
			});

			Route::resource('cron', 'CronController');
		});
	}
	else
	{
		return "Your Setting is empty";
	}
}
else
{
	return "The class Setting doesn't exist, Please migrate first";
}

// Auth::routes();

// Route::get('/home', 'HomeController@index');
