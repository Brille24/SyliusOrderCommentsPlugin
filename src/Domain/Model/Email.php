<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Domain\Model;

final class Email
{
    private function __construct(private string $email)
    {
    }

    public static function fromString(string $email): self
    {
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \DomainException(sprintf('This email "%s" is not valid', $email));
        }

        return new self($email);
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
