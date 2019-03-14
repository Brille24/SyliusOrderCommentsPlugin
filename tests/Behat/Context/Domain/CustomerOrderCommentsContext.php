<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Context\Domain;

use Behat\Behat\Context\Context;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;
use Webmozart\Assert\Assert;

final class CustomerOrderCommentsContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /**
     * @param SharedStorageInterface $sharedStorage
     */
    public function __construct(SharedStorageInterface $sharedStorage)
    {
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @When I comment the order :order with :message
     */
    public function aCustomerCommentsTheOrderWith(OrderInterface $order, string $message): void
    {
        $this->commentOrder($order, $message, true);
    }

    /**
     * @When I comment the order :order with :message and :fileName file
     */
    public function iCommentTheOrderWithMessageAndFile(Orderinterface $order, string $message, string $fileName): void
    {
        $this->commentOrder($order, $message, true, $fileName);
    }

    /**
     * @When I try to comment the order :order with an empty message
     */
    public function aCustomerTryToCommentsTheOrderWithEmptyMessage(OrderInterface $order): void
    {
        try {
            $this->commentOrder($order, '', true);
        } catch (\DomainException $exception) {
            $this->sharedStorage->set('exception', $exception);
        }
    }

    /**
     * @When a customer with email :email try to comment an order :order
     */
    public function aCustomerWithEmailTryToCommentAnOrder(string $email, OrderInterface $order): void
    {
        try {
            $this->commentOrder($order, 'Hello', true, null, $email);
        } catch (\DomainException $exception) {
            $this->sharedStorage->set('exception', $exception);
        }
    }

    /**
     * @Then /^(this order) should have a comment with "([^"]+)" from this customer$/
     */
    public function thisOrderShouldHaveCommentWithFromThisCustomer(OrderInterface $order, string $message): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');
        /** @var Comment $comment */
        $comment = $this->sharedStorage->get('comment');

        if (
            $comment->message() !== $message ||
            $comment->order() !== $order ||
            $comment->authorEmail() != $user->getEmail() ||
            !$comment->createdAt() instanceof \DateTimeInterface ||
            empty($comment->recordedMessages())
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
     * Creates a new comment and sets it into the shared storage.
     * @param OrderInterface $order
     * @param string $message
     * @param bool $notifyCustomer
     * @param string|null $fileName
     * @param string|null $email
     */
    private function commentOrder(OrderInterface $order, string $message, bool $notifyCustomer, string $fileName = null, string $email = null): void
    {
        if (null === $email) {
            /** @var ShopUserInterface $user */
            $user = $this->sharedStorage->get('user');
            $email = $user->getEmail();
        }

        $comment = new Comment($order, $email, $message, $notifyCustomer);
        $comment->orderCommented();

        if (null !== $fileName) {
            $comment->attachFile($fileName);
        }

        $this->sharedStorage->set('comment', $comment);
    }

    /**
     * @Then I should be notified that comment is invalid
     */
    public function thisOrderShouldNotHaveEmptyCommentFromThisCustomer(): void
    {
        Assert::isInstanceOf($this->sharedStorage->get('exception'), \DomainException::class);
    }

    /**
     * @Then this order should not have any comments
     */
    public function thisOrderShouldNotHaveAnyComments()
    {
        Assert::false($this->sharedStorage->has('comment'), 'At least one comment has been saved in shared storage, but none should');
    }

    /**
     * @Then /^(this order) should have a comment with "([^"]+)" and file "([^"]+)" from this customer$/
     */
    public function thisOrderShouldHaveACommentWithAndFileFromThisCustomer(Orderinterface $order, string $message, string $fileName): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');
        /** @var Comment $comment */
        $comment = $this->sharedStorage->get('comment');

        if (
            $comment->message() !== $message ||
            $comment->order() !== $order ||
            $comment->authorEmail() != $user->getEmail() ||
            !$comment->createdAt() instanceof \DateTimeInterface ||
            $comment->attachedFile()->path() != $fileName ||
            empty($comment->recordedMessages())
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
