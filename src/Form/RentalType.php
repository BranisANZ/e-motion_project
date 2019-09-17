<?php

namespace App\Form;

use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand', TextType::class, [
                'label' => 'Marque :',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])->add('km', IntegerType::class, [
                'label' => 'Kilometrage :',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])->add('matriculation', TextType::class, [
                'label' => 'Matriculation :',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])->add('year', TextType::class, [
                'label' => 'Année d\'immatriculation :',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])->add('autonomie',IntegerType::class, [
                'label' => 'Autonomie :',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])->add('door', IntegerType::class, [
                'label' => 'Nombre de portes :',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])->add('place', IntegerType::class, [
                'label' => 'Nombre de place :',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
