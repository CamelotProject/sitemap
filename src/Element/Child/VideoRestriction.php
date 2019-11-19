<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Util\Assert;

/**
 * Whether the video is allowed or denied in search results in the
 * specified countries. Supported values are allow or deny.
 *
 * If allow, listed countries are allowed, unlisted countries are denied.
 * If deny, listed countries are denied, unlisted countries are allowed.
 */
final class VideoRestriction
{
    public const ALLOW = 'allow';
    public const DENY = 'deny';

    private string $relationship;
    /** List of country codes in ISO 3166 format. */
    private array $countries;

    public function __construct(string $relationship, array $countries)
    {
        Assert::oneOf($relationship, [self::ALLOW, self::DENY], 'relationship');

        $this->relationship = $relationship;
        $this->countries = $countries;
    }

    public function getRelationship(): string
    {
        return $this->relationship;
    }

    public function getCountries(): array
    {
        return $this->countries;
    }
}
