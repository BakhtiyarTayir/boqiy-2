<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\LikeBalance;
use Illuminate\Support\Facades\Log;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (auth()->check()) {
                // Get user's like balance
                $likeBalance = LikeBalance::where('user_id', Auth()->user()->id)->first();
                
                // Create like balance if it doesn't exist
                if (!$likeBalance) {
                    try {
                        $likeBalance = LikeBalance::create([
                            'user_id' => Auth()->user()->id,
                            'balance' => 0
                        ]);
                    } catch (\Exception $e) {
                        // Log error but continue
                        Log::error('Failed to create like balance: ' . $e->getMessage());
                    }
                }
                
                $view->with('likeBalance', $likeBalance);
            }
        });
    }
} 