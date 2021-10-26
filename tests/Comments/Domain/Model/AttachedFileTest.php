<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Comments\Domain\Model;

use PHPUnit\Framework\TestCase;
use Sylius\OrderCommentsPlugin\Domain\Model\AttachedFile;

final class AttachedFileTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created(): void
    {
        $uploadedFile = AttachedFile::create('test/test/file.pdf');

        $this->assertEquals('test/test/file.pdf', $uploadedFile->path());
    }

    /**
     * @test
     */
    public function it_cannot_be_created_with_empty_path(): void
    {
        $this->expectException(\DomainException::class);

        AttachedFile::create('');
    }
}
