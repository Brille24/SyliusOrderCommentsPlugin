<?php

declare(strict_types=1);

namespace Tests\Brille24\OrderCommentsPlugin\Comments\Event;

use PHPUnit\Framework\TestCase;
use Brille24\OrderCommentsPlugin\Domain\Event\FileAttached;

final class FileAttachedTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_immutable_fact_of_file_being_attached(): void
    {
        $event = FileAttached::occur('test/test/file.pdf');

        $this->assertEquals('test/test/file.pdf', $event->filePath());
    }
}
