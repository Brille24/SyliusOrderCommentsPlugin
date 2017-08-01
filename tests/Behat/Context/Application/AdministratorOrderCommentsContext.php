<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Context\Application;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use SimpleBus\Message\Bus\MessageBus;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\OrderCommentsPlugin\Application\Command\CommentOrderByAdministrator;
use Sylius\OrderCommentsPlugin\Domain\Event\OrderCommentedByAdministrator;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;
use Sylius\OrderCommentsPlugin\Domain\Model\Email;
use Webmozart\Assert\Assert;

final class AdministratorOrderCommentsContext implements Context
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
     * @When I comment the order :order with :message
     */
    public function iCommentTheOrderWith(OrderInterface $order, string $message): void
    {
        /** @var AdminUserInterface $user */
        $user = $this->sharedStorage->get('administrator');

        $this->commandBus->handle(CommentOrderByAdministrator::create($order->getNumber(), $user->getEmail(), $message));
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
        if ($comment->message() !== $message || $comment->authorEmail() != $user->getEmail())
        {
            throw new \InvalidArgumentException(
                sprintf(
                    'There are no order comment with the "%s" message for the "%s" order from the "%s" customer',
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
            $this->iCommentTheOrderWith($order, '');
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
     */
    public function thisOrderShouldNotHaveAnyComments(OrderInterface $order): void
    {
        $comments = $this->orderCommentRepository->findBy(['order' => $order]);

        Assert::isEmpty($comments, sprintf('This order should not have any comment, but %d found', count($comments)));
    }
}
