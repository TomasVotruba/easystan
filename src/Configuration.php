<?php

declare(strict_types=1);

namespace TomasVotruba\EasyStan;

final class Configuration
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(private array $parameters)
    {
    }

    public function getEasyLevel(): int
    {
        return $this->parameters['easy_level'];
    }
}
