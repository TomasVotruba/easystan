<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use TomasVotruba\EasyStan\Configuration\PHPStanLevelConfigsLoader;
use TomasVotruba\EasyStan\Enum\PHPStanConfigKey;

/**
 * @see \TomasVotruba\EasyStan\Configuration\PHPStanLevelConfigsLoader
 */
final class PHPStanLevelConfigsLoaderTest extends TestCase
{
    private PHPStanLevelConfigsLoader $phpStanLevelConfigsLoader;

    protected function setUp(): void
    {
        $this->phpStanLevelConfigsLoader = new PHPStanLevelConfigsLoader();
    }

    public function test(): void
    {
        $phpstanLevelConfigsByLevel = $this->phpStanLevelConfigsLoader->loadByLevel();

        $this->assertCount(10, $phpstanLevelConfigsByLevel);

        // check very first config, just ot make sure it's loaded
        $levelZeroConfig = $phpstanLevelConfigsByLevel[0];

        $this->assertArrayHasKey(PHPStanConfigKey::RULES, $levelZeroConfig);
        $this->assertArrayHasKey(PHPStanConfigKey::SERVICES, $levelZeroConfig);
        $this->assertArrayHasKey(PHPStanConfigKey::CONDITIONAL_TAGS_KEY, $levelZeroConfig);

        $this->assertGreaterThan(80, count($levelZeroConfig[PHPStanConfigKey::RULES]));
    }
}
