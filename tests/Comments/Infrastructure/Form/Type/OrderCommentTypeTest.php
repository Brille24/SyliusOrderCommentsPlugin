<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Comments\Infrastructure\Form\Type;

use Sylius\OrderCommentsPlugin\Infrastructure\Form\Type\OrderCommentType;
use Sylius\OrderCommentsPlugin\Infrastructure\Form\DTO\OrderComment;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    /**
     * @test
     */
    public function it_allows_to_upload_file(): void
    {
        $file = new UploadedFile(
            __DIR__ . DIRECTORY_SEPARATOR .'file.pdf',
            'file.pdf',
            'pdf',
            123
        );

        $form = $this->factory->create(OrderCommentType::class);

        $form->submit(['file' => $file]);

        $this->assertEquals($file, $form->getData()->file);
    }
}
