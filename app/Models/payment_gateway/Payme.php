<?php

namespace App\Models\payment_gateway;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class Payme extends Model
{
    use HasFactory;

    /**
     * Check payment status
     * 
     * @param string $identifier
     * @param array $transaction_keys
     * @return bool
     */
    public function payment_status($identifier, $transaction_keys = [])
    {
        // Get payment details from session
        $payment_details = session('payment_details');
        
        // If transaction_id is present in the request, payment was successful
        if (isset($transaction_keys['transaction_id'])) {
            return true;
        }
        
        return false;
    }

    /**
     * Create payment
     * 
     * @param string $identifier
     * @return mixed
     */
    public static function payment_create($identifier)
    {
        $payment_gateway = DB::table('payment_gateways')->where('identifier', $identifier)->first();
        $payment_details = session('payment_details');
        $keys = json_decode($payment_gateway->keys, true);
        
        // Get merchant ID and key based on mode
        $merchant_id = $payment_gateway->test_mode == 1 ? $keys['merchant_id'] : $keys['merchant_id_live'];
        
        // Логируем для отладки
        Log::info('Payme payment creation', [
            'test_mode' => $payment_gateway->test_mode,
            'merchant_id' => $merchant_id
        ]);
        
        // Generate unique order ID (добавлено больше энтропии)
        $order_id = 'LIKE_' . Auth::id() . '_' . uniqid() . '_' . time();
        
        // Calculate amount in tiyins (100 tiyins = 1 UZS)
        $amount = intval($payment_details['payable_amount'] * 100);
        
        // Get URLs from configuration
        // Используем правильный URL в зависимости от режима
        $checkout_url = $payment_gateway->test_mode == 1 
            ? (isset($keys['checkout_url']) ? $keys['checkout_url'] : 'https://test.paycom.uz')
            : (isset($keys['checkout_url_live']) ? $keys['checkout_url_live'] : 'https://checkout.paycom.uz');
        
        // Добавляем проверку, что URL корректный
        if (!filter_var($checkout_url, FILTER_VALIDATE_URL)) {
            $checkout_url = $payment_gateway->test_mode == 1 
                ? 'https://test.paycom.uz'
                : 'https://checkout.paycom.uz';
        }
        
        // Get current user ID
        $user_id = Auth::id();
        if (!$user_id) {
            // Handle case where user is not authenticated
            Log::error('Payme payment_create: User not authenticated.');
            return redirect()->route('login')->with('error', 'Please login to continue payment.');
        }
        
        // Save the transaction in our database
        DB::table('transactions')->insert([
            'user_id' => $user_id,
            'amount' => $payment_details['payable_amount'],
            'type' => 'topup',
            'status' => 'pending',
            'payment_method' => 'payme',
            'order_id' => $order_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Use the absolute URL for return path instead of route name
        $return_url = url('/payme/status/' . $order_id . '?success=1');
        $cancel_url = url('/payme/status/' . $order_id . '?cancel=1');
        
        // В качестве резервного варианта сохраняем полный URL в сессии
        session(['payme_return_url' => $return_url]);
        session(['payme_cancel_url' => $cancel_url]);
        
        // Prepare data for checkout URL
        $params = [
            'm' => $merchant_id,
            'ac.order_id' => $order_id,
            'ac.user_id' => $user_id,
            'a' => $amount,
            'l' => App::getLocale(),
            'c' => $return_url,  // URL возврата с полным абсолютным путем
            'ct' => 0, // Lifetime in minutes (0 = no limit)
            'cr' => 'UZS',
            'd' => isset($payment_details['description']) ? $payment_details['description'] : 'Like balance top-up',
            'cl' => $cancel_url  // URL для отмены платежа
        ];
        
        // Логируем параметры для отладки
        Log::info('Payme checkout parameters', [
            'params' => $params,
            'checkout_url' => $checkout_url,
            'return_url' => $return_url,
            'cancel_url' => $cancel_url,
            'cl_param' => $params['cl']
        ]);
        
        // Build checkout URL
        $checkout_url = rtrim($checkout_url, '/') . '/?' . http_build_query($params);
        
        // Redirect to Payme checkout
        return redirect()->away($checkout_url);
    }

    /**
     * Handle callback from Payme
     * 
     * @param Request $request
     * @return mixed
     */
    public function handleCallback(Request $request)
    {
        Log::info('Payme callback received', $request->all());
        
        // Get payment gateway configuration
        $payment_gateway = DB::table('payment_gateways')->where('identifier', 'payme')->first();
        $keys = json_decode($payment_gateway->keys, true);
        
        // Get merchant key based on mode
        $merchant_key = $payment_gateway->test_mode == 1 ? $keys['merchant_key'] : $keys['merchant_key_live'];
        
        // Verify authorization
        $auth = $request->header('Authorization');
        if (!$auth || !$this->checkAuth($auth, $merchant_key)) {
            return response()->json([
                'error' => [
                    'code' => -32504,
                    'message' => 'Authorization failed',
                ]
            ], 401);
        }
        
        // Verify the transaction
        $transaction_id = $request->input('id');
        $order_id = $request->input('order_id');
        
        // If verification is successful, return success
        if ($transaction_id && $order_id) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'error' => 'Invalid transaction']);
    }
    
    /**
     * Check authorization header
     * 
     * @param string $auth
     * @param string $merchant_key
     * @return bool
     */
    protected function checkAuth($auth, $merchant_key)
    {
        // Extract credentials from Basic Auth header
        if (preg_match('/^Basic\s+(.*)$/i', $auth, $matches)) {
            $credentials = base64_decode($matches[1]);
            list($login, $password) = explode(':', $credentials);
            
            // Check if credentials match
            return $login === $merchant_key;
        }
        
        return false;
    }
} 