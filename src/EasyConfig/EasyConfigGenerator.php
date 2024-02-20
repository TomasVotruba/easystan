<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan\EasyConfig;

use TomasVotruba\EasyStan\Configuration\PHPStanLevelConfigsLoader;
use TomasVotruba\EasyStan\Enum\PHPStanConfigKey;
use TomasVotruba\EasyStan\FileSystem\NeonFileSystem;
use TomasVotruba\EasyStan\FileSystem\PathHelper;

/**
 * @see \TomasVotruba\EasyStan\Tests\EasyConfig\EasyConfigGenerator\EasyConfigGeneratorTest
 */
final class EasyConfigGenerator
{
    private PHPStanLevelConfigsLoader $phpStanLevelConfigsLoader;

    public function __construct()
    {
        $this->phpStanLevelConfigsLoader = new PHPStanLevelConfigsLoader();
    }

    public function generate(string $targetConfigDirectory): void
    {
        $easyLevels = [];

        // we need to regenerate
        foreach ($this->phpStanLevelConfigsLoader->loadByLevel() as $phpstanLevelConfig) {
            $parameters = $phpstanLevelConfig[PHPStanConfigKey::PARAMETERS_KEY] ?? [];

            $conditionalTags = $phpstanLevelConfig[PHPStanConfigKey::CONDITIONAL_TAGS_KEY] ?? [];
            // make sure it's allowed
            unset($parameters[PHPStanConfigKey::CUSTOM_RULESET_KEY]);

            // @todo split parameters one at a time later
            $rules = $phpstanLevelConfig[PHPStanConfigKey::RULES] ?? [];

            // 1 rule at a time
            foreach ($rules as $rule) {
                $easyLevel = [
                    PHPStanConfigKey::RULES => [$rule],
                ];

                if ($parameters !== []) {
                    $easyLevel[PHPStanConfigKey::PARAMETERS_KEY] = $parameters;
                }

                // add conditional tags for the rule
                if (isset($conditionalTags[$rule])) {
                    $easyLevel[PHPStanConfigKey::CONDITIONAL_TAGS_KEY][$rule] = $conditionalTags[$rule];
                }

                $easyLevels[] = $easyLevel;
            }

            // one rule service at at time
            // @todo make sure to share non-rule services in every config
            $services = $phpstanLevelConfig[PHPStanConfigKey::SERVICES] ?? [];

            foreach ($services as $service) {
                $easyLevel = [
                    PHPStanConfigKey::SERVICES => [$service],
                ];

                $serviceClass = $service['class'] ?? null;
                if ($serviceClass && isset($conditionalTags[$serviceClass])) {
                    $easyLevel[PHPStanConfigKey::CONDITIONAL_TAGS_KEY][$serviceClass] = $conditionalTags[$serviceClass];
                }

                $easyLevels[] = $easyLevel;
            }
        }

        foreach ($easyLevels as $key => $easyLevel) {
            $targetEasyLevelConfigFilePath = $targetConfigDirectory . '/' . $key . '.neon';

            // make sure to include previous configs
            if ($key > 0) {
                $easyLevel = array_merge([
                    'includes' => [($key - 1) . '.neon'],
                ], $easyLevel);
            }

            // print levels
            NeonFileSystem::print($easyLevel, $targetEasyLevelConfigFilePath);

            if (defined('PHPUNIT_COMPOSER_INSTALL')) {
                continue;
            }

            echo 'Generated config ' . PathHelper::relativeToCwd($targetEasyLevelConfigFilePath) . PHP_EOL;
        }
    }
}
