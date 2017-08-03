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

    private function __construct(UuidInterface $id, Email $authorEmail, OrderInterface $order, string $message)
    {
        $this->id = $id;
        $this->authorEmail = $authorEmail;
        $this->order = $order;
        $this->message = $message;
    }

    public static function orderByCustomer(OrderInterface $order, string $customerEmail, string $message): self
    {
        if (null == $message) {
            throw new \DomainException('OrderComment cannot be created with empty message');
        }

        $id = Uuid::uuid4();
        $customerEmail = Email::fromString($customerEmail);
        $comment = new self($id, $customerEmail, $order, $message);

        $comment->record(OrderCommentedByCustomer::occur($id, $order, $customerEmail, $message));

        return $comment;
    }

    public static function orderByAdministrator(OrderInterface $order, string $administratorEmail, string $message): self
    {
        if (null == $message) {
            throw new \DomainException('OrderComment cannot be created with empty message');
        }

        $id = Uuid::uuid4();
        $administratorEmail = Email::fromString($administratorEmail);
        $comment = new self($id, $administratorEmail, $order, $message);

        $comment->record(OrderCommentedByAdministrator::occur($id, $order, $administratorEmail, $message));

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
}
