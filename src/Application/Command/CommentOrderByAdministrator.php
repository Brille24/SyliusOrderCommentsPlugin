<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Application\Command;

final class CommentOrderByAdministrator
{
    /** @var string */
    private $orderNumber;

    /** @var string */
    private $administratorEmail;

    /** @var string */
    private $message;

    private function __construct(string $orderNumber, string $administratorEmail, string $message)
    {
        $this->orderNumber = $orderNumber;
        $this->administratorEmail = $administratorEmail;
        $this->message = $message;
    }

    public static function create(string $orderNumber, string $administratorEmail, string $message): self
    {
        return new self($orderNumber, $administratorEmail, $message);
    }

    public function orderNumber(): string
    {
        return $this->orderNumber;
    }

    public function administratorEmail(): string
    {
        return $this->administratorEmail;
    }

    public function message(): string
    {
        return $this->message;
    }
}
