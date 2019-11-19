<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Util\Assert;

/**
 * Whether to show or hide your video in search results on specified platform
 * types. Supported values are allow or deny.
 *
 * If allow, listed countries are allowed, unlisted countries are denied.
 * If deny, listed countries are denied, unlisted countries are allowed.
 */
final class VideoPlatform
{
    public const ALLOW = 'allow';
    public const DENY = 'deny';
    /** TV browsers, such as those available through GoogleTV devices and game consoles. */
    public const TV = 'tv';
    /** Mobile browsers, such as those on cellular phones or tablets. */
    public const MOBILE = 'mobile';
    /** Traditional computer browsers on desktops and laptops. */
    public const WEB = 'web';

    private string $relationship;
    private array $platforms;

    public function __construct(string $relationship, array $platforms)
    {
        Assert::oneOf($relationship, [self::ALLOW, self::DENY], 'relationship');
        foreach ($platforms as $platform) {
            Assert::oneOf($platform, [self::TV, self::WEB, self::MOBILE], 'platform');
        }
        $this->relationship = $relationship;
        $this->platforms = $platforms;
    }

    public function getRelationship(): string
    {
        return $this->relationship;
    }

    public function getPlatforms(): array
    {
        return $this->platforms;
    }
}
