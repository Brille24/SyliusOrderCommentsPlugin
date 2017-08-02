<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Element;

class OrderCommentFormElement extends Element implements OrderCommentFormElementInterface
{
    public function specifyMessage(string $message): void
    {
        $this->getDocument()->fillField('Message', $message);
    }

    public function comment(): void
    {
        $this->getDocument()->pressButton('Comment');
    }
}
