<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Target;

interface TargetInterface
{
    public function write(string $string): void;
}
