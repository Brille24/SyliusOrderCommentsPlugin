<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Domain\Model;

use Sylius\Component\Core\Model\Order;
use Sylius\OrderCommentsPlugin\Domain\Model\Author;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;

final class CommentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_created(): void
    {
        $author = Author::create('test@test.com');
        $order = new Order();

        $comment = Comment::create($author, $order, 'Hello');

        $this->assertEquals($order, $comment->order());
        $this->assertEquals('Hello', $comment->message());
    }

    /**
     * @test
     *
     * @expectedException \DomainException
     */
    public function it_cannot_be_created_with_empty_message(): void
    {
        $author = Author::create('test@test.com');
        $order = new Order();

        Comment::create($author, $order, '');
    }
}
