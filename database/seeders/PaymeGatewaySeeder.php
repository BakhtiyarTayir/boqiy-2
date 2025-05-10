<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymeGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if Payme gateway already exists
        $exists = DB::table('payment_gateways')
            ->where('identifier', 'payme')
            ->exists();
            
        if (!$exists) {
            DB::table('payment_gateways')->insert([
                'identifier' => 'payme',
                'currency' => 'UZS',
                'title' => 'Payme',
                'description' => 'Pay with Payme',
                'keys' => json_encode([
                    'merchant_id' => '6666a1fe37d1791960debbd7',
                    'merchant_key' => 'QCqk8GhhthnEj9Jz11zZzRUnn12W8@CDD3rI',
                    'checkout_url' => 'https://checkout.paycom.uz',
                    'return_url' => 'https://boqiy.uz/cart/?payme_success=1',
                    'merchant_id_live' => '6666a1fe37d1791960debbd7', // Same as test for now
                    'merchant_key_live' => 'QCqk8GhhthnEj9Jz11zZzRUnn12W8@CDD3rI', // Same as test for now
                ]),
                'model_name' => 'Payme',
                'status' => 1,
                'test_mode' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
} 