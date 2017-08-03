<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Domain\Model;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\OrderCommentsPlugin\Domain\Event\OrderCommentedByAdministrator;
use Sylius\OrderCommentsPlugin\Domain\Event\OrderCommentedByCustomer;

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

    private function __construct(OrderInterface $order, string $authorEmail, string $message)
    {
        if (null == $message) {
            throw new \DomainException('OrderComment cannot be created with empty message');
        }

        $this->id = Uuid::uuid4();
        $this->authorEmail = Email::fromString($authorEmail);
        $this->order = $order;
        $this->message = $message;
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function orderByCustomer(OrderInterface $order, string $customerEmail, string $message): self
    {
        $comment = new self($order, $customerEmail, $message);

        $comment->record(
            OrderCommentedByCustomer::occur(
                $comment->id,
                $comment->order,
                $comment->authorEmail,
                $comment->message,
                $comment->createdAt
            )
        );

        return $comment;
    }

    public static function orderByAdministrator(OrderInterface $order, string $administratorEmail, string $message): self
    {
        $comment = new self($order, $administratorEmail, $message);

        $comment->record(
            OrderCommentedByAdministrator::occur(
                $comment->id,
                $comment->order,
                $comment->authorEmail,
                $comment->message,
                $comment->createdAt
            )
        );

        return $comment;
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
}
