<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->notPath([
        'config/bundles.php',
        'config/reference.php',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        'yoda_style' => false,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/var/.php-cs-fixer.cache')
;
