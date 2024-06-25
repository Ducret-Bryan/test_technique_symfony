<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departureDate', DateType::class, [
                'label' => 'Date de départ',
                'widget' => 'single_text',
            ])
            ->add('returnDate', DateType::class, [
                'label' => 'Date de retour',
                'widget' => 'single_text',
            ])
            ->add('price', MoneyType::class, [
                'label' => false,
                'currency' => false,
                'attr' => [
                    'placeholder' => 'Prix (€/Jour)'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
