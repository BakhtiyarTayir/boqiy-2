<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Wallet;

class WalletServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    } 

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Create a wallet for each new user
	    // @todo Asilbek change bu ish user regstratsiyada qilindi
	    if (0) {
		    User::created(function ($user) {
			    Wallet::create([
				    'user_id' => $user->id,
				    'balance' => 0.00
			    ]);
		    });
	    }
     
    }
} 
