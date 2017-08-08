<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Application\Command;

final class CommentOrder
{
    /** @var string */
    private $orderNumber;

    /** @var string */
    private $authorEmail;

    /** @var string */
    private $message;

    /**
     * @param string $orderNumber
     * @param string $authorEmail
     * @param string $message
     */
    private function __construct(string $orderNumber, string $authorEmail, string $message)
    {
        $this->orderNumber = $orderNumber;
        $this->authorEmail = $authorEmail;
        $this->message = $message;
    }

    public static function create(string $orderNumber, string $authorEmail, string $message): self
    {
        return new self($orderNumber, $authorEmail, $message);
    }

    public function orderNumber(): string
    {
        return $this->orderNumber;
    }

    public function authorEmail(): string
    {
        return $this->authorEmail;
    }

    public function message(): string
    {
        return $this->message;
    }
}
