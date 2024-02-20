<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan\Enum;

final class PHPStanConfigKey
{
    /**
     * @var string
     */
    public const CUSTOM_RULESET_KEY = 'customRulesetUsed';

    /**
     * @var string
     */
    public const CONDITIONAL_TAGS_KEY = 'conditionalTags';

    /**
     * @var string
     */
    public const PARAMETERS_KEY = 'parameters';

    /**
     * @var string
     */
    public const RULES = 'rules';

    /**
     * @var string
     */
    public const SERVICES = 'services';
}
