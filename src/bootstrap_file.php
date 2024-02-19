<?php

declare(strict_types=1);

use Nette\Neon\Neon;
use Nette\Utils\FileSystem;
use PHPStan\DependencyInjection\Container;
use TomasVotruba\EasyStan\Configuration;
use TomasVotruba\EasyStan\Finder\NeonFilesFinder;

final class EasyLevelExtensionUpdater
{
    /**
     * @var string
     */
    private const EXTENSION_FILE_PATH = __DIR__ . '/../config/extension.neon';

    /**
     * @var string
     */
    private const INCLUDES_KEY = 'includes';

    public function run(int $easyLevel): void
    {
        // 1. build level configs if missing
        $easyLevelNeonFiles = NeonFilesFinder::find([__DIR__ . '/../config/easy_levels']);


        if (count($easyLevelNeonFiles) < 10) {
            // we need to regenerate
            $phpStanLevelConfigsLoader = new Configuration\PHPStanLevelConfigsLoader();

            foreach ($phpStanLevelConfigsLoader->loadByLevel() as $level => $phpstanLevelConfig) {
                // @note dummy cope here, improve later by split mechanism
                $this->printNeon($phpstanLevelConfig, __DIR__ . '/../config/easy_levels/' . $level . '.neon');
            }
        }

        // 2. fill the includes in extension.neon if needed

        $localExtensionNeon = Nette\Neon\Neon::decodeFile(self::EXTENSION_FILE_PATH);
        if ($this->isExtensionFileUpdateNeeded($localExtensionNeon, $easyLevel)) {
            // 2. we need to fill the includes first
            $localExtensionNeon[self::INCLUDES_KEY] = [
                // use path relative to extension.neon
                'easy_levels/' . $easyLevel . '.neon',
            ];

            $this->printNeon($localExtensionNeon, self::EXTENSION_FILE_PATH);

            echo 'We have pre-build your easy level setup to configs. Re-run PHPStan to load it and run analysis' . PHP_EOL;
            die;
        }
    }

    /**
     * @param array<string, mixed> $extensionNeon
     */
    private function isExtensionFileUpdateNeeded(array $extensionNeon, int $easyLevel): bool
    {
        $includes = $extensionNeon[self::INCLUDES_KEY] ?? null;

        // no includes is provided
        if ($includes === null) {
            return true;
        }

        foreach ($includes as $include) {
            // the included file is current level → no need to update
            if ($include === 'easy_levels/' . $easyLevel . '.neon') {
                return false;
            }
        }


        return true;
    }

    /**
     * @param array<string, mixed> $neon
     */
    private function printNeon(array $neon, string $targetFilePath): void
    {
        $neonFileContents = Neon::encode($neon, true, '    ');
        FileSystem::write($targetFilePath, $neonFileContents);
    }
}

// the $container variable is available via https://github.com/phpstan/phpstan-src/blob/f01dbb66ff2bf67fe9c3e125af8d4a6ed2bedad8/src/Command/CommandHelper.php#L536-L538
/** @var Container $container */
$configuration = $container->getByType(Configuration::class);

$easyLevelExtensionUpdater = new EasyLevelExtensionUpdater();
/** @var Configuration $configuration */
$easyLevelExtensionUpdater->run($configuration->getEasyLevel());

// 2. run PHPStan again
