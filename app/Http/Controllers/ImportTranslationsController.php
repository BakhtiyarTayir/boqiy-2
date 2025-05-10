<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportTranslationsController extends Controller
{
    public function index()
    {
        $page_data['view_path'] = 'language.import';

        return view('backend.index', $page_data);
    }
    
    public function importTranslations()
    {
        $languages = ['en', 'uz'];
        $files = ['likes', 'wallet', 'general', 'marketplace', 'main_content']; // Добавляем файл marketplace
        $importCount = 0;
        
        foreach ($languages as $lang) {
            foreach ($files as $file) {
                // Проверяем существование файла
                $filePath = resource_path("lang/{$lang}/{$file}.php");
                if (!file_exists($filePath)) {
                    continue;
                }
                
                $translations = require($filePath);
                
                // Рекурсивно обрабатываем переводы (для вложенных массивов)
                $this->processTranslations($translations, $lang, $importCount);
            }
        }
        
        flash()->addSuccess("Successfully imported {$importCount} translations!");
        return redirect()->back();
    }
    
    /**
     * Рекурсивно обрабатывает переводы и добавляет их в базу данных
     *
     * @param array $translations Массив переводов
     * @param string $lang Код языка
     * @param int &$importCount Счетчик импортированных переводов
     * @param string $prefix Префикс для вложенных ключей
     * @return void
     */
    private function processTranslations($translations, $lang, &$importCount, $prefix = '')
    {
        foreach ($translations as $phrase => $translation) {
            // Если перевод - массив, рекурсивно обрабатываем его
            if (is_array($translation)) {
                $this->processTranslations($translation, $lang, $importCount, $prefix . $phrase . '.');
                continue;
            }
            
            // Формируем полный ключ фразы
            $fullPhrase = $prefix . $phrase;
            
            // Проверить, существует ли перевод
            $exists = DB::table('languages')
                ->where('name', $lang)
                ->where('phrase', $fullPhrase)
                ->exists();
                
            if (!$exists) {
                // Добавить новый перевод
                DB::table('languages')->insert([
                    'name' => $lang,
                    'phrase' => $fullPhrase,
                    'translated' => $translation,
                ]);
                $importCount++;
            } else {
                // Обновить существующий перевод
                DB::table('languages')
                    ->where('name', $lang)
                    ->where('phrase', $fullPhrase)
                    ->update(['translated' => $translation]);
            }
        }
    }
} 