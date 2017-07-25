<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Domain\Model;

use Sylius\Component\Core\Model\Order;
use Sylius\OrderCommentsPlugin\Domain\Model\Author;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;

final class AuthorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_created(): void
    {
        $author = Author::create('test@test.com');

        $this->assertEquals('test@test.com', $author->email());
    }

    /**
     * @test
     */
    public function it_can_comment_an_order(): void
    {
        $order = new Order();
        $author = Author::create('test@test.com');

        $author->commentOrder($order, 'Hello');

        $this->assertEquals([Comment::create($author, $order, 'Hello')], $author->orderComments());
    }

    /**
     * @test
     *
     * @expectedException \DomainException
     */
    public function it_cannot_comment_an_order_with_empty_message(): void
    {
        $order = new Order();
        $author = Author::create('test@test.com');

        $author->commentOrder($order, '');
    }
}
