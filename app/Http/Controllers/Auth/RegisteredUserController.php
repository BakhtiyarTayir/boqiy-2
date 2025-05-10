<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LikeBalance;
use App\Models\User;
use App\Models\Wallet;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Carbon\Carbon;
use Session;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if(get_settings('public_signup') != 1){
            Session::flash('error_message', get_phrase('Public signup not allowed'));
            return redirect()->route('login');
        }
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
	    // return $request->all();
	    $request->validate([
		    'name' => ['required', 'string', 'max:255'],
		    //    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
		    'phone' => ['required', 'regex:/^\d{9}$/', 'unique:users,phone'],
		    'password' => ['required', Rules\Password::defaults()],
	    ]);
	
	    $phone = $request->phone;
	
	    $userCheck = User::all()->where('phone', $phone);
	
	    if (!empty($userCheck) && count($userCheck)) {
		    return back()->with([
			    'errorType' => 'hasPhone',
			    'errorText' => 'Bu telefon raqam bilan ro\'yxatdan o\'tilgan',
		    ]);
	    }
	
	    $generateEmail = $request->phone . '@boqiy.uz';
	
	    $user = User::create([
		    'user_role' => 'general',
		    'username' => rand(100000, 999999),
		    'name' => $request->name,
		    'email' => $generateEmail,
		    'friends' => json_encode(array()),
		    'followers' => json_encode(array()),
		    'timezone' => $request->timezone,
		    'password' => Hash::make($request->password),
		    'phone' => $request->phone,
		    'status' => 1,
		    'lastActive' => Carbon::now(),
		    'email_verified_at' => Carbon::now(),
		    'created_at' => time()
	    ]);
	
	    if ($user) {
		    Wallet::create([
			    'user_id' => $user->id,
			    'balance' => 20000
		    ]);
		
		    LikeBalance::create([
			    'user_id' => $user->id,
			    'balance' => 5000
		    ]);
	    }
	
	    Auth::login($user);
	
	    return redirect(RouteServiceProvider::HOME)->with(['showWelcomeBonusModal' => 1]);
    }
}
