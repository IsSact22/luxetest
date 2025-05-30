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
        __DIR__.'/_ide_helper_models.php',
    ]);

    $rectorConfig->sets([
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        LevelSetList::UP_TO_PHP_83,
    ]);

    $rectorConfig->rules([
        \RectorLaravel\Rector\MethodCall\AssertStatusToAssertMethodRector::class,
        \RectorLaravel\Rector\StaticCall\EloquentMagicMethodToQueryBuilderRector::class,
        \RectorLaravel\Rector\MethodCall\ValidationRuleArrayStringValueToArrayRector::class,
        AddVoidReturnTypeWhereNoReturnRector::class,
        RedirectRouteToToRouteHelperRector::class,
        TypedPropertyFromAssignsRector::class,
    ]);
};
