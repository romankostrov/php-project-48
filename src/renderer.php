<?php

namespace Differ\renderer;

use function Differ\formatters\formatPretty\formatPretty;
use function Differ\formatters\formatPlain\formatPlain;
use function Differ\formatters\formatJson\formatJson;

function render($diff, $format)
{
    switch ($format) {
        case 'plain':
            return formatPlain($diff);
        case 'pretty':
            return formatPretty($diff);
        case 'json':
            return formatJson($diff);
        default:
            throw new \Exception("Unknown format: {$format}!");
    }
}
