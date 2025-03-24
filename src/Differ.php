<?php

namespace Differ\Differ;

use function Differ\Parser\parseFile;

/**
 * Generates the difference between two data structures.
 *
 * @param array $firstData The first data structure (associative array).
 * @param array $secondData The second data structure (associative array).
 *
 * @return string The formatted difference as a string.
 */
function genDiff(array $firstData, array $secondData): string
{
    $diff = buildDiff($firstData, $secondData);
    return formatDiff($diff);
}

/**
 * Builds a diff array representing the differences between two data structures.
 *
 * @param array $firstData The first data structure (associative array).
 * @param array $secondData The second data structure (associative array).
 *
 * @return array The diff array.
 */
function buildDiff(array $firstData, array $secondData): array
{
    $keys1 = array_keys($firstData);
    $keys2 = array_keys($secondData);
    $allKeys = array_unique(array_merge($keys1, $keys2));
    sort($allKeys);

    $diff = [];
    foreach ($allKeys as $key) {
        if (array_key_exists($key, $firstData) && array_key_exists($key, $secondData)) {
            if ($firstData[$key] === $secondData[$key]) {
                $diff[] = ['key' => $key, 'type' => 'unchanged', 'value' => $firstData[$key]];
            } else {
                $diff[] = ['key' => $key, 'type' => 'changed', 'oldValue' => $firstData[$key], 'newValue' => $secondData[$key]];
            }
        } elseif (array_key_exists($key, $firstData)) {
            $diff[] = ['key' => $key, 'type' => 'removed', 'value' => $firstData[$key]];
        } else {
            $diff[] = ['key' => $key, 'type' => 'added', 'value' => $secondData[$key]];
        }
    }

    return $diff;
}

/**
 * Formats the diff array into a string representation.
 *
 * @param array $diff The diff array.
 *
 * @return string The formatted diff string.
 */
function formatDiff(array $diff): string
{
    $result = "{\n";
    foreach ($diff as $item) {
        switch ($item['type']) {
            case 'unchanged':
                $result .= "    {$item['key']}: {$item['value']}\n";
                break;
            case 'added':
                $result .= "  + {$item['key']}: {$item['value']}\n";
                break;
            case 'removed':
                $result .= "  - {$item['key']}: {$item['value']}\n";
                break;
            case 'changed':
                $result .= "  - {$item['key']}: {$item['oldValue']}\n";
                $result .= "  + {$item['key']}: {$item['newValue']}\n";
                break;
        }
    }
    $result .= "}";
    return $result;
}
