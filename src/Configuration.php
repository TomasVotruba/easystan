<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan;

use Webmozart\Assert\Assert;

final class Configuration
{
    public function __construct(private int $easyLevel)
    {
        // @todo check based on easy configs
        Assert::range($this->easyLevel, 0, 300);
    }

    public function getEasyLevel(): int
    {
        return $this->easyLevel;
    }
}
