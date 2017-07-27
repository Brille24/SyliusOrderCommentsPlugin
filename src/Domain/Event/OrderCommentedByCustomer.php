<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Domain\Event;

use Ramsey\Uuid\UuidInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\OrderCommentsPlugin\Domain\Model\Email;

final class OrderCommentedByCustomer
{
    /** @var UuidInterface */
    private $orderCommentId;

    /** @var OrderInterface */
    private $order;

    /** @var Email */
    private $customerEmail;

    /** @var string */
    private $message;

    private function __construct(UuidInterface $orderCommentId, OrderInterface $order, Email $customerEmail, string $message)
    {
        $this->orderCommentId = $orderCommentId;
        $this->order = $order;
        $this->customerEmail = $customerEmail;
        $this->message = $message;
    }

    public static function occur(UuidInterface $orderCommentId, OrderInterface $order, Email $customerEmail, string $message): self
    {
        return new self($orderCommentId, $order, $customerEmail, $message);
    }

    public function orderCommentId(): UuidInterface
    {
        return $this->orderCommentId;
    }

    public function order(): OrderInterface
    {
        return $this->order;
    }

    public function customerEmail(): Email
    {
        return $this->customerEmail;
    }

    public function message(): string
    {
        return $this->message;
    }
}
