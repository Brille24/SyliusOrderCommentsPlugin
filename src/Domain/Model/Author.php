<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Domain\Model;

use Sylius\Component\Core\Model\OrderInterface;

final class Author
{
    /** @var Email */
    private $email;

    /** @var Comment[] */
    private $orderComments = [];

    /**
     * @param Email $email
     */
    private function __construct(Email $email)
    {
        $this->email = $email;
    }

    public static function create(string $email): self
    {
        return new self(Email::fromString($email));
    }

    public function commentOrder(OrderInterface $order, string $message): void
    {
        $this->orderComments[] = Comment::create($this, $order, $message);
    }

    public function email(): Email
    {
        return $this->email;
    }

    /** @return Comment[] */
    public function orderComments(): array
    {
        return $this->orderComments;
    }
}
