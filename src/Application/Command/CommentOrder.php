<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Application\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class CommentOrder
{
    private function __construct(
        private string $orderNumber,
        private string $authorEmail,
        private string $message,
        private bool $notifyCustomer,
        private ?UploadedFile $file = null
    ) {
    }

    public static function create(string $orderNumber, string $authorEmail, string $message, bool $notifyCustomer, ?UploadedFile $file = null): self
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

    public function file(): ?UploadedFile
    {
        return $this->file;
    }

    public function notifyCustomer(): bool
    {
        return $this->notifyCustomer;
    }
}
