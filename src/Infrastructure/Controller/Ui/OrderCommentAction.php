<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Infrastructure\Controller\Ui;

use FOS\RestBundle\View\ViewHandlerInterface;
use SimpleBus\Message\Bus\MessageBus;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\OrderCommentsPlugin\Application\Command\CommentOrderByAdministrator;
use Sylius\OrderCommentsPlugin\Infrastructure\Form\Type\OrderCommentType;
use Sylius\OrderCommentsPlugin\Infrastructure\Model\OrderComment;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class OrderCommentAction
{
    /** @var ViewHandlerInterface */
    private $viewHandler;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var TokenStorageInterface */
    private $securityTokenStorage;

    /** @var MessageBus */
    private $commandBus;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    public function __construct(
        ViewHandlerInterface $viewHandler,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        TokenStorageInterface $securityTokenStorage,
        MessageBus $commandBus,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->viewHandler = $viewHandler;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->securityTokenStorage = $securityTokenStorage;
        $this->commandBus = $commandBus;
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(OrderCommentType::class);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            throw new BadRequestHttpException();
        }

        /** @var OrderComment $comment */
        $comment = $form->getData();
        /** @var ShopUserInterface $user */
        $user = $this->securityTokenStorage->getToken()->getUser();
        /** @var OrderInterface $order */
        $order = $this->orderRepository->find($request->attributes->get('orderId'));

        $this->commandBus->handle(CommentOrderByAdministrator::create(
            $order->getNumber(),
            $user->getEmail(),
            $comment->message
        ));

        return RedirectResponse::create($this->router->generate('sylius_admin_order_show', ['id' => $request->attributes->get('orderId')]));
    }
}
