<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Application\Command;

final class CommentOrderByCustomer
{
    /** @var string */
    private $orderNumber;

    /** @var string */
    private $customerEmail;

    /** @var string */
    private $message;

    /**
     * @param string $orderNumber
     * @param string $customerEmail
     * @param string $message
     */
    private function __construct(string $orderNumber, string $customerEmail, string $message)
    {
        $this->orderNumber = $orderNumber;
        $this->customerEmail = $customerEmail;
        $this->message = $message;
    }

    public static function create(string $orderNumber, string $customerEmail, string $message): self
    {
        return new self($orderNumber, $customerEmail, $message);
    }

    public static function createEmpty(): self
    {
        return new self('', '', '');
    }

    public function orderNumber(): string
    {
        return $this->orderNumber;
    }

    public function customerEmail(): string
    {
        return $this->customerEmail;
    }

    public function message(): string
    {
        return $this->message;
    }
}
