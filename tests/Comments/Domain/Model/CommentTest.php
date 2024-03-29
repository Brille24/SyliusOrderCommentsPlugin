<?php

declare(strict_types=1);

namespace Tests\Brille24\OrderCommentsPlugin\Comments\Domain\Model;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Order;
use Brille24\OrderCommentsPlugin\Domain\Model\Comment;

final class CommentTest extends TestCase
{
    /**
     * @test
     */
    public function order_can_be_commented(): void
    {
        $order = new Order();

        $comment = new Comment($order, 'test@test.com', 'Hello', true);

        $this->assertEquals($order, $comment->order());
        $this->assertEquals('test@test.com', $comment->authorEmail());
        $this->assertEquals('Hello', $comment->message());
        $this->assertEquals(true, $comment->notifyCustomer());
        $this->assertInstanceOf(\DateTimeImmutable::class, $comment->createdAt());
    }

    /**
     * @test
     */
    public function order_cannot_be_commented_with_empty_message(): void
    {
        $this->expectException(\DomainException::class);

        $order = new Order();

        new Comment($order, 'test@test.com', '', true);
    }

    /**
     * @test
     */
    public function order_cannot_be_commented_with_invalid_email(): void
    {
        $this->expectException(\DomainException::class);

        $order = new Order();

        new Comment($order, 'abcd.com', 'Hello', true);
    }

    /**
     * @test
     */
    public function it_can_have_file_attached(): void
    {
        $order = new Order();

        $comment = new Comment($order, 'test@test.com', 'Hello', true);
        $comment->attachFile('test/test.pdf');

        $this->assertNotNull($comment->attachedFile());
    }
}
