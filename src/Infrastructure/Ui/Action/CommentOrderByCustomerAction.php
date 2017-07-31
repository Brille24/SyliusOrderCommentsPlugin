<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Infrastructure\Ui\Action;

use SimpleBus\Message\Bus\MessageBus;
use Sylius\Component\User\Model\UserInterface;
use Sylius\OrderCommentsPlugin\Application\Command\CommentOrderByCustomer;
use Sylius\OrderCommentsPlugin\Infrastructure\Ui\Form\Type\CommentOrderByCustomerType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class CommentOrderByCustomerAction
{
    /** @var MessageBus */
    private $commandBus;

    /** @var TokenStorageInterface */
    private $securityTokenStorage;

    /** @var FormFactoryInterface */
    private $formFactory;

    public function __construct(MessageBus $commandBus, TokenStorageInterface $securityTokenStorage, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->securityTokenStorage = $securityTokenStorage;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request): Response
    {
        /** @var UserInterface $user */
        $user = $this->securityTokenStorage->getToken()->getUser();
        $form = $this->formFactory->create(CommentOrderByCustomerType::class, CommentOrderByCustomer::create($request->get('orderNumber'), $user->getEmail(), ''));

        $this->commandBus->handle($form->getData());

        return RedirectResponse::create($request->headers->get('referer'));
    }
}
