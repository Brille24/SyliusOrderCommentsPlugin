<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Domain\Model;

final class AttachedFile
{
    /** @var string */
    private $path;

    private function __construct(string $path)
    {
        if (null == $path) {
            throw new \DomainException('Uploaded file path cannot be empty.');
        }

        $this->path = $path;
    }

    public static function create(string $path): self
    {
        return new self($path);
    }

    public function path(): string
    {
        return $this->path;
    }
}
