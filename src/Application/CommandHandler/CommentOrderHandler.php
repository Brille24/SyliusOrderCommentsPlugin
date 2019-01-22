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

    /** @var string */
    private $fileDir;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager,
        string $fileDir
    ) {
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
        $this->fileDir = $fileDir;
    }

    public function __invoke(CommentOrder $command): void
    {
        /** @var OrderInterface $order */
        $order = $this->orderRepository->findOneBy(['number' => $command->orderNumber()]);

        if (null === $order) {
            throw new \DomainException(sprintf('Cannot comment an order "%s" because it does not exist', $command->orderNumber()));
        }

        $comment = new Comment($order, $command->authorEmail(), $command->message(), $command->notifyCustomer());

        $file = $command->file();
        if (null !== $file) {
            $extension = $file->guessExtension() ?? 'pdf';

            $fileName = Uuid::uuid4()->toString() . '.' . $extension;
            $file->move($this->fileDir, $fileName);
            $comment->attachFile($this->fileDir . '/' . $fileName);
        }

        $this->entityManager->persist($comment);
    }
}
