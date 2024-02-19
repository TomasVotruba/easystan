<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan\Neon;

use Nette\Neon\Neon;
use Nette\Utils\FileSystem;

final class NeonFileSystem
{
    public static function print(array $neon, string $targetFilePath): void
    {
        $neonFileContents = Neon::encode($neon, true, '    ');

        FileSystem::write($targetFilePath, $neonFileContents);
    }
}
