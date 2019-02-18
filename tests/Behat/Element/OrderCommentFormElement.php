<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Element;

class OrderCommentFormElement extends Element implements OrderCommentFormElementInterface
{
    public function specifyMessage(string $message): void
    {
        $this->getDocument()->fillField('Message', $message);
    }

    public function attachFile($path): void
    {
        $this->getDocument()->find('css', 'input[type="file"]')->attachFile($path);
    }

    public function enableCustomerNotified(): void
    {
        $this->getDocument()->find('css', '#order_comment_notifyCustomer')->check();
    }

    public function disableCustomerNotified(): void
    {
        $this->getDocument()->find('css', '#order_comment_notifyCustomer')->uncheck();
    }

    public function comment(): void
    {
        $this->getDocument()->pressButton('Comment');
    }
}
