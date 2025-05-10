<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikeBalanceController extends Controller
{
    public function processTopup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:1000',
                'payment_method' => 'required|in:payme' // Currently only payme is supported
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $user = Auth::user();
            if (!$user) {
                 // Should not happen if middleware is correct, but good practice
                 return redirect()->route('login')->with('error', 'Please login to continue.');
            }
            $amount = $request->amount;

            // Получаем настройки Payme
            $paymeGateway = DB::table('payment_gateways')->where('identifier', 'payme')->first();

            if (!$paymeGateway) {
                return redirect()->back()
                    ->with('error', get_phrase('Payment method is not available'))
                    ->withInput();
            }

            $paymeSettings = json_decode($paymeGateway->keys, true);

            // Check if essential settings are present
            $merchantIdKey = $paymeGateway->test_mode ? 'merchant_id' : 'merchant_id_live';
            $merchantId = $paymeSettings[$merchantIdKey] ?? null;
            $checkoutUrl = $paymeSettings['checkout_url'] ?? null;

            if (!$merchantId || !$checkoutUrl) {
                 \Log::error('Payme settings incomplete.', ['gateway_id' => $paymeGateway->id]);
                 return redirect()->back()
                    ->with('error', get_phrase('Payme gateway is not configured correctly.'))
                    ->withInput();
            }


            // Создаем уникальный ID заказа
            $orderId = 'LB' . time() . rand(1000, 9999);

            // Сохраняем транзакцию в нашей системе
            // Consider wrapping this in a DB transaction if more operations are added
            DB::table('transactions')->insert([
                'user_id' => $user->id,
                'amount' => $amount, // Store amount in SUM as per previous logic
                'type' => 'topup',
                'status' => 'pending', // Initial status
                'payment_method' => 'payme',
                'order_id' => $orderId, // Our local unique order ID
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Формируем данные для POST-формы Payme
            $amountTiyin = $amount * 100; // Конвертируем в тийины для Payme
            
            // Define the client-side return URL (user's browser destination)
            // You need to create a route and view for 'like_balance.topup_status'
            $clientReturnUrl = route('like_balance.topup_status', ['orderId' => $orderId]);

            $description = get_phrase('Like balance top-up for order :orderId', ['orderId' => $orderId]); // Пример описания
            
            $formData = [
                'merchant'      => $merchantId,
                'amount'        => $amountTiyin,
                'account'       => [
                    'order_id' => $orderId,      // Наш ID заказа для сверки
                    'user_id'  => $user->id       // ID пользователя
                ],
                'lang'          => app()->getLocale(),      // Язык интерфейса Payme
                'callback'      => $clientReturnUrl,        // URL возврата пользователя ПОСЛЕ оплаты
                'callback_timeout' => 15000,               // Таймаут перед редиректом (15 сек)
                'description'   => $description,
                // 'detail'      => base64_encode(json_encode($detailObject)) // Опционально, для фискализации
            ];

            // Адрес, куда будет отправлена POST форма
            $paymeFormActionUrl = rtrim($checkoutUrl, '/'); 

            // Возвращаем view с POST-формой, которая будет автоматически отправлена
            return view('frontend.like_balance.payme_post_form', compact('paymeFormActionUrl', 'formData'));

        } catch (\Exception $e) {
            // Log detailed error
            \Log::error('Like balance topup processing error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['_token']), // Don't log token
                'exception' => $e
            ]);
            return redirect()->back()
                ->with('error', get_phrase('An error occurred while processing your payment. Please try again.'))
                ->withInput();
        }
    }

    // Method to show topup status page (Needs implementation)
    public function showTopupStatus(Request $request, $orderId)
    {
        // Find the transaction in your database by $orderId
        $transaction = DB::table('transactions')->where('order_id', $orderId)->where('user_id', Auth::id())->first();

        if (!$transaction) {
            // Handle case where transaction is not found or doesn't belong to the user
            abort(404);
        }

        // Return a view with the transaction details
        // You need to create this view: resources/views/frontend/like_balance/status.blade.php
        return view('frontend.like_balance.status', ['transaction' => $transaction]);
    }

     //Placeholder for the index method if needed
     public function index()
     {
         // Logic for displaying the like balance index page
         return view('frontend.like_balance.index'); // Example view path
     }

     // Placeholder for the topup method if needed
     public function topup()
     {
         // Logic for displaying the topup form
         return view('frontend.like_balance.topup');
     }

     // Placeholder for the transactions method if needed
     public function transactions()
     {
         // Logic for displaying the transaction history
         $transactions = DB::table('transactions')
                            ->where('user_id', Auth::id())
                            ->where('type', 'topup') // Or other relevant types
                            ->orderBy('created_at', 'desc')
                            ->paginate(15); // Example pagination

         return view('frontend.like_balance.transactions', ['transactions' => $transactions]);
     }

     // Placeholder for the checkBalance method if needed
     public function checkBalance(Request $request)
     {
         // Logic for checking balance (e.g., via AJAX)
         $user = Auth::user();
         $balance = DB::table('like_balances')->where('user_id', $user->id)->value('balance');
         return response()->json(['balance' => $balance ?? 0]);
     }

     // Placeholder for the sendLike method if needed
     public function sendLike(Request $request)
     {
         // Logic for deducting balance when sending a like
         // This needs proper implementation with validation and error handling
     }

     // Placeholder for the getPostLikesCount method if needed
     public function getPostLikesCount(Request $request, $postId)
     {
         // Logic for getting like count for a post
         $count = DB::table('likes')->where('post_id', $postId)->count();
         return response()->json(['count' => $count]);
     }

} 