<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;

final class OrderCommentsContext implements Context
{
    /** @var MessageBus */
    private $commandBus;

    /** @var RepositoryInterface */
    private $orderCommentRepository;

    /** @var SharedStorageInterface */
    private $sharedStorage;

    public function __construct(
        MessageBus $commandBus,
        RepositoryInterface $orderCommentRepository,
        SharedStorageInterface $sharedStorage
    ) {
        $this->commandBus = $commandBus;
        $this->orderCommentRepository = $orderCommentRepository;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @When I comment an order :order with :message
     */
    public function aCustomerCommentsAnOrderWith(OrderInterface $order, string $message): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');

        $this->commandBus->handle(CommentOrderByCustomer::create($order->getNumber(), $user->getEmail(), $message));
    }

    /**
     * @When I try to comment an order :order with an empty message
     */
    public function aCustomerTryToCommentsAnOrderWithEmptyMessage(OrderInterface $order): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');
        try {
            $this->commandBus->handle(CommentOrderByCustomer::create($order->getNumber(), $user->getEmail(), ''));
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
            $this->commandBus->handle(CommentOrderByCustomer::create($order->getNumber(), $email, 'Hello'));
        } catch (\DomainException $exception) {
            $this->sharedStorage->set('exception', $exception);
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
            $this->commandBus->handle(CommentOrderByCustomer::create('#0003', $user->getEmail(), $message));
        } catch (\DomainException $exception) {
            $this->sharedStorage->set('exception', $exception);
        }
    }

    /**
     * @Then /^(this order) should have a comment with "([^"]+)" from this customer$/
     */
    public function thisOrderShouldHaveCommentWithFromThisCustomer(OrderInterface $order, string $message): void
    {
        /** @var Comment $comment */
        $comment = $this->orderCommentRepository->findAll()[0];

        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');

        if (
            $comment->message() !== $message ||
            $comment->order() !== $order ||
            $comment->authorEmail() != $user->getEmail() ||
            !empty($comment->recordedMessages())
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
     * @Then this order should not have empty comment from this customer
     */
    public function thisOrderShouldNotHaveEmptyCommentFromThisCustomer(): void
    {
        Assert::isInstanceOf($this->sharedStorage->get('exception'), \DomainException::class);
    }
}
