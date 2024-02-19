<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan\Finder;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\Assert\Assert;

final class NeonFilesFinder
{
    /**
     * @param string[] $paths
     * @return SplFileInfo[]
     */
    public static function find(array $paths): array
    {
        Assert::allString($paths);
        Assert::allFileExists($paths);

        $finder = Finder::create()
            ->files()
            ->in($paths)
            ->name('*.neon');

        return iterator_to_array($finder);
    }
}