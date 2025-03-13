<?php

namespace Differ\genDiff;

use function Differ\renderer\render;
use function Differ\parsers\parse;
use function Differ\buildDiff\buildDiff;

function getContent($path)
{
    if (!is_readable($path)) {
        throw new \Exception("'{$path}' is not readble");
    }
    return file_get_contents($path);
}

function genDiff($path1, $path2, $format)
{
    $content1 = getContent($path1);
    $content2 = getContent($path2);

    $extension1 = pathinfo($path1, PATHINFO_EXTENSION);
    $extension2 = pathinfo($path2, PATHINFO_EXTENSION);

    $data1 = parse($content1, $extension1);
    $data2 = parse($content2, $extension2);

    $diff = buildDiff($data1, $data2);
    return render($diff, $format);
}
