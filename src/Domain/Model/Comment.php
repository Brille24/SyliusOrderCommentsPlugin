<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Domain\Model;

use Sylius\Component\Core\Model\OrderInterface;

final class Comment
{
    /** @var OrderInterface */
    private $order;

    /** @var Email */
    private $authorEmail;

    /** @var string */
    private $message;

    private function __construct(Email $authorEmail, OrderInterface $order, string $message)
    {
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

    public function order(): OrderInterface
    {
        return $this->order;
    }

    public function message(): string
    {
        return $this->message;
    }
}
