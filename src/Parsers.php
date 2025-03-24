<?php

namespace Differ\Parser;

function parseFile(string $filePath): array
{
    if (!file_exists($filePath)) {
        throw new \Exception("Файл не найден: {$filePath}");
    }

    $fileContent = file_get_contents($filePath);

    // Определите тип файла (по расширению или содержимому)
    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    switch ($fileExtension) {
        case 'json':
            $data = json_decode($fileContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Ошибка при декодировании JSON: " . json_last_error_msg());
            }
            break;
        // TODO:  Добавьте поддержку YAML при необходимости
        // case 'yaml':
        // case 'yml':
        //     $data = yaml_parse($fileContent);
        //     if ($data === false) {
        //         throw new \Exception("Ошибка при парсинге YAML");
        //     }
        //     break;
        default:
            throw new \Exception("Неподдерживаемый формат файла: {$fileExtension}");
    }

    return $data;
}
