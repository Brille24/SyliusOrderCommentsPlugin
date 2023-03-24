<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Application\CommandHandler;

use Brille24\OrderCommentsPlugin\Domain\Event\FileAttached;
use Brille24\OrderCommentsPlugin\Domain\Event\OrderCommented;
use Doctrine\ORM\EntityManagerInterface;
use Gaufrette\FilesystemInterface;
use Ramsey\Uuid\Uuid;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Brille24\OrderCommentsPlugin\Application\Command\CommentOrder;
use Brille24\OrderCommentsPlugin\Domain\Model\Comment;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final class CommentOrderHandler
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private EntityManagerInterface $entityManager,
        private string $fileDir,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function __invoke(CommentOrder $command): void
    {
        /** @var OrderInterface|null $order */
        $order = $this->orderRepository->findOneBy(['number' => $command->orderNumber()]);

        if (null === $order) {
            throw new \DomainException(sprintf('Cannot comment an order "%s" because it does not exist', $command->orderNumber()));
        }

        $comment = new Comment($order, $command->authorEmail(), $command->message(), $command->notifyCustomer());

        $file = $command->file();
        if (null !== $file) {
            $extension = $file->guessExtension() ?? 'pdf';
            $newFileName = Uuid::uuid4()->toString().'.'.$extension;
            $file->move($this->fileDir, $newFileName);

            $filePath = $comment->attachFile($this->fileDir.'/'.$newFileName);
            $this->eventDispatcher->dispatch(FileAttached::occur($filePath));
        }

        $this->eventDispatcher->dispatch(OrderCommented::occur(
            $comment->getId(),
            $comment->order(),
            $comment->authorEmail(),
            $comment->message(),
            $comment->notifyCustomer(),
            $comment->createdAt(),
            $comment->attachedFile()
        ));

        $this->entityManager->persist($comment);
    }
}
