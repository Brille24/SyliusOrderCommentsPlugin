<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Domain\Model;

final class AttachedFile
{
    private function __construct(private ?string $path)
    {
        if (null == $path) {
            throw new \DomainException('Uploaded file path cannot be empty.');
        }
    }

    public static function create(string $path): self
    {
        return new self($path);
    }

    public function path(): ?string
    {
        return $this->path;
    }
}
