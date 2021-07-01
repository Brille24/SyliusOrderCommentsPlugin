<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Infrastructure\Controller\Ui;

use Sylius\OrderCommentsPlugin\Infrastructure\Form\Type\OrderCommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

final class RenderOrderCommentAction extends AbstractController
{
    /** @var FormFactoryInterface */
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function __invoke(int $orderId, string $submitPath): Response
    {
        $form = $this->formFactory->create(OrderCommentType::class);

        return $this->render(
            '@SyliusOrderCommentsPlugin/_form.html.twig',
            ['form' => $form->createView(), 'orderId' => $orderId, 'submitPath' => $submitPath]
        );
    }
}
