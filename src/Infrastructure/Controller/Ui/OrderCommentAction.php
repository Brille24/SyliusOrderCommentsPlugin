<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Infrastructure\Controller\Ui;

use FOS\RestBundle\View\ViewHandlerInterface;
use SimpleBus\Message\Bus\MessageBus;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\OrderCommentsPlugin\Application\Command\CommentOrder;
use Sylius\OrderCommentsPlugin\Infrastructure\Form\Type\OrderCommentType;
use Sylius\OrderCommentsPlugin\Infrastructure\Form\DTO\OrderComment;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

final class OrderCommentAction
{
    /** @var ViewHandlerInterface */
    private $viewHandler;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var TokenStorageInterface */
    private $securityTokenStorage;

    /** @var MessageBus */
    private $commandBus;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    public function __construct(
        ViewHandlerInterface $viewHandler,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $securityTokenStorage,
        MessageBus $commandBus,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->viewHandler = $viewHandler;
        $this->formFactory = $formFactory;
        $this->securityTokenStorage = $securityTokenStorage;
        $this->commandBus = $commandBus;

        $this->orderRepository = $orderRepository;
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(OrderCommentType::class);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return RedirectResponse::create($request->headers->get('referer'));
        }

        /** @var OrderComment $comment */
        $comment = $form->getData();
        $user = $this->securityTokenStorage->getToken()->getUser();
        /** @var OrderInterface $order */
        $order = $this->orderRepository->find($request->attributes->get('orderId'));

        Assert::notNull($user);

        $this->commandBus->handle(CommentOrder::create(
            $order->getNumber(),
            $user->getEmail(),
            $comment->message,
            $comment->file
        ));

        return RedirectResponse::create($request->headers->get('referer'));
    }
}
