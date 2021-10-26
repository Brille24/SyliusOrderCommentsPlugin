<?php

declare(strict_types=1);

namespace Tests\Brille24\OrderCommentsPlugin\Behat\Element;

interface OrderCommentFormElementInterface
{
    public function specifyMessage(string $message): void;

    public function attachFile($path): void;

    public function comment(): void;

    public function enableCustomerNotified(): void;

    public function disableCustomerNotified(): void;
}
