<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Util\Assert;

final class VideoId
{
    public const TYPE_TMS_SERIES = 'tms:series';
    public const TYPE_TMS_PROGRAM = 'tms:program';
    public const TYPE_ROVI_SERIES = 'rovi:series';
    public const TYPE_ROVI_PROGRAM = 'rovi:program';
    public const TYPE_FREEBASE = 'freebase';
    public const TYPE_URL = 'url';

    private string $value;
    private string $type;

    public function __construct(string $value, string $type)
    {
        Assert::oneOf($type, [self::TYPE_TMS_SERIES, self::TYPE_TMS_PROGRAM, self::TYPE_ROVI_SERIES, self::TYPE_ROVI_PROGRAM, self::TYPE_FREEBASE, self::TYPE_URL], 'id');

        $this->value = $value;
        $this->type = $type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
