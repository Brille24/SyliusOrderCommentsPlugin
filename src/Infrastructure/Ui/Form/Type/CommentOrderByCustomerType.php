<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Infrastructure\Ui\Form\Type;

use Sylius\OrderCommentsPlugin\Application\Command\CommentOrderByCustomer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CommentOrderByCustomerType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('orderNumber', TextType::class, ['disabled' => true])
            ->add('customerEmail', EmailType::class, ['disabled' => true])
            ->add('message', TextareaType::class, [])
            ->setDataMapper($this)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentOrderByCustomer::class,
            'empty_data' => null,
        ]);
    }

    public function mapDataToForms($data, $forms)
    {
        $forms = iterator_to_array($forms);

        $forms['orderNumber']->setData($data ? $data->orderNumber() : '');
        $forms['customerEmail']->setData($data ? $data->customerEmail() : '');
        $forms['message']->setData($data ? $data->message() : '');
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        $data = CommentOrderByCustomer::create(
            $forms['orderNumber']->getData(),
            $forms['customerEmail']->getData(),
            $forms['message']->getData()
        );
    }
}
