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

    /** @var \SplFileInfo */
    private $file;

    private function __construct(string $orderNumber, string $authorEmail, string $message, \SplFileInfo $file = null)
    {
        $this->orderNumber = $orderNumber;
        $this->authorEmail = $authorEmail;
        $this->message = $message;
        $this->file = $file;
    }

    public static function create(string $orderNumber, string $authorEmail, string $message, \SplFileInfo $file = null): self
    {
        return new self($orderNumber, $authorEmail, $message, $file);
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

    public function file(): ?\SplFileInfo
    {
        return $this->file;
    }
}
