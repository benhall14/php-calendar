<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@Symfony' => true,
    'php_unit_method_casing' => ['case' => 'snake_case'],
    'elseif' => true,
    'phpdoc_align' => ['align' => 'left'],
    'global_namespace_import' => true,
];

$dirsToCheck = [
    __DIR__.'/src',
    __DIR__.'/tests'
];

$finder = Finder::create()
    ->in(array_filter($dirsToCheck, 'file_exists'))
    ->exclude(['vendor'])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
