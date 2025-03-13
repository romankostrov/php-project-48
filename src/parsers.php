<?php

namespace Differ\parsers;

use Symfony\Component\Yaml\Yaml;

function parse($content, $extension)
{
    switch ($extension) {
        case 'json':
            return json_decode($content, true);
        case 'yaml':
            return Yaml::parse($content);
        default:
            throw new \Exception("'{$extension}' - this extension is not supported");
    }
}
