<?php

namespace Differ\formatters\formatJson;

function formatJson($diff)
{
    return json_encode($diff, JSON_PRETTY_PRINT);
}
