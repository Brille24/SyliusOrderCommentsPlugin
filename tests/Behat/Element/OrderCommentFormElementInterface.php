<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Element;

interface OrderCommentFormElementInterface
{
    public function specifyMessage(string $message): void;

    public function comment(): void;
}
