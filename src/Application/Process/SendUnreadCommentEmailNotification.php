<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Application\Process;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\OrderCommentsPlugin\Domain\Event\OrderCommentedByAdministrator;
use Sylius\OrderCommentsPlugin\Domain\Event\OrderCommentedByCustomer;

final class SendUnreadCommentEmailNotification
{
    /** @var SenderInterface */
    private $emailSender;

    public function __construct(SenderInterface $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    public function handleOrderCommentedByAdministrator(OrderCommentedByAdministrator $event): void
    {
        $order = $event->order();

        $this->sendUnreadCommentNotification(
            [$order->getCustomer()->getEmail()],
            $order,
            $event->message(),
            (string) $event->administratorEmail()
        );
    }

    public function handleOrderCommentedByCustomer(OrderCommentedByCustomer $event): void
    {
        $order = $event->order();
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        $this->sendUnreadCommentNotification(
            [$channel->getContactEmail()],
            $order,
            $event->message(),
            (string) $event->customerEmail()
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
