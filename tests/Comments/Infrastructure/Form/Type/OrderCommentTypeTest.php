<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Comments\Infrastructure\Form\Type;

use Sylius\OrderCommentsPlugin\Infrastructure\Form\Type\OrderCommentType;
use Sylius\OrderCommentsPlugin\Infrastructure\Model\OrderComment;
use Symfony\Component\Form\Test\TypeTestCase;

final class OrderCommentTypeTest extends TypeTestCase
{
    /**
     * @test
     */
    public function it_allows_to_define_comment_message(): void
    {
        $form = $this->factory->create(OrderCommentType::class);

        $form->submit(['message' => 'Hello']);

        $comment = new OrderComment();
        $comment->message = 'Hello';

        $this->assertEquals($comment, $form->getData());
    }
}
