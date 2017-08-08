<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Comments\Domain\Model;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Order;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;

final class CommentTest extends TestCase
{
    /**
     * @test
     */
    public function order_can_be_commented(): void
    {
        $order = new Order();

        $comment = new Comment($order, 'test@test.com', 'Hello');

        $this->assertEquals($order, $comment->order());
        $this->assertEquals('test@test.com', $comment->authorEmail());
        $this->assertEquals('Hello', $comment->message());
        $this->assertInstanceOf(\DateTimeImmutable::class, $comment->createdAt());
    }

    /**
     * @test
     *
     * @expectedException \DomainException
     */
    public function order_cannot_be_commented_with_empty_message(): void
    {
        $order = new Order();

        new Comment($order, 'test@test.com', '');
    }

    /**
     * @test
     *
     * @expectedException \DomainException
     */
    public function order_cannot_be_commented_with_invalid_email(): void
    {
        $order = new Order();

        new Comment($order, 'abcd.com', 'Hello');
    }
}
