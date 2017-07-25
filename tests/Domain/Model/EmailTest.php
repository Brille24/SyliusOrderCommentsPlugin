<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Domain\Model;

use Sylius\OrderCommentsPlugin\Domain\Model\Email;

final class EmailTest extends \PHPUnit_Framework_TestCase
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
     *
     * @expectedException \DomainException
     */
    public function it_cannot_be_created_from_invalid_string(): void
    {
        Email::fromString('abcd.com');
    }
}
