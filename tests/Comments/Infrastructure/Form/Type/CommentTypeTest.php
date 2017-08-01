<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Comments\Infrastructure\Form\Type;

use Sylius\OrderCommentsPlugin\Infrastructure\Form\Type\CommentType;
use Sylius\OrderCommentsPlugin\Infrastructure\Model\Comment;
use Symfony\Component\Form\Test\TypeTestCase;

final class CommentTypeTest extends TypeTestCase
{
    /**
     * @test
     */
    public function it_allows_to_define_comment_message(): void
    {
        $form = $this->factory->create(CommentType::class);

        $form->submit(['message' => 'Hello']);

        $comment = new Comment();
        $comment->message = 'Hello';

        $this->assertEquals($comment, $form->getData());
    }
}
