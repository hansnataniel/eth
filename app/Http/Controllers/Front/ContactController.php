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
use App\Models\Contact;

/*
	Call Mail file & mail facades
*/
use App\Mail\Front\Contactus;

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


class ContactController extends Controller
{
    /* 
    	GET THE LIST OF THE RESOURCE
    */
	public function index(Request $request)
	{
		$contact = new Contact;
		$data['contact'] = $contact;

		$setting = Setting::first();
		$data['setting'] = $setting;

		$data['request'] = $request;

        return view('front.contact.index', $data);
	}

	public function store(Request $request)
	{
		$inputs = $request->all();
		$rules = array(
			'name' 				=> 'required',
			'email' 			=> 'required|email',
			'subject' 			=> 'required',
			'message' 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$contact = new Contact;
			$contact->name = htmlspecialchars($request->input('name'));
			$contact->email = htmlspecialchars($request->input('email'));
			$contact->phone = htmlspecialchars($request->input('phone'));
			$contact->subject = htmlspecialchars($request->input('subject'));
			$contact->msg = htmlspecialchars($request->input('message'));
			$contact->save();

			Mail::to($setting->receiver_email)
			    ->send(new Contactus($contact));

			return redirect('contact-us')->with('success-message', "Your message has been sent<br> Thank you for your message");
		}
		else
		{
			return redirect('contact-us')->withInput()->withErrors($validator);
		}
	}
}