<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Domain\Event;

final class FileAttached
{
    /** @var string */
    private $filePath;

    private function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public static function occur(string $filePath): self
    {
        return new self($filePath);
    }

    public function filePath(): string
    {
        return $this->filePath;
    }
}
