<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Comments\Event;

use Ramsey\Uuid\Uuid;
use Sylius\Component\Core\Model\Order;
use Sylius\OrderCommentsPlugin\Domain\Event\OrderCommentedByCustomer;
use Sylius\OrderCommentsPlugin\Domain\Model\Email;

final class OrderCommentedByCustomerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_is_immutable_fact_of_order_being_commented_by_customer(): void
    {
        $commentId = Uuid::uuid4();
        $order = new Order();
        $event = OrderCommentedByCustomer::occur($commentId, $order, Email::fromString('test@test.com'), 'Hello');

        $this->assertEquals($commentId, $event->orderCommentId());
        $this->assertEquals($order, $event->order());
        $this->assertEquals('test@test.com', $event->customerEmail());
        $this->assertEquals('Hello', $event->message());
    }
}
