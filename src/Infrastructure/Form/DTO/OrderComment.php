<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Infrastructure\Form\DTO;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class OrderComment
{
    /** @var string */
    public $message;

    /** @var UploadedFile */
    public $file;
}
