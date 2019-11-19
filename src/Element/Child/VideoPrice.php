<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Util\Assert;
use function strtolower;

/**
 * The price to download or view the video. Omit this tag for free videos.
 */
final class VideoPrice
{
    public const TYPE_RENT = 'rent';
    public const TYPE_PURCHASE = 'purchase';
    public const RESOLUTION_STANDARD = 'hd';
    public const RESOLUTION_HIGH = 'sd';

    /** Currency in ISO 4217 format. */
    private string $currency;
    private float $value;
    /** Specifies the purchase option. Supported values are 'rent' and 'own' (default). */
    private ?string $type;
    /** Specifies the resolution of the purchased version. Supported values are 'hd' and 'sd'. */
    private ?string $resolution;

    public function __construct(string $currency, float $value, ?string $type = null, ?string $resolution = null)
    {
        Assert::oneOfOrNull(!$type ? $type : strtolower($type), [self::TYPE_RENT, self::TYPE_PURCHASE], 'type');
        Assert::oneOfOrNull(!$resolution ? $resolution : strtolower($resolution), [self::RESOLUTION_HIGH, self::RESOLUTION_STANDARD], 'resolution');

        $this->currency = $currency;
        $this->value = $value;
        $this->type = $type;
        $this->resolution = $resolution;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getResolution(): ?string
    {
        return $this->resolution;
    }
}
