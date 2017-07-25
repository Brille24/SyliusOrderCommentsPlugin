<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Domain\Model;

use Sylius\Component\Core\Model\OrderInterface;

final class Comment
{
    /** @var OrderInterface */
    private $order;

    /** @var Author */
    private $author;

    /** @var string */
    private $message;

    private function __construct(Author $author, OrderInterface $order, string $message)
    {
        $this->author = $author;
        $this->order = $order;
        $this->message = $message;
    }

    public static function create(Author $author, OrderInterface $order, string $message): self
    {
        if (null == $message) {
            throw new \DomainException('Comment cannot be created with empty message');
        }

        return new self($author, $order, $message);
    }

    public function order(): OrderInterface
    {
        return $this->order;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function message(): string
    {
        return $this->message;
    }
}
