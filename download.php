<?php

require 'vendor/autoload.php';

use Arhitector\Yandex\Disk;
use GuzzleHttp\Psr7\Stream;

$token = '<OAuth-token>'; //  подставить токен
$disk = new Disk($token);

if (isset($_GET['path'])) {
    $filePath = $_GET['path'];

    try {
        $resource = $disk->getResource($filePath);

        // Проверяем, существует ли ресурс файл)
        if (!$resource->has()) {
            echo "Файл не найден.";
            exit;
        }

        // Создаем временный поток
        $stream = new Stream(fopen('php://temp', 'r+'));

        // Скачиваем файл в поток
        $resource->download($stream);

        // Отправляем заголовки для скачивания
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . $resource->get('size'));

        // Перемещаем указатель потока в начало
        $stream->rewind();

        // Выводим содержимое потока
        echo $stream->getContents();

        exit;

    } catch (Exception $e) {
        echo "Ошибка при скачивании файла: " . $e->getMessage();
    }
} else {
    echo "Не указан путь к файлу.";
}
