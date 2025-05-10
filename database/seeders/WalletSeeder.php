<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users who don't have a wallet yet
        $users = User::whereDoesntHave('wallet')->get();
        
        foreach ($users as $user) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0.00
            ]);
        }
        
        $this->command->info('Wallets created for ' . $users->count() . ' users.');
    }
}
