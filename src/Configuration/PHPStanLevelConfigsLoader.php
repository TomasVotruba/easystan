<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan\Configuration;

use Nette\Neon\Neon;
use TomasVotruba\EasyStan\Finder\NeonFilesFinder;

/**
 * This service loads PHPStan level configs
 */
final class PHPStanLevelConfigsLoader
{
    /**
     * @return array<int, mixed[]>
     */
    public function loadByLevel(): array
    {
        // to be found in https://github.com/phpstan/phpstan-src/tree/1.11.x/conf
        $phpstanConfigFiles = NeonFilesFinder::find(['phar://vendor/phpstan/phpstan/phpstan.phar/conf']);

        $configContentsByLevel = [];

        foreach ($phpstanConfigFiles as $phpstanConfigFile) {
            // keep only level files
            if (! str_contains($phpstanConfigFile->getFilename(), 'level')) {
                continue;
            }

            $levelNeon = Neon::decodeFile($phpstanConfigFile->getPathname());

            preg_match('#(?<level>\d+)#', $phpstanConfigFile->getPathname(), $match);
            if ($match === []) {
                continue;
            }

            $level = (int) $match['level'];

            $configContentsByLevel[$level] = $levelNeon;
        }

        return $configContentsByLevel;
    }
}
