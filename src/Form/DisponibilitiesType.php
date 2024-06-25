<?php

namespace App\Form;

use App\Entity\Disponibilities;
use App\Entity\Vehicles;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class DisponibilitiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departureDate', null, [
                'label' => 'Date de départ',
                'widget' => 'single_text',
            ])
            ->add('returnDate', null, [
                'label' => 'Date de retour',
                'widget' => 'single_text',
            ])
            ->add('price', MoneyType::class, [
                'label' => false,
                'currency' => false,
                'attr' => [
                    'placeholder' => 'Prix (€/Jour)'
                ]
            ])
            ->add('status', CheckboxType::class, [
                'label' => 'Disponible',
                'attr' => [
                    'class' => 'h-4'
                ]
            ])
            ->add('radio_vehicle', ChoiceType::class, [
                'mapped' => false,
                'label' => false,
                'expanded' => true,
                'choices'  => [
                    'existant' => true,
                    'nouveau' => false,
                ],
                'data' => '1'
            ])
            ->add('vehicle', EntityType::class, [
                'class' => Vehicles::class,
                'choice_label' => 'model',
                'label' => 'Liste des véhicules',
                'attr' => [
                    'class' => 'w-[50%]'
                ]
            ])
            ->add('vehicle_brand', TextType::class, [
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'class' => '',
                    'placeholder' => 'Marque',
                    'value' => 'Marque'
                ]
            ])
            ->add('vehicle_model', TextType::class, [
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'class' => '',
                    'placeholder' => 'Modèle',
                    'value' => 'Modèle'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disponibilities::class,
        ]);
    }
}