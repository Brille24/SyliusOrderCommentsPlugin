<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Application\Process\Sender;

use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\Mime\Part\DataPart;

interface ChanneledEmailSenderInterface extends SenderInterface
{
    /**
     * @param string[]|null[] $recipients A list of email addresses to receive the message. Providing null or empty string in the list of recipients is deprecated and will be removed in SyliusMailerBundle 2.0
     * @param string[]|DataPart[] $attachments A list of file paths to attach to the message.
     * @param string[] $replyTo A list of email addresses to set as the Reply-To address for the message.
     * @param string[] $ccRecipients A list of email addresses set as carbon copy
     * @param string[] $bccRecipients A list of email addresses set as blind carbon copy
     */
    public function sendWithChannelTemplate(
        string $code,
        string $channelCode,
        array $recipients,
        array $data = [],
        array $attachments = [],
        array $replyTo = [],
        array $ccRecipients = [],
        array $bccRecipients = [],
    ): void;
}
