<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Application\Process\Sender;

use Sylius\Component\Mailer\Sender\SenderInterface;

interface ChanneledEmailSenderInterface extends SenderInterface
{
    public function sendWithChannelTemplate(
        string $code,
        string $channelCode,
        array $recipients,
        array $data = [],
        array $attachments = [],
        array $replyTo = []
    ): void;
}
