<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Infrastructure\Controller\Ui;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sylius\OrderCommentsPlugin\Infrastructure\Form\Type\OrderCommentType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

final class RenderCommentOrderAction
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

    public function __invoke(string $orderNumber, string $submitPath): Response
    {
        $form = $this->formFactory->create(OrderCommentType::class);

        $view = View::create(['form' => $form->createView(), 'orderNumber' => $orderNumber, 'submitPath' => $submitPath]);
        $view->setTemplate('@SyliusOrderCommentsPlugin/_form.html.twig');

        return $this->viewHandler->handle($view);
    }
}
