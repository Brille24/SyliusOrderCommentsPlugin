<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Application\Process;

use Brille24\OrderCommentsPlugin\Domain\Event\OrderCommented;

interface SendUnreadCommentEmailNotificationInterface
{
    public function handleOrderCommented(OrderCommented $event): void;
}
