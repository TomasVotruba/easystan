<?php

declare(strict_types=1);

use PHPStan\DependencyInjection\Container;
use TomasVotruba\EasyStan\Configuration;
use TomasVotruba\EasyStan\Configuration\EasyLevelExtensionUpdater;

// the $container variable is available via https://github.com/phpstan/phpstan-src/blob/f01dbb66ff2bf67fe9c3e125af8d4a6ed2bedad8/src/Command/CommandHelper.php#L536-L538

/** @var Container $container */
$configuration = $container->getByType(Configuration::class);

$easyLevelExtensionUpdater = new EasyLevelExtensionUpdater();

/** @var Configuration $configuration */
$easyLevelExtensionUpdater->run($configuration->getEasyLevel());
