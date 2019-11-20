<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Target;

interface ReadableTargetInterface extends TargetInterface
{
    public function read(): string;
}
