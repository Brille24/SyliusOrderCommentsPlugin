<?php

declare(strict_types=1);

namespace Brille24\OrderCommentsPlugin\Infrastructure\Form\Type;

use Brille24\OrderCommentsPlugin\Infrastructure\Form\DTO\OrderComment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class OrderCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'message',
                TextareaType::class,
                ['constraints' => [new NotBlank()]]
            )
            ->add(
                'notifyCustomer',
                CheckboxType::class,
                ['label' => 'sylius_order_comments.notify_customer']
            )
            ->add('file', FileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => OrderComment::class]);
    }
}
