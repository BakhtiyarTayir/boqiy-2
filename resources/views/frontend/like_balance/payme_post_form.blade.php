<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ get_phrase('Redirecting to Payme...') }}</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; flex-direction: column; text-align: center; }
        .loader { border: 5px solid #f3f3f3; border-top: 5px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin-top: 20px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <p>{{ get_phrase('Please wait, you are being redirected to the secure Payme payment page...') }}</p>
    <div class="loader"></div>

    <form id="paymePostForm" method="POST" action="{{ $paymeFormActionUrl }}" style="display: none;">
        {{-- Обязательные поля --}}
        <input type="hidden" name="merchant" value="{{ $formData['merchant'] }}"/>
        <input type="hidden" name="amount" value="{{ $formData['amount'] }}"/>

        {{-- Поля Account (ключи обязательны для вашего бэкенда) --}}
        @foreach($formData['account'] as $key => $value)
            <input type="hidden" name="account[{{ $key }}]" value="{{ $value }}"/>
        @endforeach

        {{-- Необязательные поля из документации --}}
        @if(isset($formData['lang']))
            <input type="hidden" name="lang" value="{{ $formData['lang'] }}"/>
        @endif

        @if(isset($formData['callback']))
            <input type="hidden" name="callback" value="{{ $formData['callback'] }}"/>
        @endif

        @if(isset($formData['callback_timeout']))
            <input type="hidden" name="callback_timeout" value="{{ $formData['callback_timeout'] }}"/>
        @endif

        @if(isset($formData['description']))
            <input type="hidden" name="description" value="{{ $formData['description'] }}"/>
        @endif

        {{-- URL для отмены платежа --}}
        @if(isset($formData['cancel_url']))
            <input type="hidden" name="cancel_url" value="{{ $formData['cancel_url'] }}"/>
        @endif

        {{-- URL для отмены платежа по документации Payme --}}
        @if(isset($formData['cl']))
            <input type="hidden" name="cl" value="{{ $formData['cl'] }}"/>
        @endif

        {{-- Опциональное поле detail для фискализации --}}
        @if(isset($formData['detail']))
            <input type="hidden" name="detail" value="{{ $formData['detail'] }}"/>
        @endif

        {{-- Кнопка нужна для отправки без JS, но мы отправляем через JS --}}
        {{-- <button type="submit">Pay with Payme</button> --}}
    </form>

    <script>
        // Автоматическая отправка формы при загрузке страницы
        document.getElementById('paymePostForm').submit();
    </script>
</body>
</html> 