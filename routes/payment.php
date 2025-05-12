<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymeController;
use Illuminate\Support\Facades\Route;

// Payme routes with proper middleware exclusion
Route::post('/payme/callback', [PaymeController::class, 'callback'])->name('payme.callback.handler')->withoutMiddleware(\App\Http\Middleware\CheckUserSession::class);

// Обработка статуса платежа (любым методом - GET или POST) 
Route::match(['get', 'post'], '/payme/status/{order_id?}', [PaymeController::class, 'status'])
    ->name('payme.status.handler')
    ->withoutMiddleware(\App\Http\Middleware\CheckUserSession::class);

// Устаревшие маршруты для обратной совместимости, перенаправляют на payme/status
Route::get('/like-balance/status', function() {
    $order_id = request('order_id');
    $is_cancelled = request('cancel') == '1';
    
    // Логируем для отладки
    \Log::info('Intercepted /like-balance/status request', [
        'order_id' => $order_id,
        'is_cancelled' => $is_cancelled,
        'query_string' => request()->getQueryString(),
        'all_params' => request()->all()
    ]);
    
    if ($order_id) {
        if ($is_cancelled) {
            return redirect('/payme/status/' . $order_id . '?cancel=1');
        }
        return redirect('/payme/status/' . $order_id . '?success=1');
    }
    return redirect()->route('like_balance.topup');
})->name('like.balance.status');

// Настраиваем маршрут для топап-статса (который используется в POST-форме)
Route::get('/like-balance/topup-status/{orderId?}', [PaymeController::class, 'status'])->name('like_balance.topup_status');

Route::controller(PaymentController::class)->group(function () {
    Route::get('payment', 'index')->name('payment');
    Route::get('payment/show_payment_gateway_by_ajax/{identifier}', 'show_payment_gateway_by_ajax')->name('payment.show_payment_gateway_by_ajax');
    Route::get('payment/success/{identifier}', 'payment_success')->name('payment.success');
    Route::get('payment/create/{identifier}', 'payment_create')->name('payment.create');

    // razor pay
    Route::post('payment/{identifier}/order', 'payment_razorpay')->name('razorpay.order');

    // paytm pay
    Route::post('payment/make/order/{identifier}', 'payment_paytm')->name('make.order');
    Route::get('payment/make/{identifier}/status', 'paytm_paymentCallback')->name('payment.status');

    //Paystack Pay 
    Route::post('paystack/payment/{identifier}', 'payment_success')->name('make.payment');
});