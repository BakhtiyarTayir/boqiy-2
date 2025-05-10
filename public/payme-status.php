<?php

// Получаем order_id из URL
$order_id = $_GET['order_id'] ?? null;

// Если order_id не передан, извлекаем его из пути
if (!$order_id) {
    $path = $_SERVER['REQUEST_URI'] ?? '';
    if (preg_match('/\/payme\/status\/([^\/\?]+)/', $path, $matches)) {
        $order_id = $matches[1];
    }
}

// Перенаправляем на главную страницу с параметрами
$redirect = '/like-balance/status';
if ($order_id) {
    $redirect .= '?order_id=' . urlencode($order_id);
}

header("Location: " . $redirect);
exit; 