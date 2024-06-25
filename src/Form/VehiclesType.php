<?php

namespace App\Form;

use App\Entity\Vehicles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiclesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => '',
                    'placeholder' => 'Marque',
                ]
            ])
            ->add('model', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => '',
                    'placeholder' => 'Modèle',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicles::class,
        ]);
    }
}
