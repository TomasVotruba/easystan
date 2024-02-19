<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan\EasyConfig;

use TomasVotruba\EasyStan\Configuration\PHPStanLevelConfigsLoader;
use TomasVotruba\EasyStan\Neon\NeonFileSystem;

final class EasyConfigGenerator
{
    private PHPStanLevelConfigsLoader $phpStanLevelConfigsLoader;

    public function __construct()
    {
        $this->phpStanLevelConfigsLoader = new PHPStanLevelConfigsLoader();
    }

    public function generate(): void
    {
        $easyLevelts = [];

        // we need to regenerate
        foreach ($this->phpStanLevelConfigsLoader->loadByLevel() as $level => $phpstanLevelConfig) {
            // @note dummy cope here, improve later by split mechanism
            NeonFileSystem::print($phpstanLevelConfig, __DIR__ . '/../../config/easy_levels/' . $level . '.neon');
        }
    }
}
