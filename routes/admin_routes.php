<?php
// Marketplace Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->group(function() {
	Route::get('/admin/marketplace/orders', [App\Http\Controllers\Admin\MarketplaceOrderController::class, 'index'])
		->name('admin.marketplace.orders')->middleware('auth', 'admin', 'verified', 'activity');
	Route::get('/admin/marketplace/orders/pending', [App\Http\Controllers\Admin\MarketplaceOrderController::class, 'pendingOrders'])
		->name('admin.marketplace.orders.pending')->middleware('auth', 'admin', 'verified', 'activity');
	Route::get('/admin/marketplace/orders/cancelled', [App\Http\Controllers\Admin\MarketplaceOrderController::class, 'cancelledOrders'])
		->name('admin.marketplace.orders.cancelled')->middleware('auth', 'admin', 'verified', 'activity');
	Route::get('/admin/marketplace/statistics', [App\Http\Controllers\Admin\MarketplaceOrderController::class, 'salesStatistics'])
		->name('admin.marketplace.statistics')->middleware('auth', 'admin', 'verified', 'activity');
	Route::get('/admin/marketplace/order/{id}', [App\Http\Controllers\Admin\MarketplaceOrderController::class, 'show'])
		->name('admin.marketplace.order.show')->middleware('auth', 'admin', 'verified', 'activity');
	Route::post('/admin/marketplace/order/{id}/update-status', [App\Http\Controllers\Admin\MarketplaceOrderController::class, 'updateStatus'])
		->name('admin.marketplace.order.update-status')->middleware('auth', 'admin', 'verified', 'activity');
});

// Marketplace Product Management Routes
Route::get('/admin/marketplace/products', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'index'])
	->name('admin.marketplace.products')->middleware('auth', 'admin', 'verified', 'activity');
Route::get('/admin/marketplace/products/create', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'create'])
	->name('admin.marketplace.products.create')->middleware('auth', 'admin', 'verified', 'activity');
Route::post('/admin/marketplace/products', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'store'])
	->name('admin.marketplace.products.store')->middleware('auth', 'admin', 'verified', 'activity');
Route::get('/admin/marketplace/products/{id}', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'show'])
	->name('admin.marketplace.products.show')->middleware('auth', 'admin', 'verified', 'activity');
Route::get('/product/type/show/file/{product_type_id}', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'showProductTypeFile'])
	->name('showProductTypeFile');
Route::get('/admin/marketplace/products/edit/{id}', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'edit'])
	->name('admin.marketplace.products.edit')->middleware('auth', 'admin', 'verified', 'activity');
Route::post('/admin/marketplace/products/update/{id}', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'update'])
	->name('admin.marketplace.products.update')->middleware('auth', 'admin', 'verified', 'activity');
Route::get('/admin/marketplace/products/{id}/delete', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'destroy'])
	->name('admin.marketplace.products.destroy')->middleware('auth', 'admin', 'verified', 'activity');
Route::get('/admin/marketplace/products/image/{id}/remove', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'removeImage'])
	->name('admin.marketplace.products.remove-image')->middleware('auth', 'admin', 'verified', 'activity');
Route::get('/admin/marketplace/products/featured', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'featuredProducts'])
	->name('admin.marketplace.products.featured')->middleware('auth', 'admin', 'verified', 'activity');
Route::get('/admin/marketplace/products/out-of-stock', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'outOfStockProducts'])
	->name('admin.marketplace.products.out-of-stock')->middleware('auth', 'admin', 'verified', 'activity');


Route::get('/admin/marketplace/free/products', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'indexFreeProduct'])
	->name('admin.marketplace.free.products')->middleware('auth', 'admin', 'verified', 'activity');
Route::get('/admin/marketplace/free/products/create', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'createFreeProduct'])
	->name('admin.marketplace.free.products.create')->middleware('auth', 'admin', 'verified', 'activity');
Route::post('/admin/marketplace/free/product/save', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'storeFreeProduct'])
	->name('admin.marketplace.free.products.store')->middleware('auth', 'admin', 'verified', 'activity');
Route::get('/admin/marketplace/free/products/edit/{id}', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'editFreeProduct'])
	->name('admin.marketplace.free.products.edit')->middleware('auth', 'admin', 'verified', 'activity');
Route::post('/admin/marketplace/free/products/update/{id}', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'updateFreeProduct'])
	->name('admin.marketplace.free.products.update')->middleware('auth', 'admin', 'verified', 'activity');
Route::get('/admin/marketplace/free/products/{id}/delete', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'destroyFreeProduct'])
	->name('admin.marketplace.free.products.destroy')->middleware('auth', 'admin', 'verified', 'activity');
Route::get('/admin/marketplace/free/products/run/deadline', [App\Http\Controllers\Admin\MarketplaceProductController::class, 'runDeadline'])
	->name('admin.marketplace.free.products.run.deadline')->middleware('auth', 'admin', 'verified', 'activity');