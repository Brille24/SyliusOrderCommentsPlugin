<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Application\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\OrderCommentsPlugin\Application\Command\CommentOrderByAdministrator;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;

final class CommentOrderByAdministratorHandler
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(OrderRepositoryInterface $orderRepository, EntityManagerInterface $entityManager)
    {
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(CommentOrderByAdministrator $command): void
    {
        /** @var OrderInterface $order */
        $order = $this->orderRepository->findOneBy(['number' => $command->orderNumber()]);

        if (null === $order) {
            throw new \DomainException(sprintf('Cannot comment an order "%s" because it does not exist', $command->orderNumber()));
        }

        $comment = Comment::orderByAdministrator($order, $command->administratorEmail(), $command->message());

        $this->entityManager->persist($comment);
    }
}
