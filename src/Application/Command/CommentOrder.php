<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Application\Command;

use SplFileInfo;

final class CommentOrder
{
    /** @var string */
    private $orderNumber;

    /** @var string */
    private $authorEmail;

    /** @var string */
    private $message;

    /** @var SplFileInfo */
    private $file;

    /** @var bool */
    private $notifyCustomer;

    /**
     * CommentOrder constructor.
     *
     * @param string $orderNumber
     * @param string $authorEmail
     * @param string $message
     * @param bool $notifyCustomer
     * @param SplFileInfo|null $file
     */
    private function __construct(
        string $orderNumber,
        string $authorEmail,
        string $message,
        bool $notifyCustomer,
        ?SplFileInfo $file
    ) {
        $this->orderNumber = $orderNumber;
        $this->authorEmail = $authorEmail;
        $this->message = $message;
        $this->notifyCustomer = $notifyCustomer;
        $this->file = $file;
    }

    public static function create(string $orderNumber, string $authorEmail, string $message, bool $notifyCustomer, SplFileInfo $file = null): self
    {
        return new self($orderNumber, $authorEmail, $message, $notifyCustomer, $file);
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

    public function file(): ?SplFileInfo
    {
        return $this->file;
    }

    public function notifyCustomer(): bool
    {
        return $this->notifyCustomer;
    }
}
