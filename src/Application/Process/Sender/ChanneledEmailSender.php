<?php

declare(strict_types=1);


namespace Brille24\OrderCommentsPlugin\Application\Process\Sender;

use InvalidArgumentException;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Webmozart\Assert\Assert;

class ChanneledEmailSender implements ChanneledEmailSenderInterface
{
    public function __construct(
        private SenderInterface $baseSender,
        private ChannelRepositoryInterface $channelRepository,
        private ChannelContextInterface $channelContext
    ) {
    }

    /**
     * {@inheritdoc}
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
    ): void {
        $enrichedData = array_merge($data, ['channel' => $this->channelRepository->findOneByCode($channelCode)]);

        try {
            /**
             * @psalm-suppress DeprecatedMethod
             * @psalm-suppress TooManyArguments
             */
            $this->baseSender->send("{$code}_{$channelCode}", $recipients, $enrichedData, $attachments, $replyTo, $ccRecipients, $bccRecipients);
        } catch (InvalidArgumentException) {
            /**
             * @psalm-suppress DeprecatedMethod
             * @psalm-suppress TooManyArguments
             */
            $this->baseSender->send($code, $recipients, $enrichedData, $attachments, $replyTo, $ccRecipients, $bccRecipients);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function send(
        string $code,
        array $recipients,
        array $data = [],
        array $attachments = [],
        array $replyTo = []
    ): void {
        $channelCode = $this->channelContext->getChannel()->getCode();
        Assert::notNull($channelCode);

        $this->sendWithChannelTemplate($code, $channelCode, $recipients, $data, $attachments, $replyTo);
    }
}
