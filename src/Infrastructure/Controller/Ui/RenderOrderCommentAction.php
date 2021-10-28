<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Infrastructure\Controller\Ui;

use Brille24\OrderCommentsPlugin\Infrastructure\Form\Type\OrderCommentType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class RenderOrderCommentAction
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var Environment */
    private $twig;

    public function __construct(FormFactoryInterface $formFactory, Environment $twig)
    {
        $this->formFactory = $formFactory;
        $this->twig = $twig;
    }

    public function __invoke(int $orderId, string $submitPath): Response
    {
        $form = $this->formFactory->create(OrderCommentType::class);

        return new Response($this->twig->render(
            '@Brille24SyliusOrderCommentsPlugin/_form.html.twig',
            ['form' => $form->createView(), 'orderId' => $orderId, 'submitPath' => $submitPath]
        ));
    }
}
