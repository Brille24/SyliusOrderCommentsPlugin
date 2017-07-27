<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Context\Domain;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Ramsey\Uuid\Uuid;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\OrderCommentsPlugin\Domain\Event\OrderCommentedByCustomer;
use Sylius\OrderCommentsPlugin\Domain\Model\Author;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;
use Sylius\OrderCommentsPlugin\Domain\Model\Email;
use Webmozart\Assert\Assert;

final class OrderCommentsContext implements Context
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
     * @When I comment an order :order with :message
     */
    public function aCustomerCommentsAnOrderWith(OrderInterface $order, string $message): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');
        $this->sharedStorage->set('comment', Comment::orderByCustomer($order, $user->getEmail(), $message));
    }

    /**
     * @When I try to comment an order :order with an empty message
     */
    public function aCustomerTryToCommentsAnOrderWithEmptyMessage(OrderInterface $order): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');
        try {
            $this->sharedStorage->set('comment', Comment::orderByCustomer($order, $user->getEmail(), ''));
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
            $this->sharedStorage->set('comment', Comment::orderByCustomer($order, $email, 'Hello'));
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
            !in_array(
                OrderCommentedByCustomer::occur($comment->getId(), $order, Email::fromString($user->getEmail()), $message),
                $comment->recordedMessages()
            )
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
     * @Then this order should not have any comments
     */
    public function thisOrderShouldNotHaveAnyComments()
    {
        Assert::false($this->sharedStorage->has('comment'), 'At least one comment has been saved in shared storage, but none should');
    }
}
