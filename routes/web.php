<?php

use App\Http\Controllers\InstallController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MemoriesController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\Profile;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\Updater;
use Illuminate\Http\Request;
use App\Models\Account_active_request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LikeBalanceController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MarketplaceOrderController;
use App\Http\Controllers\SetDefaultLanguageController;
use App\Http\Controllers\ProductSponsorController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Route::group(['domain' => '{subdomain}.localhost'], function(){
//     Route::any('/sssss', function($subdomain) {
//         return 'Subdomain ' . $subdomain;
//     });
// });
// @todo Asilbek added
Route::get('/no-login', [\App\Http\Controllers\Auth\HelperRegisterController::class, 'noLogin'])->name('no-login');

Route::get('/no-login/products', [\App\Http\Controllers\Auth\HelperRegisterController::class, 'noLoginProduct'])->name('noLoginProduct');


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return 'Application cache cleared';
});

Route::get('/auth-checker', function () {
    if (auth::check()) {
        return true;
    } else {
        return false;
    }
})->name('auth-checker');

//Passing param
Route::get('/users/{user_id}', function ($user_id) {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::get('language/switch/{language}', function (Request $request, $language) {
    $request->session()->put('active_language', $language);
    return redirect()->back();
})->name('language.switch');

// Маршрут для установки узбекского языка по умолчанию
Route::get('/set-uzbek-default', [SetDefaultLanguageController::class, 'setUzbekAsDefault'])->name('set.uzbek.default');

Route::get('/account-disable', function () {
    return view('frontend.disable_view');
})->name('frontend.disable_view');

Route::get('/account-enble-req/{id}', function (Request $request, $id) {

    $data['user_id'] = $id;
    $data['status'] = 'pending';
    Account_active_request::create($data);
    flash()->addSuccess('Account enable request successfully');
        return redirect()->back();
})->name('frontend.account_enble_req');

// Общие маршруты для модальных окон
Route::any('/load_modal_content/{view_path}', [App\Http\Controllers\ModalController::class, 'common_view_function'])->name('load_modal_content');

// Маршрут для модального окна недостаточного баланса
Route::get('/insufficient-balance-modal', [App\Http\Controllers\LikeBalanceController::class, 'insufficientBalanceModal'])->name('insufficient_balance_modal');

//Modal controllers group routing
Route::controller(ModalController::class)->middleware('auth', 'user', 'verified', 'activity')->group(function () {
    // Другие маршруты модального контроллера
});

//Home controllers group routing
Route::controller(MainController::class)->middleware('auth', 'user', 'user', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::get('/', 'timeline')->name('timeline');
    Route::post('/create_post', 'create_post')->name('create_post');
    Route::get('/edit_post_form/{id}', 'edit_post_form')->name('edit_post_form');
    Route::post('/edit_post/{id}', 'edit_post')->name('edit_post');
    Route::get('/load_post_by_scrolling', 'load_post_by_scrolling')->name('load_post_by_scrolling');
    Route::post('/my_react', 'my_react')->name('my_react');
    Route::get('/my_comment_react', 'my_comment_react')->name('my_comment_react');
    Route::get('/post_comment', 'post_comment')->name('post_comment');
    Route::get('/load_post_comments', 'load_post_comments')->name('load_post_comments');
    Route::get('/search_friends_for_tagging', 'search_friends_for_tagging')->name('search_friends_for_tagging');
    Route::get('/save-post/{id}', 'save_post')->name('save_post');
    Route::get('/unsave-post/{id}', 'unsave_post')->name('unsave_post');

    Route::get('/live/{post_id}', 'live')->name('live');
    Route::get('/live-ended/{post_id}', 'live_ended')->name('zoom-meeting-leave-url');

    Route::get('/view/single/post/{id?}', 'single_post')->name('single.post');

    Route::get('/preview_post', 'preview_post')->name('preview_post');

    Route::get('/post_comment_count', 'post_comment_count')->name('post_comment_count');

    Route::post('/post/report/save/', 'save_post_report')->name('save.post.report');

    Route::get('/delete/my/post', 'post_delete')->name('post.delete');

    Route::get('comment/delete', 'comment_delete')->name('comment.delete');

    Route::post('share/on/group', 'share_group')->name('share.group.post');
    Route::post('share/on/my/timeline', 'share_my_timeline')->name('share.my.timeline');

    // share page view
    Route::get('custom/shared/post/view/{id}', 'custom_shared_post_view')->name('custom.shared.post.view');

    //remove media files
    Route::get('media/file/delete/{id}', 'delete_media_file')->name('media.file.delete');

    // main addon layout
    Route::get('addons/manager', 'addons_manager')->name('addons.manager');
    Route::get('/user/settings', 'user_settings')->name('user.settings');
    Route::post('/save/user/settings', 'save_user_settings')->name('save.payment.settings');

    // live streaming
    Route::get('/streaming/live/{id}', 'live_streaming')->name('go.live');
	
	// @todo Asilbek added
	Route::post('product/comment/{product_id}', 'saveProductComment')->name('saveProductComment');
	Route::get('product/comment/show/file/{comment_id}', 'showProductCommentFile')->name('showProductCommentFile');

    // Theme Controller
    Route::post('/update-theme-color', 'updateThemeColor')->name('update-theme-color');


    Route::get('album/details/page_show/{id}', 'details_album')->name('album.details.page_show');
    
    // Block User from frontend
    Route::get('/block_user/{id}', 'block_user')->name('block_user');
    Route::post('/block_user_post/{id}', 'block_user_post')->name('block_user_post');
    Route::get('/unblock_user/{id}', 'unblock_user')->name('unblock_user');

    Route::get('/ai/image-generator', 'imageGenerator')->name('ai_image.image_generator');

});


// Memories Controller
Route::controller(MemoriesController::class)->middleware('auth', 'user', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::get('/memories', 'memories')->name('memories');
    Route::get('/load/memories', 'load_memories')->name('load.memories');
});

// Badge  Controller
Route::controller(BadgeController::class)->middleware('auth', 'user', 'verified', 'activity','prevent-back-history')->group(function () {
    Route::get('/badge', 'badge')->name('badge');
    Route::get('/badge/info', 'badge_info')->name('badge.info');
    Route::post('badge/payment_configuration/{id}', 'payment_configuration')->name('badge.payment_configuration');
});






//Story controllers group routing
Route::controller(StoryController::class)->middleware('auth', 'user', 'verified', 'activity')->group(function () {
    Route::post('/create_story', 'create_story')->name('create_story');

    Route::any('/stories/{offset?}/{limit?}', 'stories')->name('stories');

    Route::any('/stories/{offset?}/{limit?}', 'stories')->name('stories');
    Route::any('/story_details/{story_id}/{offset?}/{limit?}', 'story_details')->name('story_details');
    Route::any('/single_story_details/{story_id}', 'single_story_details')->name('single_story_details');
});

//Profile controllers group routing
Route::controller(Profile::class)->middleware('auth', 'verified', 'user', 'activity', 'prevent-back-history')->group(function () {
    Route::get('/profile', 'profile')->name('profile');
	// @todo Asilbek added
	Route::get('/profile/edit/{id}', 'profileEdit')->name('profileEdit');
	Route::get('/profile/show/photo/{id}', 'showUserPhoto')->name('showUserPhoto');
	Route::post('/profile/update/{id}', 'profileUpdate')->name('profileUpdate');

	Route::get('/profile/load_post_by_scrolling', 'load_post_by_scrolling')->name('profile.load_post_by_scrolling');
    Route::get('/profile/friends', 'friends')->name('profile.friends');

    Route::get('/profile/photos', 'photos')->name('profile.photos');
    Route::get('/profile/load_photos', 'load_photos')->name('profile.load_photos');

    Route::any('/profile/album/{action_type?}', 'album')->name('profile.album');
    Route::get('/profile/load_albums', 'load_albums')->name('profile.load_albums');

    Route::get('/profile/videos', 'videos')->name('profile.videos');
    Route::get('/profile/load_videos', 'load_videos')->name('profile.load_videos');

    Route::get('/profile/load_my_friends', 'load_my_friends')->name('profile.load_my_friends');
    Route::get('/profile/load_my_friend_requests', 'load_my_friend_requests')->name('profile.load_my_friend_requests');

    Route::post('/profile/accept_friend_request', 'accept_friend_request')->name('profile.accept_friend_request');
    Route::get('/profile/delete_friend_request', 'delete_friend_request')->name('profile.delete_friend_request');

    Route::post('/profile/about/{action_type?}', 'about')->name('profile.about');
    Route::any('/profile/my_info/{action_type?}', 'my_info')->name('profile.my_info');
    Route::get('/profile/load_photo_and_videos', 'load_photo_and_videos')->name('profile.load_photo_and_videos');

    Route::post('/profile/upload_photo/{photo_type}', 'upload_photo')->name('profile.upload_photo');

    Route::post('/profile/update_profile/', 'update_profile')->name('profile.update_profile');

    Route::get('/profile/save-post-list', 'savePostList')->name('profile.savePostList');

    Route::get('/profile/profile-lock', 'profileLock')->name('profile.profileLock');

    Route::get('/profile/profile-unlock', 'profileUnlock')->name('profile.profileUnlock');

    Route::get('/profile/check-ins', 'checkinsView')->name('profile.checkins_list');

});

// Marketplace purchase routes
Route::middleware(['auth', 'verified', 'user', 'activity'])->group(function () {
    Route::get('/marketplace/checkout/{id}', [App\Http\Controllers\MarketplaceController::class, 'checkout'])->name('marketplace.checkout');
    Route::post('/marketplace/purchase/{id}', [App\Http\Controllers\MarketplaceController::class, 'purchase'])->name('marketplace.purchase');
    Route::get('/marketplace/purchase-history', [App\Http\Controllers\MarketplaceOrderController::class, 'purchaseHistory'])->name('marketplace.purchase_history');
    Route::get('/marketplace/sold-items', [App\Http\Controllers\MarketplaceOrderController::class, 'soldItems'])->name('marketplace.sold_items');
});

//Updater routes are here
Route::controller(Updater::class)->middleware('auth', 'verified', 'activity')->group(function () {

    Route::post('admin/addon/create', 'update')->name('admin.addon.create');
    Route::post('admin/addon/update', 'update')->name('admin.addon.update');
    Route::post('admin/product/update', 'update')->name('admin.product.update');

    // addon install
    Route::get('admin/addon/manager', 'addon_manager')->name('admin.addon.manager');
    Route::post('admin/addon/install', 'update')->name('addon.install');
    Route::get('admin/addon/status/{status}/{id}', 'addon_status')->name('addon.status');
    Route::get('admin/addon/delete/{id}', 'addon_delete')->name('addon.delete');
    Route::get('admin/addon/form', 'addon_form')->name('addon.form');
});
//End Updater routes

//Installation routes
Route::controller(InstallController::class)->group(function () {

    Route::get('/install_ended', 'index');
    Route::get('install/step0', 'step0')->name('step0');
    Route::get('install/step1', 'step1')->name('step1');
    Route::get('install/step2', 'step2')->name('step2');
    Route::any('install/step3', 'step3')->name('step3');
    Route::get('install/step4', 'step4')->name('step4');
    Route::get('install/step4/{confirm_import}', 'confirmImport')->name('step4.confirm_import');
    Route::get('install/install', 'confirmInstall')->name('confirm_install');
    Route::post('install/validate', 'validatePurchaseCode')->name('install.validate');
    Route::any('install/finalizing_setup', 'finalizingSetup')->name('finalizing_setup');
    Route::get('install/success', 'success')->name('success');
});
//Installation routes


// Wallet routes
Route::middleware(['auth'])->group(function () {
    Route::get('/wallet', [App\Http\Controllers\WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/transactions', [App\Http\Controllers\WalletController::class, 'transactions'])->name('wallet.transactions');
});

// Like Controller Routes
Route::controller(LikeController::class)->middleware('auth', 'user', 'verified', 'activity')->group(function () {
    Route::get('/likes', 'index')->name('likes.index');
    Route::get('/likes/create', 'create')->name('likes.create');
    Route::post('/likes/store', 'store')->name('likes.store');
    Route::get('/likes/edit/{id}', 'edit')->name('likes.edit');
    Route::post('/likes/update/{id}', 'update')->name('likes.update');
    Route::delete('/likes/destroy/{id}', 'destroy')->name('likes.destroy');
    Route::post('/post/like', 'likePost')->name('post.like');
    Route::get('/post/{postId}/likes', 'getPostLikes')->name('post.likes');
    Route::post('/save-post-animation', 'savePostAnimation')->name('save.post.animation');
});

// Like Balance Controller Routes
Route::controller(LikeBalanceController::class)->middleware('auth', 'user', 'verified', 'activity')->group(function () {
    Route::get('/like-balance', 'index')->name('like_balance.index');
    Route::get('/like-balance/topup', 'topup')->name('like_balance.topup');
    Route::post('/like-balance/process-topup', 'processTopup')->name('like_balance.process_topup');
    Route::get('/like-balance/status/{orderId?}', 'processTopup')->name('like_balance.topup_status');
    Route::get('/like-balance/payme', 'payme')->name('like_balance.payme');
    Route::get('/like-balance/transactions', 'transactions')->name('like_balance.transactions');
    Route::post('/check-like-balance', 'checkBalance')->name('like_balance.check');
    Route::post('/send-like', 'sendLike')->name('like_balance.send');
    Route::get('/get-post-likes-count/{postId}', 'getPostLikesCount')->name('like_balance.post_likes_count');
});

// Product Sponsorship Route
Route::middleware(['auth'])->group(function () {
    Route::get('product/{id}/sponsor', [ProductSponsorController::class, 'sponsorForm'])->name('product.sponsor.form');
    Route::post('product/{id}/sponsor', [ProductSponsorController::class, 'store'])->name('product.sponsor.store');
});