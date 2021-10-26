<?php

declare(strict_types=1);

namespace Tests\Brille24\OrderCommentsPlugin\Comments\Domain\Model;

use PHPUnit\Framework\TestCase;
use Brille24\OrderCommentsPlugin\Domain\Model\Email;

final class EmailTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_immutable_representation_of_author_email(): void
    {
        $email = Email::fromString('test@test.com');

        $this->assertEquals('test@test.com', $email->__toString());
    }

    /**
     * @test
     */
    public function it_cannot_be_created_from_invalid_string(): void
    {
        $this->expectException(\DomainException::class);

        Email::fromString('abcd.com');
    }
}
