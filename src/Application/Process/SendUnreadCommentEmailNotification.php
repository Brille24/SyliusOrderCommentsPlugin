<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Application\Process;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Brille24\OrderCommentsPlugin\Application\Process\Sender\ChanneledEmailSenderInterface;
use Brille24\OrderCommentsPlugin\Domain\Event\OrderCommented;
use Brille24\OrderCommentsPlugin\Domain\Model\AttachedFile;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmozart\Assert\Assert;

final class SendUnreadCommentEmailNotification implements SendUnreadCommentEmailNotificationInterface, EventSubscriberInterface
{
    public function __construct(private ChanneledEmailSenderInterface $emailSender)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            OrderCommented::class => 'handleOrderCommented',
        ];
    }

    public function handleOrderCommented(OrderCommented $event): void
    {
        if (!$event->notifyCustomer()) {
            return;
        }

        $order = $event->order();
        $customer = $order->getCustomer();

        if (null === $customer) {
            return;
        }

        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        $recipients = [$channel->getContactEmail(), $customer->getEmail()];

        $this->sendUnreadCommentNotification(
            array_diff(array_filter($recipients), [$event->authorEmail()]),
            $order,
            $event->message(),
            (string) $event->authorEmail(),
            $event->attachedFile()
        );
    }

    /**
     * @param array<array-key, string> $recipients
     */
    private function sendUnreadCommentNotification(
        array $recipients,
        OrderInterface $order,
        string $message,
        string $authorEmail,
        ?AttachedFile $attachedFile
    ): void {
        $attachments = ($attachedFile === null) ? [] : [$attachedFile->path() ?? ''];

        $orderChannel = $order->getChannel();
        Assert::isInstanceOf($orderChannel, ChannelInterface::class);

        $orderChannelCode = $orderChannel->getCode();
        Assert::notNull($orderChannelCode);

        $this->emailSender->sendWithChannelTemplate(
            'unread_comment',
            $orderChannelCode,
            $recipients,
            [
                'order' => $order,
                'message' => $message,
                'authorEmail' => $authorEmail,
            ],
            $attachments
        );
    }
}
