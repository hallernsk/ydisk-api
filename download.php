<?php

require 'vendor/autoload.php';

use Arhitector\Yandex\Disk;

$token = '<OAuth-token>'; // Подставить токен
$disk = new Disk($token);

if (isset($_GET['path'])) {
    $filePath = $_GET['path'];

    try {
        $resource = $disk->getResource($filePath);

        if (!$resource->has()) {
            echo "Файл не найден.";
            exit;
        }

        // Отправляем заголовки перед вызовом download()
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        // header('Content-Length: ' . $resource->get('size'));

        // Скачиваем файл, передавая вывод в php://output
        $resource->download('php://output');

        exit;


    } catch (Exception $e) {
        echo "Ошибка при скачивании файла: " . $e->getMessage();
    }
} else {
    echo "Не указан путь к файлу.";
}
