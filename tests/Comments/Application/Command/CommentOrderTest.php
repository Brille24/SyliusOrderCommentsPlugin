<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Comments\Application\Command;

use PHPUnit\Framework\TestCase;
use Sylius\OrderCommentsPlugin\Application\Command\CommentOrder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class CommentOrderTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_immutable_intention_of_commenting_an_order_by_customer(): void
    {
        $command = CommentOrder::create('#00002', 'test@test.com', 'Hello', true);

        $this->assertEquals('#00002', $command->orderNumber());
        $this->assertEquals('test@test.com', $command->authorEmail());
        $this->assertEquals('Hello', $command->message());
        $this->assertEquals(true, $command->notifyCustomer());
    }

    /**
     * @test
     */
    public function it_has_option_file_path_and_file_type(): void
    {
        $fileName = 'text.txt';

        // Symfony 3.4 style
        $file = new UploadedFile($fileName, $fileName, null, null, null, true);
        $command = CommentOrder::create('#00002', 'test@test.com', 'Hello', true, $file);

        $this->assertEquals($file, $command->file());
    }
}
