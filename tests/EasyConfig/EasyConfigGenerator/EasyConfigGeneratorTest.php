<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan\Tests\EasyConfig\EasyConfigGenerator;

use Nette\Utils\FileSystem;
use PHPUnit\Framework\TestCase;
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

    public function test(): void
    {
        $this->easyConfigGenerator->generate(__DIR__ . '/output');
    }

    protected function tearDown(): void
    {
        FileSystem::delete(__DIR__ . '/output');
    }
}
