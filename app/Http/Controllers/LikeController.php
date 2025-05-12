<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Posts;
use App\Models\PostLike;
use App\Models\LikeBalance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    /**
     * Отображение списка всех доступных типов лайков
     */
    public function index()
    {
        $likes = Like::where('is_active', true)->get();
        
        // Проверяем, является ли запрос AJAX-запросом
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'likes' => $likes,
            ]);
        }
        // Формируем массив данных для view
        $page_data = [
            'likes' => $likes,
            'view_path' => 'frontend/likes/index'
        ];
        
        return view('frontend.index', $page_data);
    }
    
    /**
     * Отображение формы для создания нового типа лайка (только для админа)
     */
    public function create()
    {
        // Проверка прав доступа
        if (Auth::user()->user_role !== 'admin') {
            return redirect()->back()->with('error', 'У вас нет прав для создания типов лайков');
        }
        // Получаем все типы лайков для отображения в форме
        $likes = Like::all();
        $page_data = [
            'likes' => $likes,
            'view_path' => 'frontend/likes/create'
        ];
        return view('frontend.index', $page_data);
    }
    
    /**
     * Сохранение нового типа лайка (только для админа)
     */
    public function store(Request $request)
    {
        // Проверка прав доступа
        if (Auth::user()->user_role !== 'admin') {
            return redirect()->back()->with('error', 'У вас нет прав для создания типов лайков');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'animation' => 'required|file|mimes:gif,mp4,webm|max:5120', // Максимум 5MB
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Загрузка анимации
        $animationFile = $request->file('animation');
        $filename = time() . '_' . $animationFile->getClientOriginalName();
        
        // Сохраняем файл в storage/app/public/likes
        $animationPath = $animationFile->storeAs('public/likes', $filename);
        
        // Также копируем файл в public/storage/likes для прямого доступа
        $publicPath = public_path('storage/likes/' . $filename);
        copy($animationFile->getRealPath(), $publicPath);
        
        // Сохраняем только имя файла в базе данных
        $animationPath = basename($animationPath);
        
        // Создание нового типа лайка
        Like::create([
            'name' => $request->name,
            'price' => $request->price,
            'animation_path' => $animationPath,
            'is_active' => true
        ]);
        
        return redirect()->route('likes.index')->with('success', 'Тип лайка успешно создан');
    }
    
    /**
     * Отображение формы для редактирования типа лайка (только для админа)
     */
    public function edit($id)
    {
        // Проверка прав доступа
        if (Auth::user()->user_role !== 'admin') {
            return redirect()->back()->with('error', 'У вас нет прав для редактирования типов лайков');
        }
        
        $like = Like::findOrFail($id);
        $page_data = [
            'like' => $like,
            'view_path' => 'frontend/likes/edit'
        ];
        return view('frontend.index', $page_data);
    }
    
    /**
     * Обновление типа лайка (только для админа)
     */
    public function update(Request $request, $id)
    {
        // Проверка прав доступа
        if (Auth::user()->user_role !== 'admin') {
            return redirect()->back()->with('error', 'У вас нет прав для редактирования типов лайков');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'animation' => 'nullable|file|mimes:gif,mp4,webm|max:5120', // Максимум 5MB
            'is_active' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $like = Like::findOrFail($id);
        
        // Обновление данных
        $like->name = $request->name;
        $like->price = $request->price;
        $like->is_active = $request->has('is_active');
        
        // Если загружена новая анимация
        if ($request->hasFile('animation')) {
            $animationFile = $request->file('animation');
            $filename = time() . '_' . $animationFile->getClientOriginalName();
            
            // Сохраняем файл в storage/app/public/likes
            $animationPath = $animationFile->storeAs('public/likes', $filename);
            
            // Также копируем файл в public/storage/likes для прямого доступа
            $publicPath = public_path('storage/likes/' . $filename);
            copy($animationFile->getRealPath(), $publicPath);
            
            // Удаляем старый файл, если он существует
            if ($like->animation_path) {
                $oldStoragePath = 'public/likes/' . $like->animation_path;
                $oldPublicPath = public_path('storage/likes/' . $like->animation_path);
                
                if (\Storage::exists($oldStoragePath)) {
                    \Storage::delete($oldStoragePath);
                }
                
                if (file_exists($oldPublicPath)) {
                    unlink($oldPublicPath);
                }
            }
            
            // Сохраняем только имя файла в базе данных
            $like->animation_path = basename($animationPath);
        }
        
        $like->save();
        
        return redirect()->route('likes.index')->with('success', 'Тип лайка успешно обновлен');
    }
    
    /**
     * Поставить лайк на пост
     */
    public function likePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,post_id',
            'like_id' => 'required|exists:likes,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        
        $user = Auth::user();
        $post = Posts::where('post_id', $request->post_id)->first();
        $like = Like::findOrFail($request->like_id);
        dd($user->id);
        // Проверяем, есть ли у пользователя баланс лайков
        $likeBalance = $user->likeBalance;
        
        if (!$likeBalance) {
            // Создаем баланс лайков, если его нет
            $likeBalance = LikeBalance::create([
                'user_id' => $user->id,
                'balance' => 0
            ]);
        }
        
        // Проверяем, достаточно ли средств на балансе
        if ($likeBalance->balance < $like->price) {
            return response()->json([
                'success' => false, 
                'message' => 'Недостаточно средств на балансе лайков', 
                'balance' => $likeBalance->balance,
                'required' => $like->price,
                'need_topup' => true
            ]);
        }
        
        // Списываем средства с баланса
        $transaction = $likeBalance->withdraw(
            $like->price, 
            'like_purchase', 
            'Лайк на пост #' . $post->post_id, 
            [
                'post_id' => $post->post_id,
                'like_id' => $like->id
            ]
        );
        
        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Ошибка при списании средств']);
        }
        
        // Создаем запись о лайке
        $postLike = PostLike::create([
            'post_id' => $post->post_id,
            'user_id' => $user->id,
            'like_id' => $like->id,
            'amount' => $like->price
        ]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Лайк успешно поставлен', 
            'animation' => $like->animation_path,
            'like_url' => $like->image_url,
            'balance' => $likeBalance->balance
        ]);
    }
    
    /**
     * Получить все лайки для поста
     */
    public function getPostLikes($postId)
    {
        $post = Posts::where('post_id', $postId)->first();
        
        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Пост не найден']);
        }
        
        $postLikes = $post->postLikes()
            ->with(['user', 'like'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all animations for this post
        $animations = [];
        if ($post->post_animation) {
            $animations = explode(',', $post->post_animation);
        }
        
        return response()->json([
            'success' => true,
            'likes' => $postLikes,
            'animations' => $animations,
            'count' => count($animations)
        ]);
    }
    
    /**
     * Удаление типа лайка (только для админа)
     */
    public function destroy($id)
    {
        // Проверка прав доступа
        if (Auth::user()->user_role !== 'admin') {
            return redirect()->back()->with('error', 'У вас нет прав для удаления типов лайков');
        }
        
        $like = Like::findOrFail($id);
        
        // Проверяем, используется ли этот тип лайка
        $usageCount = PostLike::where('like_id', $id)->count();
        
        if ($usageCount > 0) {
            return redirect()->back()->with('error', 'Невозможно удалить тип лайка, так как он уже используется в ' . $usageCount . ' лайках');
        }
        
        // Удаляем файл анимации, если он существует
        if ($like->animation_path) {
            // Удаляем из storage/app/public/likes
            $storagePath = 'public/likes/' . $like->animation_path;
            if (\Storage::exists($storagePath)) {
                \Storage::delete($storagePath);
            }
            
            // Удаляем из public/storage/likes
            $publicPath = public_path('storage/likes/' . $like->animation_path);
            if (file_exists($publicPath)) {
                unlink($publicPath);
            }
        }
        
        $like->delete();
        
        return redirect()->route('likes.index')->with('success', 'Тип лайка успешно удален');
    }
    
    /**
     * Сохранить постоянную анимацию для поста
     */
    public function savePostAnimation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,post_id',
            'animation_url' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        
        $post = Posts::where('post_id', $request->post_id)->first();
        
        // Append the new animation URL to existing ones
        if ($post->post_animation) {
            // Check if this animation URL is already saved to avoid duplicates
            $existingAnimations = explode(',', $post->post_animation);
            if (!in_array($request->animation_url, $existingAnimations)) {
                // Append the new animation URL
                $post->post_animation = $post->post_animation . ',' . $request->animation_url;
            }
        } else {
            // If no animations exist yet, just set the new one
            $post->post_animation = $request->animation_url;
        }
        
        $post->save();
        
        // Get all animations for this post to return to the client
        $animations = explode(',', $post->post_animation);
        
        return response()->json([
            'success' => true,
            'message' => 'Анимация успешно сохранена',
            'animations' => $animations,
            'count' => count($animations)
        ]);
    }
}
