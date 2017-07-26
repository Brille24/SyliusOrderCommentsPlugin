<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Application\CommandHandler;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\OrderCommentsPlugin\Application\Command\CommentOrderByCustomer;
use Sylius\OrderCommentsPlugin\Application\Repository\OrderCommentRepository;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;

final class CommentOrderByCustomerHandler
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var OrderCommentRepository */
    private $orderCommentRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, OrderCommentRepository $orderCommentRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->orderCommentRepository = $orderCommentRepository;
    }

    public function __invoke(CommentOrderByCustomer $command): void
    {
        /** @var OrderInterface $order */
        $order = $this->orderRepository->findOneBy(['number' => $command->orderNumber()]);

        if (null === $order) {
            throw new \DomainException(sprintf('Cannot comment an order "%s" because it does not exist', $command->orderNumber()));
        }

        $comment = Comment::create($command->customerEmail(), $order, $command->message());

        $this->orderCommentRepository->add($comment);
    }
}
