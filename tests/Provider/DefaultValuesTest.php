<?php

namespace Camelot\Sitemap\Tests\Provider;

use Camelot\Sitemap\Entity\ChangeFrequency;
use Camelot\Sitemap\Provider\DefaultValues;
use PHPUnit\Framework\TestCase;

class DefaultValuesTest extends TestCase
{
    public function testEmptyDefaultValuesCanBeCreated(): void
    {
        $values = DefaultValues::none();

        $this->assertFalse($values->hasLastmod());
        $this->assertFalse($values->hasPriority());
        $this->assertFalse($values->hasChangeFreq());

        $this->assertNull($values->getLastmod());
        $this->assertNull($values->getPriority());
        $this->assertNull($values->getChangeFreq());
    }

    public function testDefaultValuesCanBeGiven(): void
    {
        $priority = 0.4;
        $changeFreq = ChangeFrequency::ALWAYS;
        $lastmod = new \DateTimeImmutable();

        $values = DefaultValues::create($priority, $changeFreq, $lastmod);

        $this->assertTrue($values->hasLastmod());
        $this->assertTrue($values->hasPriority());
        $this->assertTrue($values->hasChangeFreq());

        $this->assertSame($lastmod, $values->getLastmod());
        $this->assertSame($priority, $values->getPriority());
        $this->assertSame($changeFreq, $values->getChangeFreq());
    }
}
