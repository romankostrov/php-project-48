<?php

// gendiff.php - Исполняемый файл для утилиты gendiff

require __DIR__ . '/vendor/autoload.php';

use Docopt;

$usage = <<<'DOCOPT'
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)

Options:
  -h --help                     Show this screen
  -v --version                  Show version
DOCOPT;

try {
    $args = new Docopt($usage);
    $arguments = $args->handle( $_SERVER['argv'] );

    if ($arguments['--help']) {
        echo $usage;
        exit(0);
    }

    if ($arguments['--version']) {
        echo "gendiff 0.1.0\\n"; // Заглушка для версии
        exit(0);
    }

} catch (Exception $e) {
    echo $e->getMessage();
}