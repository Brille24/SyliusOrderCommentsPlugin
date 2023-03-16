<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Domain\Model;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

class Comment implements ResourceInterface
{
    private UuidInterface $id;

    private Email $authorEmail;

    private \DateTimeInterface $createdAt;

    private ?AttachedFile $attachedFile = null;

    public function __construct(
        private OrderInterface $order,
        string $authorEmail,
        private string $message,
        private bool $notifyCustomer
    ) {
        if (null == $this->message) {
            throw new \DomainException('OrderComment cannot be created with empty message');
        }

        $this->id = Uuid::uuid4();
        $this->authorEmail = Email::fromString($authorEmail);
        $this->createdAt = new \DateTimeImmutable();
    }

    public function attachFile(string $path): string
    {
        $this->attachedFile = AttachedFile::create($path);

        $path = $this->attachedFile->path();
        Assert::string($path);

        return $path;
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
