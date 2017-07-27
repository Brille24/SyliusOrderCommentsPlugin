<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Comments\Domain\Model;

use Sylius\Component\Core\Model\Order;
use Sylius\OrderCommentsPlugin\Domain\Model\Author;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;
use Sylius\OrderCommentsPlugin\Domain\Model\Email;

final class CommentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_created(): void
    {
        $order = new Order();

        $comment = Comment::orderByCustomer($order, 'test@test.com', 'Hello');

        $this->assertEquals($order, $comment->order());
        $this->assertEquals('test@test.com', $comment->authorEmail());
        $this->assertEquals('Hello', $comment->message());
    }

    /**
     * @test
     *
     * @expectedException \DomainException
     */
    public function it_cannot_be_created_with_empty_message(): void
    {
        $order = new Order();

        Comment::orderByCustomer($order, 'test@test.com', '');
    }

    /**
     * @test
     *
     * @expectedException \DomainException
     */
    public function it_cannot_be_created_with_invalid_author_email(): void
    {
        $order = new Order();

        Comment::orderByCustomer($order, 'abcd.com', 'Hello');
    }
}
