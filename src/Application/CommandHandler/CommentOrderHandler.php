<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Application\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Gaufrette\FilesystemInterface;
use Ramsey\Uuid\Uuid;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Brille24\OrderCommentsPlugin\Application\Command\CommentOrder;
use Brille24\OrderCommentsPlugin\Domain\Model\Comment;

final class CommentOrderHandler
{
    private OrderRepositoryInterface $orderRepository;

    private EntityManagerInterface $entityManager;

    private FilesystemInterface $fileSystem;

    private string $fileDir;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager,
        FilesystemInterface $fileSystem,
        string $fileDir
    ) {
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
        $this->fileSystem = $fileSystem;
        $this->fileDir = $fileDir;
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
            $path  = Uuid::uuid4()->toString() . '.' . $extension;

            /** @var string $content */
            $content = file_get_contents($file->getPathname());
            $this->fileSystem->write($path, $content);
            $comment->attachFile($this->fileDir . '/' . $path);
        }

        $comment->orderCommented();

        $this->entityManager->persist($comment);
    }
}
