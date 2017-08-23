<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Context\UI;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Shop\Account\Order\ShowPageInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Tests\Sylius\OrderCommentsPlugin\Behat\Element\OrderCommentsElementInterface;
use Tests\Sylius\OrderCommentsPlugin\Behat\Element\OrderCommentFormElementInterface;
use Webmozart\Assert\Assert;

final class CustomerOrderCommentsContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var ShowPageInterface */
    private $orderPage;

    /** @var OrderCommentsElementInterface */
    private $orderCommentsElement;

    /** @var OrderCommentFormElementInterface */
    private $orderCommentFormElement;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        ShowPageInterface $orderPage,
        OrderCommentsElementInterface $orderCommentsElement,
        OrderCommentFormElementInterface $orderCommentFormElement
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->orderPage = $orderPage;
        $this->orderCommentsElement = $orderCommentsElement;
        $this->orderCommentFormElement = $orderCommentFormElement;
    }

    /**
     * @When I comment the order :order with :message
     * @Given I have commented the order :order with :message
     */
    public function iCommentTheOrderWith(OrderInterface $order, string $message): void
    {
        $this->orderPage->open(['number' => $order->getNumber()]);

        $this->orderCommentFormElement->specifyMessage($message);
        $this->orderCommentFormElement->comment();
    }

    /**
     * @When I try to comment the order :order with an empty message
     */
    public function aCustomerTryToCommentsTheOrderWithEmptyMessage(OrderInterface $order): void
    {
        $this->orderPage->open(['number' => $order->getNumber()]);
        $this->orderCommentFormElement->specifyMessage('');
        $this->orderCommentFormElement->comment();
    }

    /**
     * @When I comment the order :order with :message and :fileName file
     */
    public function iCommentTheOrderWithMessageAndFile(Orderinterface $order, string $message, string $fileName): void
    {
        $filePath = __DIR__ . '/../../../Comments/Infrastructure/Form/Type/' . $fileName;

        $this->orderPage->open(['number' => $order->getNumber()]);

        $this->orderCommentFormElement->specifyMessage($message);
        $this->orderCommentFormElement->attachFile($filePath);
        $this->orderCommentFormElement->comment();
    }

    /**
     * @Then this order should have a comment with :message from this customer
     * @Then the first comment from the top should have the :message message
     */
    public function thisOrderShouldHaveACommentWithFromThisAdministrator(string $message): void
    {
        /** @var AdminUserInterface $user */
        $user = $this->sharedStorage->get('user');

        $comment = $this->orderCommentsElement->getFirstComment();

        Assert::notNull($comment);
        Assert::same($comment->find('css', '.text')->getText(), $message);
        Assert::same($comment->find('css', '.author')->getText(), $user->getEmail());
    }

    /**
     * @Then the order :order should not have any comments
     * @Then /^(this order) should not have any comments$/
     */
    public function theOrderShouldNotHaveAnyComments(OrderInterface $order): void
    {
        $this->orderPage->open(['number' => $order->getNumber()]);

        Assert::same($this->orderCommentsElement->countComments(), 0, 'This order should not have any comment, but %s found.');
    }

    /**
     * @Then I should be notified that comment is invalid
     */
    public function thisOrderShouldNotHaveEmptyCommentFromThisCustomer(): void
    {
        #TODO
    }

    /**
     * @Then this order should have a comment with :message and file :fileName from this customer
     */
    public function thisOrderShouldHaveACommentWithAndFileFromThisCustomer(string $message, string $fileName): void
    {
        /** @var AdminUserInterface $user */
        $user = $this->sharedStorage->get('user');

        $comment = $this->orderCommentsElement->getFirstComment();

        Assert::notNull($comment);
        Assert::same($comment->find('css', '.text')->getText(), $message);
        Assert::same($comment->find('css', '.author')->getText(), $user->getEmail());
        Assert::true($comment->find('css', '.file')->isValid());
    }
}
