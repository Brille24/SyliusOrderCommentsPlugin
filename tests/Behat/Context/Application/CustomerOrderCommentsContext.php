<?php

declare(strict_types=1);

namespace Tests\Brille24\OrderCommentsPlugin\Behat\Context\Application;

use Behat\Behat\Context\Context;
use Sylius\Behat\Service\Checker\EmailCheckerInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Brille24\OrderCommentsPlugin\Application\Command\CommentOrder;
use Brille24\OrderCommentsPlugin\Domain\Model\Comment;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

final class CustomerOrderCommentsContext implements Context
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private RepositoryInterface $orderCommentRepository,
        private SharedStorageInterface $sharedStorage,
        private EmailCheckerInterface $emailChecker
    ) {
    }

    /**
     * @Given I have commented the order :order with :message
     * @When I comment the order :order with :message
     */
    public function aCustomerCommentsTheOrderWith(OrderInterface $order, string $message): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');

        $this->commandBus->dispatch(CommentOrder::create($order->getNumber(), $user->getEmail(), $message, true));
    }

    /**
     * @When I comment the order :order with :message and :fileName file
     */
    public function iCommentTheOrderWithMessageAndFile(Orderinterface $order, string $message, string $fileName): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');
        $originalFilePath = __DIR__ . '/../../../Comments/Infrastructure/Form/Type/' . $fileName;

        // Copy the file, because the handler will move it.
        $filePath = $originalFilePath.'.bkp';
        copy($originalFilePath, $filePath);

        $file = new UploadedFile($filePath, $filePath, null, null, true);

        $this->commandBus->dispatch(CommentOrder::create($order->getNumber(), $user->getEmail(), $message, true, $file));
    }

    /**
     * @When I try to comment the order :order with an empty message
     */
    public function aCustomerTryToCommentsTheOrderWithEmptyMessage(OrderInterface $order): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');
        try {
            $this->commandBus->dispatch(CommentOrder::create($order->getNumber(), $user->getEmail(), '', true));
        } catch (HandlerFailedException $exception) {
            $innerException = $exception->getPrevious();
            if (!($innerException instanceof \DomainException)) {
                throw $exception;
            }

            $this->sharedStorage->set('exception', $innerException);
        }
    }

    /**
     * @When a customer with email :email try to comment an order :order
     */
    public function aCustomerWithEmailTryToCommentAnOrder(string $email, OrderInterface $order): void
    {
        try {
            $this->commandBus->dispatch(CommentOrder::create($order->getNumber(), $email, 'Hello', true));
        } catch (HandlerFailedException $exception) {
            $innerException = $exception->getPrevious();
            if (!($innerException instanceof \DomainException)) {
                throw $exception;
            }

            $this->sharedStorage->set('exception', $innerException);
        }
    }

    /**
     * @When I try to comment a not existing order with :message
     */
    public function iTryToCommentAnNotExistingOrderWith(string $message): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');
        try {
            $this->commandBus->dispatch(CommentOrder::create('#0003', $user->getEmail(), $message, true));
        } catch (HandlerFailedException $exception) {
            $innerException = $exception->getPrevious();
            if (!($innerException instanceof \DomainException)) {
                throw $exception;
            }

            $this->sharedStorage->set('exception', $innerException);
        }
    }

    /**
     * @Then /^(this order) should have a comment with "([^"]+)" from this customer$/
     */
    public function thisOrderShouldHaveCommentWithFromThisCustomer(OrderInterface $order, string $message): void
    {
        /** @var Comment $comment */
        $comment = $this->orderCommentRepository->findOneBy(['order' => $order]);

        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');

        Assert::notNull($comment, 'This order does not have any comments.');
        if (
            $comment->message() !== $message ||
            $comment->order() !== $order ||
            $comment->authorEmail() != $user->getEmail() ||
            !$comment->createdAt() instanceof \DateTimeInterface
        ) {
            throw new \RuntimeException(
                sprintf(
                    'There are no order comment with this message "%s" for this order "%s" from this customer "%s"',
                    $message,
                    $order->getNumber(),
                    $user->getEmail()
                )
            );
        }
    }

    /**
     * @Then I should be notified that comment is invalid
     */
    public function thisOrderShouldNotHaveEmptyCommentFromThisCustomer(): void
    {
        Assert::isInstanceOf($this->sharedStorage->get('exception'), \DomainException::class);
    }

    /**
     * @Then /^(this order) should not have any comments$/
     * @Then the order :order should not have any comments
     */
    public function thisOrderShouldNotHaveAnyComments(OrderInterface $order): void
    {
        $comments = $this->orderCommentRepository->findBy(['order' => $order]);

        Assert::isEmpty($comments, sprintf('This order should not have any comment, but %d found', count($comments)));
    }

    /**
     * @Then the notification email should be sent to the administrator about :message comment
     */
    public function theNotificationEmailShouldBeSentToTheAdministratorAboutComment(string $message): void
    {
        /** @var ChannelInterface $channel */
        $channel = $this->sharedStorage->get('channel');
        Assert::true($this->emailChecker->hasMessageTo($message, $channel->getContactEmail()));
    }

    /**
     * @Then /^(this order) should have a comment with "([^"]+)" and file "([^"]+)" from this customer$/
     */
    public function thisOrderShouldHaveACommentWithAndFileFromThisCustomer(Orderinterface $order, string $message, string $fileName): void
    {
        /** @var Comment $comment */
        $comment = $this->orderCommentRepository->findOneBy(['order' => $order]);

        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');

        Assert::notNull($comment, 'This order does not have any comments.');
        if (
            $comment->message() !== $message ||
            $comment->order() !== $order ||
            $comment->authorEmail() != $user->getEmail() ||
            !$comment->createdAt() instanceof \DateTimeInterface
        ) {
            throw new \RuntimeException(
                sprintf(
                    'There are no order comment with this message "%s" for this order "%s" from this customer "%s"',
                    $message,
                    $order->getNumber(),
                    $user->getEmail()
                )
            );
        }
    }
}
