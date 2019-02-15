<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Context\Application;

use Behat\Behat\Context\Context;
use SimpleBus\Message\Bus\MessageBus;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Core\Test\Services\EmailCheckerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\OrderCommentsPlugin\Application\Command\CommentOrder;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;
use Webmozart\Assert\Assert;

final class AdministratorOrderCommentsContext implements Context
{
    /** @var MessageBus */
    private $commandBus;

    /** @var RepositoryInterface */
    private $orderCommentRepository;

    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var EmailCheckerInterface */
    private $emailChecker;

    public function __construct(
        MessageBus $commandBus,
        RepositoryInterface $orderCommentRepository,
        SharedStorageInterface $sharedStorage,
        EmailCheckerInterface $emailChecker
    ) {
        $this->commandBus = $commandBus;
        $this->orderCommentRepository = $orderCommentRepository;
        $this->sharedStorage = $sharedStorage;
        $this->emailChecker = $emailChecker;
    }

    /**
     * @Given I have commented the order :order with :message with the notify customer checkbox enabled
     * @When I comment the order :order with :message with the notify customer checkbox enabled
     */
    public function iCommentTheOrderWithMessageAndCheckboxEnabled(OrderInterface $order, string $message): void
    {
        /** @var AdminUserInterface $user */
        $user = $this->sharedStorage->get('administrator');

        $this->commandBus->handle(CommentOrder::create($order->getNumber(), $user->getEmail(), $message, true));
    }

    /**
     * @Given I have commented the order :order with :message with the notify customer checkbox disabled
     * @When I comment the order :order with :message with the notify customer checkbox disabled
     */
    public function iCommentTheOrderWithMessageAndCheckboxDisabled(OrderInterface $order, string $message): void
    {
        /** @var AdminUserInterface $user */
        $user = $this->sharedStorage->get('administrator');

        $this->commandBus->handle(CommentOrder::create($order->getNumber(), $user->getEmail(), $message, false));
    }

    /**
     * @Then /^(this order) should have a comment with "([^"]+)" from this administrator$/
     */
    public function thisOrderShouldHaveACommentWithFromThisAdministrator(OrderInterface $order, string $message): void
    {
        /** @var Comment $comment */
        $comment = $this->orderCommentRepository->findOneBy(['order' => $order]);

        /** @var AdminUserInterface $user */
        $user = $this->sharedStorage->get('administrator');

        Assert::notNull($comment, 'This order does not have any comments.');
        if (
            $comment->message() !== $message ||
            $comment->order() !== $order ||
            $comment->authorEmail() != $user->getEmail() ||
            !$comment->createdAt() instanceof \DateTimeInterface ||
            !empty($comment->recordedMessages())
        ) {
            throw new \RuntimeException(
                sprintf(
                    'There are no order comment with the "%s" message for the "%s" order from the "%s" administrator',
                    $message, $order->getNumber(), $user->getEmail()
            ));
        }
    }

    /**
     * @When I try to comment the order :order with an empty message
     */
    public function iTryToCommentTheOrderWithAnEmptyMessage(OrderInterface $order): void
    {
        try {
            $this->iCommentTheOrderWithMessageAndCheckboxEnabled($order, '');
        } catch (\DomainException $exception) {
            $this->sharedStorage->set('exception', $exception);
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
     * @Then /^(this order) should not have any comments$/
     * @Then the order :order should not have any comments
     */
    public function thisOrderShouldNotHaveAnyComments(OrderInterface $order): void
    {
        $comments = $this->orderCommentRepository->findBy(['order' => $order]);

        Assert::isEmpty($comments, sprintf('This order should not have any comment, but %d found', count($comments)));
    }

    /**
     * @Then the notification email should be sent to the customer about :message comment
     */
    public function theNotificationEmailShouldBeSentToTheCustomerAboutComment(string $message): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');
        Assert::true($this->emailChecker->hasMessageTo($message, $user->getEmail()));
    }

    /**
     * @Then the notification email should not be sent to the customer about :message comment
     */
    public function theNotificationEmailShouldNotBeSentToTheCustomerAboutComment(string $message): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');
        Assert::false($this->emailChecker->hasMessageTo($message, $user->getEmail()));
    }
}
