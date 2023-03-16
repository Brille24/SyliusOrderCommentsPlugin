<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Domain\Event;

use Ramsey\Uuid\UuidInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Brille24\OrderCommentsPlugin\Domain\Model\AttachedFile;
use Brille24\OrderCommentsPlugin\Domain\Model\Email;

final class OrderCommented
{
    private function __construct(
        private UuidInterface $orderCommentId,
        private OrderInterface $order,
        private Email $authorEmail,
        private string $message,
        private bool $notifyCustomer,
        private \DateTimeInterface $createdAt,
        private ?AttachedFile $attachedFile = null
    ) {
    }

    public static function occur(
        UuidInterface $orderCommentId,
        OrderInterface $order,
        Email $authorEmail,
        string $message,
        bool $notifyCustomer,
        \DateTimeInterface $createdAt,
        ?AttachedFile $attachedFile = null
    ): self {
        return new self($orderCommentId, $order, $authorEmail, $message, $notifyCustomer, $createdAt, $attachedFile);
    }

    public function orderCommentId(): UuidInterface
    {
        return $this->orderCommentId;
    }

    public function order(): OrderInterface
    {
        return $this->order;
    }

    public function authorEmail(): Email
    {
        return $this->authorEmail;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function notifyCustomer(): bool
    {
        return $this->notifyCustomer;
    }

    public function createdAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function attachedFile(): ?AttachedFile
    {
        return $this->attachedFile;
    }
}
