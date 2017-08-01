<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Comments\Event;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Sylius\Component\Core\Model\Order;
use Sylius\OrderCommentsPlugin\Domain\Event\OrderCommentedByAdministrator;
use Sylius\OrderCommentsPlugin\Domain\Model\Email;

final class OrderCommentedByAdministratorTest extends TestCase
{
    /**
     * @test
     */
    public function adminstrator_commented_an_order(): void
    {
        $commentId = Uuid::uuid4();
        $order = new Order();
        $event = OrderCommentedByAdministrator::occur($commentId, $order, Email::fromString('test@test.com'), 'Hello');

        $this->assertEquals($commentId, $event->orderCommentId());
        $this->assertEquals($order, $event->order());
        $this->assertEquals('test@test.com', $event->administratorEmail());
        $this->assertEquals('Hello', $event->message());
    }
}
