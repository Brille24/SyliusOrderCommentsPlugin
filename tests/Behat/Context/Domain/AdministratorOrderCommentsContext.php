<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Context\Domain;

use Behat\Behat\Context\Context;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\OrderCommentsPlugin\Domain\Event\OrderCommentedByAdministrator;
use Sylius\OrderCommentsPlugin\Domain\Model\Author;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;
use Sylius\OrderCommentsPlugin\Domain\Model\Email;
use Webmozart\Assert\Assert;

final class AdministratorOrderCommentsContext implements Context
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
    public function iCommentTheOrderWith(OrderInterface $order, string $message): void
    {
        /** @var AdminUserInterface $user */
        $user = $this->sharedStorage->get('administrator');
        $this->sharedStorage->set('comment', Comment::orderByAdministrator($order, $user->getEmail(), $message));
    }

    /**
     * @When I try to comment the order :order with an empty message
     */
    public function iTryToCommentTheOrderWith(OrderInterface $order): void
    {
        try {
            $this->iCommentTheOrderWith($order, '');
        } catch (\DomainException $exception) {
            $this->sharedStorage->set('exception', $exception);
        }
    }

    /**
     * @Then /^(this order) should have a comment with "([^"]+)" from this administrator$/
     */
    public function thisOrderShouldHaveCommentWithFromThisAdministrator(OrderInterface $order, string $message): void
    {
        /** @var AdminUserInterface $user */
        $user = $this->sharedStorage->get('administrator');
        /** @var Comment $comment */
        $comment = $this->sharedStorage->get('comment');

        if (!in_array(
            OrderCommentedByAdministrator::occur($comment->getId(), $order, Email::fromString($user->getEmail()), $message),
            $comment->recordedMessages(),
            false
        )) {
            throw new \InvalidArgumentException(
            sprintf(
                'There are no order comment with the "%s" message for the "%s" order from the "%s" customer',
                $message, $order->getNumber(), $user->getEmail()
            ));
        }
    }

    /**
     * @Then I should be notified that comment is invalid
     */
    public function iShouldBeNotifiedThatCommentIsInvalid(): void
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
