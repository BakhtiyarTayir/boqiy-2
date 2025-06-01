<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;

class CreateWalletsSeeder extends Seeder
{
    public function run()
    {
        User::whereDoesntHave('wallet')->each(function ($user) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0
            ]);
        });
    }
} 