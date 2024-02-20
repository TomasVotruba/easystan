<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan\Tests\EasyConfig\EasyConfigGenerator;

use Nette\Utils\FileSystem;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use TomasVotruba\EasyStan\EasyConfig\EasyConfigGenerator;

/**
 * @see \TomasVotruba\EasyStan\EasyConfig\EasyConfigGenerator
 */
final class EasyConfigGeneratorTest extends TestCase
{
    private EasyConfigGenerator $easyConfigGenerator;

    protected function setUp(): void
    {
        $this->easyConfigGenerator = new EasyConfigGenerator();
    }

    protected function tearDown(): void
    {
        FileSystem::delete(__DIR__ . '/output');
    }

    public function test(): void
    {
        $this->easyConfigGenerator->generate(__DIR__ . '/output');

        // test at least 270 files were generated

        $generatedFileCount = Finder::create()
            ->files()
            ->in(__DIR__ . '/output')
            ->count();

        $this->assertGreaterThan(250, $generatedFileCount);
    }
}
