<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Parses a file and returns its content as an associative array.
 *
 * @param string $filePath The path to the file.
 *
 * @return array The content of the file as an associative array.
 *
 * @throws \Exception if the file format is not supported or the file does not exist.
 */
function parseFile(string $filePath): array
{
    if (!file_exists($filePath)) {
        throw new \Exception("File not found: {$filePath}");
    }

    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
    $fileContent = file_get_contents($filePath);

    switch ($fileExtension) {
        case 'json':
            $data = json_decode($fileContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Error decoding JSON: " . json_last_error_msg());
            }
            return $data;
        case 'yaml':
        case 'yml':
            try {
                $data = Yaml::parse($fileContent);
                if (!is_array($data)) {
                    throw new \Exception("YAML file does not contain an array.");
                }
                return $data;
            } catch (ParseException $exception) {
                throw new \Exception("Error parsing YAML: " . $exception->getMessage());
            }
        default:
            throw new \Exception("Unsupported file format: {$fileExtension}");
    }
}
