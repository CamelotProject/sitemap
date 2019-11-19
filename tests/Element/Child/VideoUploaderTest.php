<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\VideoUploader;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\VideoUploader
 *
 * @internal
 */
final class VideoUploaderTest extends TestCase
{
    public function testGetName(): void
    {
        static::assertSame('Mary', (new VideoUploader('Mary'))->getName());
    }

    public function testGetInfo(): void
    {
        static::assertSame('Sheepish', (new VideoUploader('Mary', 'Sheepish'))->getInfo());
    }
}
