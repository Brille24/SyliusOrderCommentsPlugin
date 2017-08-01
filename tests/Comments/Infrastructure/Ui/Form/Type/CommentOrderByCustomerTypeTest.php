<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Comments\Infrastructure\Ui\From\Type;

use Sylius\OrderCommentsPlugin\Application\Command\CommentOrderByCustomer;
use Sylius\OrderCommentsPlugin\Infrastructure\Ui\Form\Type\CommentOrderByCustomerType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

final class CommentOrderByCustomerTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        return [
            new PreloadedExtension([new CommentOrderByCustomerType()], [])
        ];
    }

    /**
     * @test
     */
    public function it_creates_comment_order_by_customer_command(): void
    {
        $form = $this->factory->create(CommentOrderByCustomerType::class, CommentOrderByCustomer::create('#0002', 'test@test.com', ''));
        $form->submit(['message' => 'Hello']);

        /** @var CommentOrderByCustomer $command */
        $command = $form->getData();

        $this->assertEquals('#0002', $command->orderNumber());
        $this->assertEquals('test@test.com', $command->customerEmail());
        $this->assertEquals('Hello', $command->message());
    }
}
