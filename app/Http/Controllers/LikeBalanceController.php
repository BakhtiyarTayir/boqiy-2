<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LikeBalance;
use App\Models\LikeTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LikeBalanceController extends Controller
{
    /**
     * Отображение баланса лайков пользователя
     */
    public function index()
    {
		// @todo: Asilbek change
	    $user = Auth::user();
	    $userLikeBalance = $user->likeBalance;
	
	    if (!$userLikeBalance) {
		    // Создаем баланс лайков, если его нет
		    $userLikeBalance = LikeBalance::create([
			    'user_id' => $user->id,
			    'balance' => 0
		    ]);
	    }
	
	    $transactions = $userLikeBalance->transactions()
		    ->orderBy('created_at', 'desc')
		    ->paginate(10);
	
	    $view_path = 'frontend.like_balance.index';
	
	    $todayUserTransactions = DB::table('like_transactions')
		        ->whereDate('created_at', DB::raw('CURDATE()'))
		    ->select('user_id', 'amount')
		    ->get();
	
	    $todayAllAmount = $todayUserTransactions->sum('amount');
	    $todayTransactionsCount = count($todayUserTransactions->groupBy('user_id'));
	
	    return view('frontend.index', [
		    'userLikeBalance' => $userLikeBalance,
		    'transactions' => $transactions,
		    'view_path' => $view_path,
		    'todayUserTransactions' => $todayTransactionsCount, // count($todayUserTransactions),
		    'todayAllAmount' => $todayAllAmount, // count($todayUserTransactions),
	    ]);
    }
    
    /**
     * Отображение формы для пополнения баланса лайков
     */
    public function topup()
    {
        $view_path = 'frontend.like_balance.topup';
        return view('frontend.index', compact('view_path'));
    }
    
    /**
     * Обработка запроса на пополнение баланса лайков
     */
    public function processTopup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:1000',
                'payment_method' => 'required|in:payme'
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $user = Auth::user();
            $amount = $request->amount;

            // Получаем настройки Payme
            $paymeGateway = DB::table('payment_gateways')->where('identifier', 'payme')->first();
            
            if (!$paymeGateway) {
                return redirect()->back()
                    ->with('error', 'Payment method is not available')
                    ->withInput();
            }

            $paymeSettings = json_decode($paymeGateway->keys, true);

            // Создаем заказ в базе данных
            $order = \App\Models\Order::createLikeBalanceTopup(
                $user->id,
                $amount,
                'payme',
                ['description' => 'Пополнение баланса лайков']
            );

            // Формируем данные для POST-формы
            $merchantId = $paymeGateway->test_mode == 1 ? $paymeSettings['merchant_id'] : $paymeSettings['merchant_id_live'];
            $amountTiyin = round($amount * 100); // Конвертируем в тийины (1 сум = 100 тийин)
            
            // URL для возврата после оплаты и отмены
            $baseUrl = config('app.url', 'https://boqiy.uz'); 
            // URL для возврата при успешной оплате
            $clientReturnUrl = $baseUrl . '/payme/status/' . $order->order_id . '?success=1';
            // URL для возврата при отмене оплаты
            $clientCancelUrl = $baseUrl . '/payme/status/' . $order->order_id . '?cancel=1';
            
            // URL для отправки POST-формы
            $checkoutUrl = rtrim($paymeSettings['checkout_url'], '/');
            
            // Описание платежа
            $description = get_phrase('Like balance top-up for order :orderId', ['orderId' => $order->order_id]);

            // Данные для POST-формы
            $formData = [
                'merchant' => $merchantId,
                'amount' => $amountTiyin,
                'account' => [
                    'order_id' => $order->order_id,
                    'user_id' => $user->id
                ],
                'lang' => app()->getLocale(),
                'callback' => $clientReturnUrl,
                'callback_timeout' => 15000, // 15 секунд
                'description' => $description,
                // URL для отмены платежа в соответствии с документацией Payme
                'cl' => $clientCancelUrl // Параметр "cl" для отмены, не cancel_url!
            ];

            // Возвращаем view с POST-формой
            return view('frontend.like_balance.payme_post_form', [
                'paymeFormActionUrl' => $checkoutUrl,
                'formData' => $formData
            ]);

        } catch (\Exception $e) {
            \Log::error('Payment processing error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while processing your payment. Please try again.')
                ->withInput();
        }
    }
    
    /**
     * Перенаправление на страницу оплаты Payme
     */
    public function payme()
    {
        $amount = session('topup_amount');
        
        if (!$amount) {
            return redirect()->route('like_balance.topup')
                ->with('error', 'Ошибка при обработке запроса на пополнение');
        }
        
        // Здесь должна быть логика для создания платежа в Payme
        // Для примера, мы просто отобразим страницу с информацией о платеже
        
        $view_path = 'frontend/like_balance/payme';
        return view('frontend.index', compact('amount', 'view_path'));
    }
    
    /**
     * Обработка callback от Payme
     */
    public function paymeCallback(Request $request)
    {
        // Получаем order_id из параметров
        $orderId = $request->input('order_id');
        
        if (!$orderId) {
            return redirect()->route('like_balance.topup')
                ->with('error', get_phrase('Payment failed. Please try again.'));
        }

        // Находим транзакцию
        $transaction = DB::table('transactions')
            ->where('order_id', $orderId)
            ->first();

        if (!$transaction) {
            return redirect()->route('like_balance.topup')
                ->with('error', get_phrase('Invalid transaction.'));
        }

        // Проверяем статус оплаты
        // В данном случае мы просто проверяем наличие параметра payment_status
        // В реальном приложении здесь должна быть проверка подписи и других параметров от Payme
        if ($request->input('payment_status') === 'success') {
            // Обновляем статус транзакции
            DB::table('transactions')
                ->where('id', $transaction->id)
                ->update([
                    'status' => 'completed',
                    'updated_at' => now()
                ]);

            // Пополняем баланс пользователя
            DB::table('users')
                ->where('id', $transaction->user_id)
                ->increment('like_balance', $transaction->amount);

            return redirect()->route('like_balance.index')
                ->with('success', get_phrase('Your balance has been topped up successfully!'));
        }

        return redirect()->route('like_balance.topup')
            ->with('error', get_phrase('Payment failed. Please try again.'));
    }
    
    /**
     * Получение истории транзакций
     */
    public function transactions()
    {
        $user = Auth::user();
        $likeBalance = $user->likeBalance;
        
        if (!$likeBalance) {
            return response()->json(['success' => false, 'message' => 'Баланс лайков не найден']);
        }
        
        $transactions = $likeBalance->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return response()->json([
            'success' => true,
            'transactions' => $transactions
        ]);
    }

    /**
     * Проверка баланса лайков
     */
    public function checkBalance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'like_type_id' => 'required|exists:likes,id',
            'price' => 'required|numeric'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Неверные данные запроса'
            ]);
        }
        
        $user = Auth::user();
        $likeBalance = $user->likeBalance;
        
        if (!$likeBalance) {
            return response()->json([
                'success' => false,
                'message' => 'Баланс лайков не найден'
            ]);
        }
        
        $price = $request->price;
        
        // Проверяем, достаточно ли средств
        if ($likeBalance->balance >= $price) {
            return response()->json([
                'success' => true,
                'message' => 'Достаточно средств для отправки лайка'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Недостаточно средств для отправки лайка'
            ]);
        }
    }
    
    /**
     * Отправка лайка
     */
    public function sendLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,post_id',
            'like_type_id' => 'required|exists:likes,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Неверные данные запроса'
            ]);
        }
        
        $user = Auth::user();
        $likeBalance = $user->likeBalance;
        $postId = $request->post_id;
        $likeTypeId = $request->like_type_id;
        
        // Получаем информацию о типе лайка
        $likeType = \App\Models\Like::find($likeTypeId);
        
        if (!$likeType) {
            return response()->json([
                'success' => false,
                'message' => 'Тип лайка не найден'
            ]);
        }
        
        // Проверяем, достаточно ли средств
        if ($likeBalance->balance < $likeType->price) {
            return response()->json([
                'success' => false,
                'message' => 'Недостаточно средств для отправки лайка'
            ]);
        }
        
        // Списываем средства
        $likeBalance->withdraw(
            $likeType->price, 
            'like_sent', 
            'Отправка лайка на пост #' . $postId, 
            [
                'post_id' => $postId,
                'like_type_id' => $likeTypeId
            ]
        );
        
        // Создаем запись о лайке
        $postLike = new \App\Models\PostLike();
        $postLike->post_id = $postId;
        $postLike->user_id = $user->id;
        $postLike->like_id = $likeTypeId;
        $postLike->amount = $likeType->price;
        $postLike->save();
        
        // Get the post to update its animations
        $post = \App\Models\Posts::where('post_id', $postId)->first();
        
        // Add the animation to the post's animations
        $animationUrl = $likeType->image_url;
        
        // Save the animation to the post
        if ($post->post_animation) {
            // Check if this animation URL is already saved to avoid duplicates
            $existingAnimations = explode(',', $post->post_animation);
            if (!in_array($animationUrl, $existingAnimations)) {
                // Append the new animation URL
                $post->post_animation = $post->post_animation . ',' . $animationUrl;
                $post->save();
            }
        } else {
            // If no animations exist yet, just set the new one
            $post->post_animation = $animationUrl;
            $post->save();
        }
        
        // Get all animations for this post
        $animations = [];
        if ($post->post_animation) {
            $animations = explode(',', $post->post_animation);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Лайк успешно отправлен',
            'like_url' => $likeType->image_url,
            'animation' => $likeType->animation_path,
            'animations' => $animations,
            'count' => count($animations)
        ]);
    }
    
    /**
     * Получение количества лайков поста и их анимаций
     */
    public function getPostLikesCount($postId)
    {
        $count = \App\Models\PostLike::where('post_id', $postId)->count();
        
        // Get the post and its animations
        $post = \App\Models\Posts::where('post_id', $postId)->first();
        $animations = [];
        
        if ($post && $post->post_animation) {
            $animations = explode(',', $post->post_animation);
        }
        
        return response()->json([
            'success' => true,
            'count' => $count,
            'animations' => $animations,
            'has_animations' => !empty($animations)
        ]);
    }
    
    /**
     * Отображение модального окна с недостаточным балансом
     */
    public function insufficientBalanceModal()
    {
        return view('frontend.like_balance.insufficient_balance');
    }
}
