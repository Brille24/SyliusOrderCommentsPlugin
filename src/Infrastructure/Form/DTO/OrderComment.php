<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Infrastructure\Form\DTO;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class OrderComment
{
    public string $message = '';

    public ?UploadedFile $file = null;

    public bool $notifyCustomer = false;
}
