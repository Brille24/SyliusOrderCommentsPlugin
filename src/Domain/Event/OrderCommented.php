<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Domain\Event;

use Ramsey\Uuid\UuidInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\OrderCommentsPlugin\Domain\Model\AttachedFile;
use Sylius\OrderCommentsPlugin\Domain\Model\Email;

final class OrderCommented
{
    /** @var UuidInterface */
    private $orderCommentId;

    /** @var OrderInterface */
    private $order;

    /** @var Email */
    private $authorEmail;

    /** @var string */
    private $message;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var bool */
    private $notifyCustomer;

    /** @var AttachedFile */
    private $attachedFile;

    private function __construct(
        UuidInterface $orderCommentId,
        OrderInterface $order,
        Email $authorEmail,
        string $message,
        bool $notifyCustomer,
        \DateTimeInterface $createdAt,
        AttachedFile $attachedFile = null
    ) {
        $this->orderCommentId = $orderCommentId;
        $this->order = $order;
        $this->authorEmail = $authorEmail;
        $this->message = $message;
        $this->notifyCustomer = $notifyCustomer;
        $this->createdAt = $createdAt;
        $this->attachedFile = $attachedFile;
    }

    public static function occur(
        UuidInterface $orderCommentId,
        OrderInterface $order,
        Email $authorEmail,
        string $message,
        bool $notifyCustomer,
        \DateTimeInterface $createdAt,
        AttachedFile $attachedFile = null
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
