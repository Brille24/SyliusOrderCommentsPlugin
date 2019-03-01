<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Domain\Model;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\OrderCommentsPlugin\Domain\Event\FileAttached;
use Sylius\OrderCommentsPlugin\Domain\Event\OrderCommented;

final class Comment implements ResourceInterface, ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    /** @var UuidInterface */
    private $id;

    /** @var OrderInterface */
    private $order;

    /** @var Email */
    private $authorEmail;

    /** @var string */
    private $message;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var AttachedFile */
    private $attachedFile;

    /** @var bool */
    private $notifyCustomer;

    public function __construct(OrderInterface $order, string $authorEmail, string $message, bool $notifyCustomer)
    {
        if (null == $message) {
            throw new \DomainException('OrderComment cannot be created with empty message');
        }

        $this->id = Uuid::uuid4();
        $this->authorEmail = Email::fromString($authorEmail);
        $this->order = $order;
        $this->message = $message;
        $this->notifyCustomer = $notifyCustomer;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function attachFile(string $path)
    {
        $this->attachedFile = AttachedFile::create($path);

        $this->record(
            FileAttached::occur($this->attachedFile->path())
        );
    }

    public function orderCommented(): void
    {
        $this->record(
            OrderCommented::occur(
                $this->getId(),
                $this->order(),
                $this->authorEmail(),
                $this->message(),
                $this->notifyCustomer(),
                $this->createdAt(),
                $this->attachedFile()
            )
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
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

    public function createdAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function attachedFile(): ?AttachedFile
    {
        return $this->attachedFile;
    }

    public function notifyCustomer(): bool
    {
        return $this->notifyCustomer;
    }
}
