<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Context\Domain;

use Behat\Behat\Context\Context;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\OrderCommentsPlugin\Domain\Model\Author;

final class OrderCommentsContext implements Context
{
    /** @var Author */
    private $author;

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
     * @When a customer comments an order :order with :message
     */
    public function aCustomerCommentsAnOrderWith(OrderInterface $order, string $message): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');
        $this->author = Author::create($user->getEmail());

        $this->author->commentOrder($order, $message);
    }

    /**
     * @Then /^(this order) should have comment with "([^"]+)" from this customer$/
     */
    public function thisOrderShouldHaveCommentWithFromThisCustomer(OrderInterface $order, string $message): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');

        foreach ($this->author->orderComments() as $orderComment) {
            if (
                $orderComment->message() === $message &&
                $orderComment->order() === $order &&
                $orderComment->author()->email() == $user->getEmail()
            ) {
                return;
            }
        }

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
