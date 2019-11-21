<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use PHPUnit\Framework\MockObject\MockObject;
use Sabre\Xml\Writer;

/**
 * @method MockObject createMock($originalClassName)
 */
trait XmlWriterMockTrait
{
    private function createWriterMock(array $expected): Writer
    {
        $writer = $this->createMock(Writer::class);
        $writer
            ->expects($this->once())
            ->method('write')
            ->with($expected)
        ;

        return $writer;
    }
}
