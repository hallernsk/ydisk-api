<?php
require 'vendor/autoload.php';

use Arhitector\Yandex\Disk;

$token = '<OAuth-token>'; //  подставить токен

$disk = new Disk($token);

// Добавление файла (на Яндекс Диск)
if (isset($_FILES['uploaded_file'])) {
    $uploadedFilePath = $_FILES['uploaded_file']['tmp_name'];
    $originalFileName = $_FILES['uploaded_file']['name'];
    $destinationPath = '/' . $originalFileName;

    try {
        $resource = $disk->getResource($destinationPath);
        $resource->upload($uploadedFilePath, true);
        echo "<p>Файл '$originalFileName' успешно загружен на Яндекс Диск.</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>Ошибка при загрузке файла: " . $e->getMessage() . "</p>";
    }
}

// Удаление файла
if (isset($_GET['delete'])) {
    $filePathToDelete = $_GET['delete'];

    try {
        $resourceToDelete = $disk->getResource($filePathToDelete);
        $resourceToDelete->delete(true);
        echo "<p>Файл '$filePathToDelete' успешно удален.</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>Ошибка при удалении файла: " . $e->getMessage() . "</p>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Яндекс Диск</title>
</head>
<body>

<h3>Работа с Яндекс Диском</h3>

<h4>Загрузка файла (на Яндекс Диск)</h4>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="uploaded_file">
    <input type="submit" value="Загрузить">
</form>

<h4>Список файлов</h4>
<?php
// Просмотр файлов

    $resources = $disk->getResources(100);

echo "<ul>";
foreach ($resources as $resource) {
    $fileName = $resource->get('name');
    $fileSize = $resource->get('size');
    $fileModified = $resource->get('modified');
    $filePath = $resource->getPath();

    echo "<li>";
    echo "Имя: $fileName  ";
    echo "Размер: " . $fileSize . "  байт ";
    echo "Изменен: $fileModified  ";
    echo "<a href='?delete=" . urlencode($filePath) . "'>Удалить</a> ";
    echo "<a href='download.php?path=" . urlencode($filePath) . "'>Скачать</a>";
    echo "</li>";
}
echo "</ul>";

?>

</body>
</html>