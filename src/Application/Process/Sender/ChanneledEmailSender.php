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
    private SenderInterface $baseSender;

    private ChannelContextInterface $channelContext;

    private ChannelRepositoryInterface $channelRepository;

    public function __construct(
        SenderInterface $sender,
        ChannelRepositoryInterface $channelRepository,
        ChannelContextInterface $channelContext
    ) {
        $this->baseSender = $sender;
        $this->channelContext = $channelContext;
        $this->channelRepository = $channelRepository;
    }

    /** {@inheritdoc} */
    public function sendWithChannelTemplate(
        string $code,
        string $channelCode,
        array $recipients,
        array $data = [],
        array $attachments = [],
        array $replyTo = []
    ): void {
        $enrichedData = array_merge($data, ['channel' => $this->channelRepository->findOneByCode($channelCode)]);

        try {
            $this->baseSender->send("{$code}_{$channelCode}", $recipients, $enrichedData, $attachments, $replyTo);
        } catch (InvalidArgumentException $exception) {
            $this->baseSender->send($code, $recipients, $enrichedData, $attachments, $replyTo);
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
