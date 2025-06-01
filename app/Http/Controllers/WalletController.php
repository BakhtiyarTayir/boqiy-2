<?php

namespace App\Http\Controllers;

use App\Models\Wallet; 
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Posts;
use App\Models\Friendships;

class WalletController extends Controller
{
    /**
     * Show user wallet
     */
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        // Если у пользователя нет кошелька, создаем его
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0
            ]);
        }

        // Получаем транзакции
        $transactions = $wallet->transactions()->latest()->paginate(15);

        // Получаем посты пользователя как в Profile
        $posts = Posts::where(function ($query) use ($user) {
            $query->whereJsonContains('posts.tagged_user_ids', [$user->id])
                ->where('posts.privacy', '!=', 'private')
                ->orWhere('posts.user_id', $user->id);
        })
            ->where('posts.publisher', 'post')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')
            ->take(5)->orderBy('posts.post_id', 'DESC')->get();

        // Получаем друзей как в Profile
        $friendships = Friendships::where(function ($query) use ($user) {
            $query->where('accepter', $user->id)
                ->orWhere('requester', $user->id);
        })
            ->where('is_accepted', 1)
            ->orderBy('friendships.importance', 'desc')->get();

        // Формируем массив данных для view
        $page_data = [
            'wallet' => $wallet,
            'transactions' => $transactions,
            'posts' => $posts,
            'friendships' => $friendships,
            'user' => $user,
            'view_path' => 'frontend/wallet/index'
        ];

        // Возвращаем view с данными
        return view('frontend.index', $page_data);
    }
    
    /**
     * Show wallet transactions history
     */
    public function transactions()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0
            ]);
        }
        
        $transactions = $wallet->transactions()->latest()->paginate(20);
        
        // Формируем массив данных для view
        $page_data = [
            'wallet' => $wallet,
            'transactions' => $transactions,
            'user' => $user,
            'view_path' => 'frontend/wallet/transactions'
        ];
        
        // Возвращаем view с данными
        return view('frontend.index', $page_data);
    }
    
    /**
     * Process post creation reward
     */
    public static function rewardForPost($post)
    {
        $user = $post->user;
        $wallet = $user->wallet;
        
        if (!$wallet) {
            $wallet = Wallet::create(['user_id' => $user->id]);
        }
        
        // Check if the post contains video
        $hasVideo = false;
        $mediaFiles = $post->media_files;
        
        if ($mediaFiles && $mediaFiles->count() > 0) {
            foreach ($mediaFiles as $media) {
                if ($media->file_type == 'video') {
                    $hasVideo = true;
                    break;
                }
            }
        }
        
        // Define the reward amount based on post type
        if ($hasVideo) {
            $rewardAmount = 3000.00; // 3000 sum for video post
            $rewardType = 'video_post_creation';
            $description = 'Reward for creating a video post';
        } else {
            $rewardAmount = 1.00; // 1 credit for regular post
            $rewardType = 'post_creation';
            $description = 'Reward for creating a post';
        }
        
        // Add the reward to the user's wallet
        $wallet->deposit(
            $rewardAmount, 
            $rewardType, 
            $description, 
            ['post_id' => $post->id]
        );
        
        return true;
    } 
} 