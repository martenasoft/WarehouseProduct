<?php

namespace MartenaSoft\WarehouseProduct\Form;

use App\Entity\ProductStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Operation as AppOperation;

class ProductStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('safeMoneyOperation', ChoiceType::class, [
                'choices' => [
                    '' => null,
                    AppOperation::ADD_TO_SAFE_NAME => AppOperation::TYPE_ADD,
                    AppOperation::DEDUCT_FROM_SAFE_NAME => AppOperation::TYPE_DEDUCT,

                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductStatus::class,
        ]);
    }
}
