<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Infrastructure\Ui\Action;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sylius\OrderCommentsPlugin\Infrastructure\Ui\Form\Type\CommentOrderByCustomerType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

final class RenderCommentOrderByCustomerAction
{
    /** @var ViewHandlerInterface */
    private $viewHandler;

    /** @var FormFactoryInterface */
    private $formFactory;

    public function __construct(ViewHandlerInterface $viewHandler, FormFactoryInterface $formFactory)
    {
        $this->viewHandler = $viewHandler;
        $this->formFactory = $formFactory;
    }

    public function __invoke(): Response
    {
        $form = $this->formFactory->create(CommentOrderByCustomerType::class);

        $view = View::create(['form' => $form->createView()]);
        $view->setTemplate('@SyliusAdmin/order_comment.html.twig');

        return $this->viewHandler->handle($view);
    }
}
