<?php

// 1. get "easy_parameter" from phpsta.neon here :)

$localExtensionNeon = Nette\Neon\Neon::decodeFile(__DIR__ . '/../config/extension.neon');
if ($localExtensionNeon['includes'] === null) {
    // we need to fill the includes first

    // 1. generate easy levels

    /** @var \PHPStan\DependencyInjection\Container $container */
    $configuration = $container->getByType(\TomasVotruba\EasyStan\Configuration::class);

    /** @var \TomasVotruba\EasyStan\Configuration $configuration */
    $easyLevel = $configuration->getEasyLevel();

    // 1. fill includes
    $localExtensionNeon['includes'] = [
        __DIR__ . '/../config/easy-level/' . $easyLevel . '.neon',
    ];

    $updatedExtensionFileContents = \Nette\Neon\Neon::encode($localExtensionNeon, true, '    ');
    \Nette\Utils\FileSystem::write(__DIR__ . '/../config/extension.neon', $updatedExtensionFileContents);

    echo 'We have pre-build your easy level setup to configs. Re-run PHPStan to load it and run analysis' . PHP_EOL;
}

// dump($localExtensionNeon);
die;

// dump($container::class);

// 2. load configs on the fly... somehow

