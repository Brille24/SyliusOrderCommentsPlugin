<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Domain\Event;

use Ramsey\Uuid\UuidInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\OrderCommentsPlugin\Domain\Model\Email;

final class OrderCommentedByAdministrator
{
    /** @var UuidInterface */
    private $orderCommentId;

    /** @var OrderInterface */
    private $order;

    /** @var Email */
    private $administratorEmail;

    /** @var string */
    private $message;

    private function __construct(UuidInterface $orderCommentId, OrderInterface $order, Email $administratorEmail, string $message)
    {
        $this->orderCommentId = $orderCommentId;
        $this->order = $order;
        $this->administratorEmail = $administratorEmail;
        $this->message = $message;
    }

    public static function occur(UuidInterface $orderCommentId, OrderInterface $order, Email $administratorEmail, string $message): self
    {
        return new self($orderCommentId, $order, $administratorEmail, $message);
    }

    public function orderCommentId(): UuidInterface
    {
        return $this->orderCommentId;
    }

    public function order(): OrderInterface
    {
        return $this->order;
    }

    public function administratorEmail(): Email
    {
        return $this->administratorEmail;
    }

    public function message(): string
    {
        return $this->message;
    }
}
