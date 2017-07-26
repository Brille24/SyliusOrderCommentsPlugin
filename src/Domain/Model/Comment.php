<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Domain\Model;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class Comment implements ResourceInterface
{
    /** @var UuidInterface */
    private $id;

    /** @var OrderInterface */
    private $order;

    /** @var Email */
    private $authorEmail;

    /** @var string */
    private $message;

    private function __construct(Email $authorEmail, OrderInterface $order, string $message)
    {
        $this->id = Uuid::uuid4();
        $this->authorEmail = $authorEmail;
        $this->order = $order;
        $this->message = $message;
    }

    public static function create(string $authorEmail, OrderInterface $order, string $message): self
    {
        if (null == $message) {
            throw new \DomainException('Comment cannot be created with empty message');
        }

        return new self(Email::fromString($authorEmail), $order, $message);
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
}
