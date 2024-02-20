<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan\FileSystem;

use Nette\Neon\Neon;
use Nette\Utils\FileSystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\Assert\Assert;

final class NeonFileSystem
{
    public static function print(array $neon, string $targetFilePath): void
    {
        $neonFileContents = Neon::encode($neon, true, '    ');

        FileSystem::write($targetFilePath, $neonFileContents);
    }

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
            ->name('*.neon')
            ->sortByName();

        return iterator_to_array($finder);
    }
}
