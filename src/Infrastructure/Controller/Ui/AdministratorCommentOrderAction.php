<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Infrastructure\Controller\Ui;

use FOS\RestBundle\View\ViewHandlerInterface;
use SimpleBus\Message\Bus\MessageBus;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\OrderCommentsPlugin\Application\Command\CommentOrderByAdministrator;
use Sylius\OrderCommentsPlugin\Infrastructure\Form\DTO\OrderComment;
use Sylius\OrderCommentsPlugin\Infrastructure\Form\Type\OrderCommentType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

final class AdministratorCommentOrderAction
{
    /** @var ViewHandlerInterface */
    private $viewHandler;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var TokenStorageInterface */
    private $securityTokenStorage;

    /** @var MessageBus */
    private $commandBus;

    public function __construct(
        ViewHandlerInterface $viewHandler,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $securityTokenStorage,
        MessageBus $commandBus
    ) {
        $this->viewHandler = $viewHandler;
        $this->formFactory = $formFactory;
        $this->securityTokenStorage = $securityTokenStorage;
        $this->commandBus = $commandBus;
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
        $user = $this->securityTokenStorage->getToken()->getUser();
        Assert::notNull($user);
        Assert::isInstanceOf($user, AdminUserInterface::class);

        $this->commandBus->handle(CommentOrderByAdministrator::create(
            $request->get('orderNumber'),
            $user->getEmail(),
            $comment->message
        ));

        return RedirectResponse::create($request->headers->get('referer'));
    }
}
