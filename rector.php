<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
use RectorLaravel\Rector\MethodCall\RedirectRouteToToRouteHelperRector;

return static function (RectorConfig $rectorConfig) {
    $rectorConfig->paths([
        __DIR__.'/app',
        __DIR__.'/config',
        __DIR__.'/database',
        __DIR__.'/public',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ]);

    $rectorConfig->sets([
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        LevelSetList::UP_TO_PHP_83,
    ]);

    $rectorConfig->rules([
        AddVoidReturnTypeWhereNoReturnRector::class,
        RedirectRouteToToRouteHelperRector::class,
        TypedPropertyFromAssignsRector::class,
    ]);
};
