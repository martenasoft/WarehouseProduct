<?php

namespace MartenaSoft\WarehouseProduct\Form;

use MartenaSoft\WarehouseProduct\Entity\ProductStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use MartenaSoft\WarehouseReports\Entity\Operation;

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
                    Operation::ADD_TO_SAFE_NAME => Operation::TYPE_ADD,
                    Operation::DEDUCT_FROM_SAFE_NAME => Operation::TYPE_DEDUCT,

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
