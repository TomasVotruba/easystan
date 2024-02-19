<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan\EasyConfig;

use TomasVotruba\EasyStan\Configuration\PHPStanLevelConfigsLoader;
use TomasVotruba\EasyStan\FileSystem\NeonFileSystem;
use TomasVotruba\EasyStan\FileSystem\PathHelper;

final class EasyConfigGenerator
{
    /**
     * @var string
     */
    private const CUSTOM_RULESET_KEY = 'customRulesetUsed';

    /**
     * @var string
     */
    private const CONDITIONAL_TAGS_KEY = 'conditionalTags';

    private PHPStanLevelConfigsLoader $phpStanLevelConfigsLoader;

    public function __construct()
    {
        $this->phpStanLevelConfigsLoader = new PHPStanLevelConfigsLoader();
    }

    public function generate(): void
    {
        $easyLevels = [];

        // we need to regenerate
        foreach ($this->phpStanLevelConfigsLoader->loadByLevel() as $phpstanLevelConfig) {
            $parameters = $phpstanLevelConfig['parameters'] ?? [];

            $conditionalTags = $phpstanLevelConfig[self::CONDITIONAL_TAGS_KEY] ?? [];
            // make sure it's allowed
            unset($parameters[self::CUSTOM_RULESET_KEY]);

            // @todo split parameters one at a time later
            $rules = $phpstanLevelConfig['rules'] ?? [];

            // 1 rule at a time
            foreach ($rules as $rule) {
                $easyLevel = [
                    'rules' => [$rule],
                ];

                if ($parameters !== []) {
                    $easyLevel['parameters'] = $parameters;
                }

                // add conditional tags for the rule
                if (isset($conditionalTags[$rule])) {
                    $easyLevel['conditionalTags'][$rule] = $conditionalTags[$rule];
                }

                $easyLevels[] = $easyLevel;
            }

            // one rule service at at time
            // @todo make sure to share non-rule services in every config
            $services = $phpstanLevelConfig['services'] ?? [];

            foreach ($services as $service) {
                $easyLevel = [
                    'services' => [$service],
                ];

                $serviceClass = $service['class'] ?? null;
                if ($serviceClass && isset($conditionalTags[$serviceClass])) {
                    $easyLevel['conditionalTags'][$serviceClass] = $conditionalTags[$serviceClass];
                }

                $easyLevels[] = $easyLevel;
            }
        }

        foreach ($easyLevels as $key => $easyLevel) {
            $targetEasyLevelConfigFilePath = __DIR__ . '/../../config/easy_levels/' . $key . '.neon';

            echo 'Generated config ' . PathHelper::relativeToCwd($targetEasyLevelConfigFilePath) . PHP_EOL;

            // make sure to include previous configs
            if ($key > 0) {
                $easyLevel = array_merge([
                    'includes' => [($key - 1) . '.neon'],
                ], $easyLevel);
            }

            // print levels
            NeonFileSystem::print($easyLevel, $targetEasyLevelConfigFilePath);
        }
    }
}
