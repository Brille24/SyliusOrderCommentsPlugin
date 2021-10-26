<?php

declare(strict_types=1);

namespace Tests\Brille24\OrderCommentsPlugin\Comments\Event;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Sylius\Component\Core\Model\Order;
use Brille24\OrderCommentsPlugin\Domain\Event\OrderCommented;
use Brille24\OrderCommentsPlugin\Domain\Model\Email;

final class OrderCommentedTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_immutable_fact_of_order_being_commented(): void
    {
        $commentId = Uuid::uuid4();
        $order = new Order();
        $event = OrderCommented::occur(
            $commentId,
            $order,
            Email::fromString('test@test.com'),
            'Hello',
            true,
            new \DateTimeImmutable()
        );

        $this->assertEquals($commentId, $event->orderCommentId());
        $this->assertEquals($order, $event->order());
        $this->assertEquals('test@test.com', $event->authorEmail());
        $this->assertEquals('Hello', $event->message());
        $this->assertEquals(true, $event->notifyCustomer());
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->createdAt());
    }
}
