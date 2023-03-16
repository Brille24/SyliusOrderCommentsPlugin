<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Infrastructure\Controller\Ui;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\User\Model\UserInterface;
use Brille24\OrderCommentsPlugin\Application\Command\CommentOrder;
use Brille24\OrderCommentsPlugin\Infrastructure\Form\Type\OrderCommentType;
use Brille24\OrderCommentsPlugin\Infrastructure\Form\DTO\OrderComment;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

final class OrderCommentAction
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private TokenStorageInterface $securityTokenStorage,
        private MessageBusInterface $commandBus,
        private OrderRepositoryInterface $orderRepository
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(OrderCommentType::class);
        $form->handleRequest($request);
        $token = $this->securityTokenStorage->getToken();

        /** @var string $referer */
        $referer = $request->headers->get('referer');
        if (null === $token || !$form->isValid()) {
            return new RedirectResponse($referer);
        }

        /** @var OrderComment $comment */
        $comment = $form->getData();

        /** @var UserInterface|string|null $user */
        $user = $token->getUser();
        /** @var OrderInterface $order */
        $order = $this->orderRepository->find($request->attributes->get('orderId'));

        Assert::isInstanceOf($user, UserInterface::class);

        $orderNumber = $order->getNumber();
        $email = $user->getEmail();
        Assert::string($orderNumber);
        Assert::string($email);

        $this->commandBus->dispatch(CommentOrder::create(
            $orderNumber,
            $email,
            $comment->message,
            $comment->notifyCustomer,
            $comment->file
        ));

        return new RedirectResponse($referer);
    }
}
