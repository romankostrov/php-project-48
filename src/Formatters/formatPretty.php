<?php

namespace Differ\formatters\formatPretty;

function formatArrayValue($value, $offsets)
{
    $prettyValue = json_encode($value, JSON_PRETTY_PRINT);
    $deleteСharacters = str_replace(['"', ','], '', $prettyValue);
    $addOffset = str_replace(
        "\n{$offsets['base']}",
        "\n{$offsets['depth']}{$offsets['base']}{$offsets['base']}",
        $deleteСharacters
    );
    return str_replace("\n}", "\n{$offsets['depth']}{$offsets['base']}}", $addOffset);
}

function getValue($item, $offsets)
{
    if (is_array($item)) {
        return formatArrayValue($item, $offsets);
    }
    return is_bool($item) ? json_encode($item) : $item;
}

function parsePretty($diff, $depth = 0)
{
    return array_map(function ($item) use (&$depth) {
        $key = $item['key'];
        $offsets = [
            "base" => "    ",
            "deleted" => "  - ",
            "added" => "  + ",
            "depth" => str_repeat("    ", $depth)
        ];

        switch ($item['type']) {
            case 'node':
                $value = implode("\n", parsePretty($item['children'], $depth + 1)) . "\n{$offsets['base']}}";
                return "{$offsets['depth']}{$offsets['base']}{$key}: {\n{$value}";
            case 'unchanged':
                $value = getValue($item['value'], $offsets);
                return "{$offsets['depth']}{$offsets['base']}{$key}: {$value}";
            case 'deleted':
                $value = getValue($item['value'], $offsets);
                return "{$offsets['depth']}{$offsets['deleted']}{$key}: {$value}";
            case 'added':
                $value = getValue($item['value'], $offsets);
                return "{$offsets['depth']}{$offsets['added']}{$key}: {$value}";
            case 'changed':
                $valueBefore = getValue($item['valueBefore'], $offsets);
                $valueAfter = getValue($item['valueAfter'], $offsets);
                return "{$offsets['depth']}{$offsets['deleted']}{$key}: {$valueBefore}\n" .
                "{$offsets['depth']}{$offsets['added']}{$key}: {$valueAfter}";
            default:
                throw new \Exception("Unknown type: {$item['type']}!");
        }
    }, $diff);
}

function formatPretty($diff)
{
    return "{\n" . implode("\n", parsePretty($diff)) . "\n}\n";
}
