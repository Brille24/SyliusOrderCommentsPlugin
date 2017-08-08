<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Application\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Gaufrette\FilesystemInterface;
use Ramsey\Uuid\Uuid;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\OrderCommentsPlugin\Application\Command\CommentOrder;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;

final class CommentOrderHandler
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var FilesystemInterface */
    private $fileSystem;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager,
        FilesystemInterface $filesystem
    ) {
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(CommentOrder $command): void
    {
        /** @var OrderInterface $order */
        $order = $this->orderRepository->findOneBy(['number' => $command->orderNumber()]);

        if (null === $order) {
            throw new \DomainException(sprintf('Cannot comment an order "%s" because it does not exist', $command->orderNumber()));
        }

        $comment = new Comment($order, $command->authorEmail(), $command->message());

        if (null !== $command->file()) {
            $path = Uuid::uuid4()->toString() . '.' . $command->file()->getExtension();
            $this->fileSystem->write($path, file_get_contents($command->file()->getPathname()));
            $comment->attachFile($path);
        }

        $this->entityManager->persist($comment);
    }
}
