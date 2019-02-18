<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Application\Process;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\OrderCommentsPlugin\Domain\Event\OrderCommented;

final class SendUnreadCommentEmailNotification
{
    /** @var SenderInterface */
    private $emailSender;

    public function __construct(SenderInterface $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    public function handleOrderCommented(OrderCommented $event): void
    {
        if (!$event->notifyCustomer()) {
            return;
        }

        $order = $event->order();
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        $recipients = [$channel->getContactEmail(), $order->getCustomer()->getEmail()];

        $this->sendUnreadCommentNotification(
            array_diff(array_filter($recipients), [$event->authorEmail()]),
            $order,
            $event->message(),
            (string) $event->authorEmail()
        );
    }

    private function sendUnreadCommentNotification(
        array $recipients,
        OrderInterface $order,
        string $message,
        string $authorEmail
    ): void {
        $this->emailSender->send(
            'unread_comment',
            $recipients,
            [
                'order' => $order,
                'message' => $message,
                'authorEmail' => $authorEmail,
            ]
        );
    }
}
