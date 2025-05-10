<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class SetDefaultLanguageController extends Controller
{
    /**
     * Set Uzbek as the default language for the application
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setUzbekAsDefault()
    {
        try {
            // Проверяем существует ли уже настройка языка в базе данных
            $exists = DB::table('settings')->where('type', 'system_language')->exists();
            
            if ($exists) {
                // Если существует, обновляем значение
                DB::table('settings')
                    ->where('type', 'system_language')
                    ->update(['description' => 'uz']);
                    
                echo "Setting updated successfully\n";
            } else {
                // Если не существует, создаем новую запись
                DB::table('settings')->insert([
                    'type' => 'system_language',
                    'description' => 'uz',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                echo "Setting created successfully\n";
            }
            
            // Устанавливаем язык в сессии
            Session::put('active_language', 'uz');
            
            echo "Default language has been set to Uzbek\n";
            
            return redirect()->back();
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            return redirect()->back();
        }
    }
} 