<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan;

use Symfony\Component\Finder\Finder;
use Webmozart\Assert\Assert;

final class Configuration
{
    public function __construct(
        private int $easyLevel
    ) {
        // count files in directory
        $maxLevelCount = Finder::create()
            ->files()
            ->in(__DIR__ . '/../config/easy_levels')
            ->count();

        Assert::range($this->easyLevel, 0, $maxLevelCount);
    }

    public function getEasyLevel(): int
    {
        return $this->easyLevel;
    }
}
