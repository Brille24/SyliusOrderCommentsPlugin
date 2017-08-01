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
    public function customer_can_comment_an_order(): void
    {
        $order = new Order();

        $comment = Comment::orderByCustomer($order, 'test@test.com', 'Hello');

        $this->assertEquals($order, $comment->order());
        $this->assertEquals('test@test.com', $comment->authorEmail());
        $this->assertEquals('Hello', $comment->message());
    }

    /**
     * @test
     */
    public function administrator_can_comment_an_order(): void
    {
        $order = new Order();

        $comment = Comment::orderByAdministrator($order, 'test@test.com', 'Hello');

        $this->assertEquals($order, $comment->order());
        $this->assertEquals('test@test.com', $comment->authorEmail());
        $this->assertEquals('Hello', $comment->message());
    }

    /**
     * @test
     *
     * @expectedException \DomainException
     */
    public function customer_cannot_create_an_empty_comment(): void
    {
        $order = new Order();

        Comment::orderByCustomer($order, 'test@test.com', '');
    }

    /**
     * @test
     *
     * @expectedException \DomainException
     */
    public function administrator_cannot_create_an_empty_comment(): void
    {
        $order = new Order();

        Comment::orderByAdministrator($order, 'test@test.com', '');
    }

    /**
     * @test
     *
     * @expectedException \DomainException
     */
    public function customer_comment_cannot_be_created_with_invalid_mail(): void
    {
        $order = new Order();

        Comment::orderByCustomer($order, 'abcd.com', 'Hello');
    }

    /**
     * @test
     *
     * @expectedException \DomainException
     */
    public function administrator_comment_cannot_be_created_with_invalid_mail(): void
    {
        $order = new Order();

        Comment::orderByCustomer($order, 'abcd.com', 'Hello');
    }
}
