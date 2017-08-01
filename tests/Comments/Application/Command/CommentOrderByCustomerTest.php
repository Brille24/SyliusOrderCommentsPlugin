<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Comments\Application\Command;

use PHPUnit\Framework\TestCase;
use Sylius\OrderCommentsPlugin\Application\Command\CommentOrderByCustomer;

final class CommentOrderByCustomerTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_immutable_intention_of_commenting_an_order_by_customer(): void
    {
        $command = CommentOrderByCustomer::create('#00002', 'test@test.com', 'Hello');

        $this->assertEquals('#00002', $command->orderNumber());
        $this->assertEquals('test@test.com', $command->customerEmail());
        $this->assertEquals('Hello', $command->message());
    }
}
