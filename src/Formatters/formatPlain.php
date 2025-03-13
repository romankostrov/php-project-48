<?php

namespace Differ\formatters\formatPlain;

function getValue($item)
{
    if (is_array($item)) {
        return 'complex value';
    }
    return is_bool($item) ? json_encode($item) : $item;
}

function parsePlain($diff, $path = '')
{
    return array_map(function ($item) use (&$path) {
        $key = $item['key'];

        switch ($item['type']) {
            case 'node':
                return implode("\n", array_filter(parsePlain($item['children'], "{$path}{$key}.")));
            case 'unchanged':
                return;
            case 'deleted':
                $value = getValue($item['value']);
                return "Property {$path}{$key} was removed";
            case 'added':
                $value = getValue($item['value']);
                return "Property {$path}{$key} was added with value: '{$value}'";
            case 'changed':
                $valueBefore = getValue($item['valueBefore']);
                $valueAfter = getValue($item['valueAfter']);
                return "Property {$path}{$key} was changed. From '{$valueBefore}' to '{$valueAfter}'";
            default:
                throw new \Exception("Unknown type: {$item['type']}!");
        }
    }, $diff);
}

function formatPlain($diff)
{
    return implode("\n", array_filter(parsePlain($diff))) . "\n";
}
