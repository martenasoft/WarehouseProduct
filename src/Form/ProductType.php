<?php

namespace MartenaSoft\WarehouseProduct\Form;

use MartenaSoft\Warehouse\Entity\Box;
use MartenaSoft\WarehouseProduct\Entity\Product;
use MartenaSoft\WarehouseProduct\Entity\ProductStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('articul', TextType::class, [
                'required' => false
            ])
            ->add('boughtPrice')
         //   ->add('recommendedPrice')
            ->add('soldPricePercent')
            ->add('status', EntityType::class, [
                'class' => ProductStatus::class,
                'choice_label' => 'name'
            ])
            ->add('length')
            ->add('description')
            ->add('box', EntityType::class, [
                'class' => Box::class,
                'choice_label' => 'name',
                'empty_data' => ''
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
