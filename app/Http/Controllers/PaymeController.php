<?php

namespace App\Http\Controllers;

use App\Models\LikeBalance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymeController extends Controller
{
    protected $merchant_id;
    protected $secret_key;
    protected $test_mode;

    public function __construct()
    {
        // Get Payme configuration from database
        $payment_gateway = DB::table('payment_gateways')->where('identifier', 'payme')->first();
        
        if ($payment_gateway) {
            $keys = json_decode($payment_gateway->keys, true);
            $this->test_mode = $payment_gateway->test_mode;
            
            if ($this->test_mode == 1) {
                $this->merchant_id = $keys['merchant_id'];
                $this->secret_key = $keys['secret_key'];
            } else {
                $this->merchant_id = $keys['merchant_id_live'];
                $this->secret_key = $keys['secret_key_live'];
            }
            
            // Логируем конфигурацию для отладки (без секретного ключа для безопасности)
            \Log::info('Payme configuration loaded', [
                'merchant_id' => $this->merchant_id,
                'test_mode' => $this->test_mode,
                'has_secret_key' => !empty($this->secret_key)
            ]);
        } else {
            \Log::error('Payme configuration not found in payment_gateways table');
        }
    }

    /**
     * Handle Payme callback
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(Request $request)
    {
        // Log the incoming request with headers
        Log::info('Payme callback received', [
            'data' => $request->all(),
            'headers' => $request->header(),
            'test_mode' => $this->test_mode
        ]);
        
        // Get the request data
        $data = $request->json()->all();
        
        // Check if the request is valid
        if (!isset($data['method'])) {
            return $this->errorResponse(-32300, 'Invalid JSON-RPC object');
        }
        
        // Authenticate the request
        $auth = $request->header('Authorization');
        if (!$auth) {
            Log::error('Authorization header missing in Payme callback');
            return $this->errorResponse(-32504, 'Authorization header missing');
        }
        
        // Check authentication
        $authResult = $this->checkAuth($auth);
        if (!$authResult['success']) {
            Log::error('Authorization failed', $authResult);
            return $this->errorResponse(-32504, 'Authorization failed: ' . $authResult['reason']);
        }
        
        // Handle different methods
        switch ($data['method']) {
            case 'CheckPerformTransaction':
                return $this->checkPerformTransaction($data);
            case 'CreateTransaction':
                return $this->createTransaction($data);
            case 'PerformTransaction':
                return $this->performTransaction($data);
            case 'CheckTransaction':
                return $this->checkTransaction($data);
            case 'CancelTransaction':
                return $this->cancelTransaction($data);
            case 'GetStatement':
                return $this->getStatement($data);
            default:
                return $this->errorResponse(-32601, 'Method not found');
        }
    }
    
    /**
     * Check if the transaction can be performed
     * 
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function checkPerformTransaction($data)
    {
        $params = $data['params'];
        
        // Проверяем наличие всех обязательных параметров
        if (!isset($params['account']) || !isset($params['amount'])) {
            return $this->errorResponse(-32504, 'Недостаточно параметров');
        }
        
        $account = $params['account'];
        $amount = $params['amount'];
        
        // Логируем запрос для отладки
        \Log::info('CheckPerformTransaction request', [
            'account' => $account,
            'amount' => $amount
        ]);
        
        // Проверяем наличие order_id в account
        if (!isset($account['order_id'])) {
            return $this->errorResponse(-31050, 'Неверный код заказа', [
                'name' => 'order_id'
            ]);
        }
        
        // Ищем заказ
        $order = $this->findOrder($account['order_id']);
        
        // Если заказ не найден
        if (!$order) {
            return $this->errorResponse(-31050, 'Заказ не найден', [
                'name' => 'order_id'
            ]);
        }
        
        // Если заказ уже обработан
        if ($order->status !== 'pending') {
            return $this->errorResponse(-31051, 'Заказ уже обработан', [
                'name' => 'order'
            ]);
        }
        
        // Проверяем сумму
        $orderAmountTiyin = $order->amount * 100; // Переводим в тийины
        if ($amount != $orderAmountTiyin) {
            return $this->errorResponse(-31001, 'Неверная сумма', [
                'name' => 'amount'
            ]);
        }
        
        // Ищем пользователя
        $user = User::find($order->user_id);
        if (!$user) {
            return $this->errorResponse(-31050, 'Пользователь не найден', [
                'name' => 'user_id'
            ]);
        }
        
        // Возвращаем успешный ответ с дополнительной информацией
        return $this->successResponse([
            'allow' => true,
            'additional' => [
                'order_id' => $order->order_id,
                'amount' => $order->amount,
                'created_at' => $order->created_at->format('Y-m-d H:i:s')
            ]
        ]);
    }
    
    /**
     * Create a transaction
     * 
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createTransaction($data)
    {
        try {
            $params = $data['params'];
            
            // Проверяем обязательные параметры
            if (!isset($params['id']) || !isset($params['time']) || !isset($params['account']) || !isset($params['amount'])) {
                return $this->errorResponse(-32504, 'Недостаточно параметров');
            }
            
            $transaction_id = $params['id'];
            $time = $params['time'];
            $account = $params['account'];
            $amount = $params['amount'];
            
            // Логируем запрос
            \Log::info('CreateTransaction request', [
                'transaction_id' => $transaction_id,
                'time' => $time,
                'account' => $account,
                'amount' => $amount
            ]);
            
            // Check if transaction already exists
            $transaction = DB::table('payme_transactions')
                ->where('transaction_id', $transaction_id)
                ->first();
                
            if ($transaction) {
                // If transaction exists and is in the same state, return success
                if ($transaction->state == 1) {
                    return $this->successResponse([
                        'create_time' => (int)$transaction->create_time,
                        'transaction' => (string)$transaction->id,
                        'state' => (int)$transaction->state
                    ]);
                }
                
                // If transaction exists but in a different state, return error
                return $this->errorResponse(-31008, 'Транзакция уже существует', [
                    'name' => 'id'
                ]);
            }
            
            // Найти заказ
            $orderId = isset($account['order_id']) ? $account['order_id'] : null;
            
            if (!$orderId) {
                return $this->errorResponse(-31050, 'Неверный код заказа', [
                    'name' => 'order_id'
                ]);
            }
            
            // Начинаем транзакцию в БД
            DB::beginTransaction();
            
            $order = $this->findOrder($orderId);
            if (!$order) {
                DB::rollBack();
                return $this->errorResponse(-31050, 'Заказ не найден', [
                    'name' => 'order_id'
                ]);
            }
            
            // Проверить статус заказа
            if ($order->status != 'pending') {
                DB::rollBack();
                return $this->errorResponse(-31051, 'Заказ уже обработан', [
                    'name' => 'order'
                ]);
            }
            
            // Проверяем сумму
            $orderAmountTiyin = round($order->amount * 100); // Переводим в тийины
            if ($amount != $orderAmountTiyin) {
                DB::rollBack();
                return $this->errorResponse(-31001, 'Неверная сумма', [
                    'name' => 'amount',
                    'expected' => $orderAmountTiyin,
                    'received' => $amount
                ]);
            }
            
            // Обновить статус заказа
            $order->updateStatus('processing', $transaction_id);
            
            // Создаем новую транзакцию
            $userId = $order->user_id;
            $transactionAmount = $amount / 100; // Конвертируем из тийинов в сумы
            
            $id = DB::table('payme_transactions')->insertGetId([
                'transaction_id' => $transaction_id,
                'user_id' => $userId,
                'amount' => $transactionAmount,
                'state' => 1, // Created
                'create_time' => $time,
                'perform_time' => null,
                'cancel_time' => null,
                'reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Фиксируем транзакцию в БД
            DB::commit();
            
            // Return success response
            return $this->successResponse([
                'create_time' => (int)$time,
                'transaction' => (string)$id,
                'state' => 1,
                // receivers не указываем, так как у нас прямой платеж
            ]);
            
        } catch (\Exception $e) {
            // В случае ошибки отменяем транзакцию в БД
            if (isset($order) && $order) {
                $order->updateStatus('pending', null);
            }
            
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            \Log::error('CreateTransaction error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->errorResponse(-31008, 'Ошибка при создании транзакции: ' . $e->getMessage());
        }
    }
    
    /**
     * Perform the transaction
     * 
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function performTransaction($data)
    {
        try {
            $params = $data['params'];
            
            // Проверяем обязательные параметры
            if (!isset($params['id'])) {
                return $this->errorResponse(-32504, 'Недостаточно параметров');
            }
            
            $transaction_id = $params['id'];
            
            // Логируем запрос
            \Log::info('PerformTransaction request', [
                'transaction_id' => $transaction_id
            ]);
            
            // Find the transaction
            $transaction = DB::table('payme_transactions')
                ->where('transaction_id', $transaction_id)
                ->first();
                
            if (!$transaction) {
                return $this->errorResponse(-31003, 'Транзакция не найдена', [
                    'name' => 'id',
                    'value' => $transaction_id
                ]);
            }
            
            // Check transaction state
            if ($transaction->state == 2) {
                // Transaction already performed
                return $this->successResponse([
                    'transaction' => (string)$transaction->id,
                    'perform_time' => (int)$transaction->perform_time,
                    'state' => (int)$transaction->state
                ]);
            }
            
            if ($transaction->state != 1) {
                // Transaction in invalid state
                return $this->errorResponse(-31008, 'Неверное состояние транзакции', [
                    'name' => 'state',
                    'value' => $transaction->state
                ]);
            }
            
            // Начинаем транзакцию в БД
            DB::beginTransaction();
            
            // Найти связанный заказ по transaction_id
            $order = \App\Models\Order::where('transaction_id', $transaction_id)->first();
            
            if (!$order) {
                // Попробуем найти заказ по ID транзакции в метаданных
                $orders = \App\Models\Order::where('status', 'processing')->get();
                foreach ($orders as $o) {
                    if ($o->transaction_id === $transaction_id) {
                        $order = $o;
                        break;
                    }
                }
            }
            
            if (!$order) {
                DB::rollBack();
                return $this->errorResponse(-31050, 'Заказ не найден', [
                    'name' => 'order',
                    'value' => $transaction_id
                ]);
            }
            
            // Update transaction state
            $perform_time = time() * 1000;
            DB::table('payme_transactions')
                ->where('id', $transaction->id)
                ->update([
                    'state' => 2, // Performed
                    'perform_time' => $perform_time,
                    'updated_at' => now(),
                ]);
                
            // Получаем ID пользователя и сумму
            $user_id = $transaction->user_id;
            $amount = $transaction->amount;
            
            // Обновляем статус заказа
            $order->updateStatus('completed', $transaction_id);
            
            // Find or create like balance for the user
            $likeBalance = LikeBalance::firstOrCreate(
                ['user_id' => $user_id],
                ['balance' => 0]
            );
            
            // Add the amount to the balance
            $likeBalance->deposit(
                $amount,
                'payme',
                'Пополнение баланса через Payme',
                ['transaction_id' => $transaction_id, 'order_id' => $order->order_id]
            );
            
            // Завершаем транзакцию в БД
            DB::commit();
            
            // Return success response
            return $this->successResponse([
                'transaction' => (string)$transaction->id,
                'perform_time' => (int)$perform_time,
                'state' => 2,
                'receivers' => null
            ]);
        } catch (\Exception $e) {
            // В случае ошибки отменяем транзакцию в БД
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            // Логируем ошибку
            \Log::error('Error during PerformTransaction', [
                'transaction_id' => $transaction_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->errorResponse(-31008, 'Ошибка при выполнении транзакции', [
                'name' => 'system'
            ]);
        }
    }
    
    /**
     * Check transaction status
     * 
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function checkTransaction($data)
    {
        try {
            $params = $data['params'];
            
            // Проверяем обязательные параметры
            if (!isset($params['id'])) {
                return $this->errorResponse(-32504, 'Недостаточно параметров');
            }
            
            $transaction_id = $params['id'];
            
            // Логируем запрос
            \Log::info('CheckTransaction request', [
                'transaction_id' => $transaction_id
            ]);
            
            // Find the transaction
            $transaction = DB::table('payme_transactions')
                ->where('transaction_id', $transaction_id)
                ->first();
                
            if (!$transaction) {
                return $this->errorResponse(-31003, 'Транзакция не найдена', [
                    'name' => 'id',
                    'value' => $transaction_id
                ]);
            }
            
            // Return transaction details
            $result = [
                'create_time' => (int)$transaction->create_time,
                'perform_time' => (int)$transaction->perform_time,
                'cancel_time' => (int)$transaction->cancel_time,
                'transaction' => (string)$transaction->id,
                'state' => (int)$transaction->state,
                'reason' => $transaction->reason ? (int)$transaction->reason : null
            ];
            
            // Если это цепной платеж, добавить получателей (в нашем случае - null)
            $result['receivers'] = null;
            
            return $this->successResponse($result);
            
        } catch (\Exception $e) {
            \Log::error('Error during CheckTransaction', [
                'transaction_id' => $transaction_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->errorResponse(-31008, 'Ошибка при проверке транзакции');
        }
    }
    
    /**
     * Cancel transaction
     * 
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function cancelTransaction($data)
    {
        try {
            $params = $data['params'];
            
            // Проверяем обязательные параметры
            if (!isset($params['id']) || !isset($params['reason'])) {
                return $this->errorResponse(-32504, 'Недостаточно параметров');
            }
            
            $transaction_id = $params['id'];
            $reason = $params['reason'];
            
            // Логируем запрос
            \Log::info('CancelTransaction request', [
                'transaction_id' => $transaction_id,
                'reason' => $reason
            ]);
            
            // Find the transaction
            $transaction = DB::table('payme_transactions')
                ->where('transaction_id', $transaction_id)
                ->first();
                
            if (!$transaction) {
                return $this->errorResponse(-31003, 'Транзакция не найдена', [
                    'name' => 'id',
                    'value' => $transaction_id
                ]);
            }
            
            // Начинаем транзакцию в БД
            DB::beginTransaction();
            
            // Check if transaction can be canceled
            if ($transaction->state == -1) {
                // Transaction already canceled
                DB::rollBack();
                return $this->successResponse([
                    'transaction' => (string)$transaction->id,
                    'cancel_time' => (int)$transaction->cancel_time,
                    'state' => -1,
                ]);
            }
            
            if ($transaction->state == 2) {
                // Transaction already performed, need to reverse it
                
                // Найдем заказ
                $order = \App\Models\Order::where('transaction_id', $transaction_id)->first();
                if (!$order) {
                    DB::rollBack();
                    return $this->errorResponse(-31007, 'Невозможно отменить транзакцию', [
                        'name' => 'order'
                    ]);
                }
                
                // Find the user's like balance
                $likeBalance = LikeBalance::where('user_id', $transaction->user_id)->first();
                
                // Check if the user has enough balance to reverse
                if ($likeBalance && $likeBalance->balance >= $transaction->amount) {
                    // Withdraw the amount from the balance
                    $likeBalance->withdraw(
                        $transaction->amount,
                        'payme_refund',
                        'Возврат средств Payme',
                        ['transaction_id' => $transaction_id, 'reason' => $reason]
                    );
                    
                    // Обновим статус заказа
                    $order->updateStatus('cancelled', $transaction_id);
                } else {
                    // Cannot cancel transaction
                    DB::rollBack();
                    return $this->errorResponse(-31007, 'Невозможно отменить транзакцию: недостаточно средств на балансе', [
                        'name' => 'balance'
                    ]);
                }
            } else if ($transaction->state == 1) {
                // Если транзакция только создана, но не выполнена
                // Найдем заказ
                $order = \App\Models\Order::where('transaction_id', $transaction_id)->first();
                if ($order) {
                    $order->updateStatus('cancelled', $transaction_id);
                }
            }
            
            // Update transaction state
            $cancel_time = time() * 1000;
            DB::table('payme_transactions')
                ->where('id', $transaction->id)
                ->update([
                    'state' => -1, // Canceled
                    'cancel_time' => $cancel_time,
                    'reason' => $reason,
                    'updated_at' => now(),
                ]);
            
            // Фиксируем транзакцию в БД
            DB::commit();
            
            // Return success response
            return $this->successResponse([
                'transaction' => (string)$transaction->id,
                'cancel_time' => (int)$cancel_time,
                'state' => -1,
            ]);
            
        } catch (\Exception $e) {
            // В случае ошибки отменяем транзакцию в БД
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            \Log::error('Error during CancelTransaction', [
                'transaction_id' => $transaction_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->errorResponse(-31008, 'Ошибка при отмене транзакции');
        }
    }
    
    /**
     * Check authorization header
     * 
     * @param string $auth
     * @return array
     */
    protected function checkAuth($auth)
    {
        // Extract credentials from Basic Auth header
        if (preg_match('/^Basic\s+(.*)$/i', $auth, $matches)) {
            $credentials = base64_decode($matches[1]);
            list($login, $password) = explode(':', $credentials, 2);
            
            // Логируем попытку авторизации для отладки (без конфиденциальных данных)
            \Log::info('Payme auth attempt', [
                'received_login' => $login,
                'expected_merchant_id' => $this->merchant_id,
                'test_mode' => $this->test_mode
            ]);
            
            // В зависимости от режима работы (тестовый или боевой) Payme использует разные форматы
            if ($this->test_mode == 1) {
                // Для тестового режима
                $validLogins = [$this->merchant_id, 'Payme', 'Paycom'];
                $isValid = in_array($login, $validLogins) && $password === $this->secret_key;
                
                if (!$isValid) {
                    return [
                        'success' => false,
                        'reason' => 'Invalid credentials for test mode'
                    ];
                }
                
                return ['success' => true];
            } else {
                // Для боевого режима - строгая проверка
                $isValid = ($login === $this->merchant_id && $password === $this->secret_key);
                
                if (!$isValid) {
                    // Проверим альтернативные логины, используемые в Payme API v2
                    $alternativeLogins = ['Paycom'];
                    $isValid = in_array($login, $alternativeLogins) && $password === $this->secret_key;
                    
                    if (!$isValid) {
                        return [
                            'success' => false,
                            'reason' => 'Invalid credentials for production mode'
                        ];
                    }
                }
                
                return ['success' => true];
            }
        }
        
        return [
            'success' => false,
            'reason' => 'Invalid Authorization header format'
        ];
    }
    
    /**
     * Return success response
     * 
     * @param array $result
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($result)
    {
        return response()->json([
            'jsonrpc' => '2.0',
            'result' => $result,
            'id' => request()->json('id'),
        ]);
    }
    
    /**
     * Return error response
     * 
     * @param int $code
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($code, $message, $data = null)
    {
        $error = [
            'code' => $code,
            'message' => $message
        ];
        
        if ($data !== null) {
            $error['data'] = $data;
        }
        
        $response = [
            'jsonrpc' => '2.0',
            'error' => $error,
            'id' => request()->json('id'),
        ];
        
        // Логируем ошибку
        \Log::warning('Payme error response', $response);
        
        return response()->json($response);
    }

    /**
     * Get statement of transactions for a given period.
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getStatement($data)
    {
        $params = $data['params'];
        $from = $params['from'] ?? null;
        $to = $params['to'] ?? null;

        // 1. Валидация дат
        if (!$from || !$to || !is_numeric($from) || !is_numeric($to) || $from >= $to) {
            // Payme не специфицирует код ошибки для невалидных дат,
            // используем общий код "Невозможно выполнить операцию" или специфичный для валидации, если есть.
            return $this->errorResponse(-31008, 'Invalid date range specified.');
        }

        // 2. Запрос транзакций из БД
        // Payme обычно ожидает транзакции, созданные (create_time) в этом диапазоне
        // или иногда измененные (updated_at) в этом диапазоне.
        // Уточните в актуальной документации Payme, какое поле использовать для фильтрации.
        // Будем использовать create_time как наиболее вероятный вариант.
        $transactions = DB::table('payme_transactions')
            ->whereBetween('create_time', [$from, $to])
            ->orderBy('create_time', 'asc') // Порядок важен для Payme
            ->get();

        // 3. Форматирование результата
        $resultTransactions = [];
        foreach ($transactions as $tx) {
            $resultTransactions[] = [
                'id'            => $tx->transaction_id, // Payme ID
                'time'          => $tx->create_time,    // Время создания Payme (мы его сохраняем)
                'amount'        => $tx->amount * 100,   // Конвертируем сумы обратно в тийины!
                'account'       => [
                                    'user_id' => $tx->user_id // Пример, если у вас только user_id
                                   ],
                'create_time'   => $tx->create_time,    // Время создания в нашей системе
                'perform_time'  => $tx->perform_time,
                'cancel_time'   => $tx->cancel_time,
                'transaction'   => $tx->id,             // Наш ID транзакции
                'state'         => $tx->state,
                'reason'        => $tx->reason,
            ];
        }

        // 4. Возврат результата
        return $this->successResponse(['transactions' => $resultTransactions]);
    }

    /**
     * Handle return from Payme checkout
     * 
     * @param string $order_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status($order_id = null)
    {
        // Получаем order_id из разных источников
        if (!$order_id) {
            // Проверяем параметр в GET запросе
            $order_id = request('order_id');
            
            // Проверяем параметр orderId (другой формат)
            if (!$order_id) {
                $order_id = request('orderId');
            }
        }
        
        // Логируем запрос для отладки с полной информацией
        \Log::info('Payme status callback received', [
            'order_id' => $order_id,
            'all_params' => request()->all(),
            'headers' => request()->header(),
            'url' => request()->fullUrl(),
            'referer' => request()->header('referer')
        ]);
        
        // Если заказ все еще не определен, перенаправляем на страницу пополнения
        if (!$order_id) {
            return redirect()->route('like_balance.topup')
                ->with('error', 'Order ID not provided. Please try again.');
        }
        
        // Проверяем наличие специального параметра success или cancel от Payme
        $is_payment_cancelled = request()->has('cancel') || request()->query('cancel') == '1';
        $is_payme_return = !$is_payment_cancelled && (request()->has('success') || request()->query('success') == '1' || request('is_payme_return'));
        
        \Log::info('Payme return flags', [
            'is_payme_return' => $is_payme_return,
            'is_payment_cancelled' => $is_payment_cancelled,
            'has_success' => request()->has('success'),
            'success_value' => request()->query('success')
        ]);
        
        // Если пользователь отменил платеж - это приоритетное действие
        if ($is_payment_cancelled) {
            // Обновляем статус заказа, если он существует
            if ($order_id) {
                $order = $this->findOrder($order_id);
                if ($order && $order->status == 'pending') {
                    $order->updateStatus('cancelled', null);
                }
                
                // Также проверяем транзакцию
                $transaction = DB::table('transactions')
                    ->where('order_id', $order_id)
                    ->first();
                
                if ($transaction && $transaction->status == 'pending') {
                    DB::table('transactions')
                        ->where('id', $transaction->id)
                        ->update([
                            'status' => 'cancelled',
                            'updated_at' => now()
                        ]);
                }
            }
            
            // Всегда отправляем на страницу пополнения с сообщением об отмене
            return redirect()->route('like_balance.topup')
                ->with('error', 'Your payment was cancelled. Please try again if you want to top up your balance.');
        }
        
        // Ищем заказ в таблице orders
        $order = \App\Models\Order::where('order_id', $order_id)->first();
        
        // Проверяем также в таблице транзакций
        $transaction = null;
        if (!$order) {
            // Заказ не найден, проверяем в транзакциях
            $transaction = DB::table('transactions')
                ->where('order_id', $order_id)
                ->first();
                
            // Если не нашли в таблице transactions, проверяем в payme_transactions
            if (!$transaction) {
                $transaction = DB::table('payme_transactions')
                    ->where('transaction_id', $order_id)
                    ->first();
            }
            
            // Если нигде не нашли, а маркер успешного возврата есть
            if (!$transaction && $is_payme_return) {
                // Проверим сессию с возвратным URL
                $return_url = session('payme_return_url');
                if ($return_url) {
                    \Log::info('Using saved return URL from session', ['url' => $return_url]);
                    // Платеж скорее всего обрабатывается, перенаправляем на обработку
                    return redirect()->route('like_balance.topup')
                        ->with('info', 'Your payment is being processed. Please wait a moment.');
                }
                
                // Если и сессия пуста, предполагаем проблему
                return redirect()->route('like_balance.topup')
                    ->with('error', 'We could not verify your payment. Please contact support if your balance is not updated shortly.');
            }
            
            // Если транзакцию так и не нашли
            if (!$transaction) {
                return redirect()->route('like_balance.topup')
                    ->with('error', 'Transaction not found. Please try again or contact support.');
            }
            
            // Если нашли транзакцию, проверяем её статус
            if ($transaction && (
                (isset($transaction->state) && $transaction->state == 2) || 
                (isset($transaction->status) && $transaction->status == 'completed')
            )) {
                return redirect()->route('like_balance.index')
                    ->with('success', 'Your balance has been topped up successfully!');
            }
            
            // В других случаях, если заказ в обработке
            return redirect()->route('like_balance.topup')
                ->with('info', 'Your payment is being processed. Please wait or try again later.');
        }
        
        // Если заказ найден - проверяем его статус
        if ($order->status == 'completed') {
            return redirect()->route('like_balance.index')
                ->with('success', 'Your balance has been topped up successfully!');
        } elseif ($order->status == 'cancelled') {
            return redirect()->route('like_balance.topup')
                ->with('error', 'Your payment was cancelled. Please try again.');
        } else {
            // Для статусов pending и processing - сообщаем о том, что заказ в обработке
            return redirect()->route('like_balance.topup')
                ->with('info', 'Your payment is being processed. Please wait or try again later.');
        }
    }
    
    /**
     * Check if an order exists
     * 
     * @param string $order_id
     * @return \App\Models\Order|null
     */
    protected function findOrder($order_id)
    {
        return \App\Models\Order::where('order_id', $order_id)->first();
    }
} 