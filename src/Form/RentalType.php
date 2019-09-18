<?php

namespace App\Form;

use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', ChoiceType::class, [
            'label' => 'Type :',
            'required' => true,
            'attr' => [
                'class' => 'form-control'
            ],
            'choices' => $this->getChoices()
        ])->add('brand', TextType::class, [
                'label' => 'Marque :',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])->add('km', IntegerType::class, [
                'label' => 'Kilometrage :',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])->add('matriculation', TextType::class, [
                'label' => 'Matriculation :',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])->add('year', DateType::class, [
                'label' => 'Année d\'immatriculation :',
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])->add('autonomy',IntegerType::class, [
                'label' => 'Autonomie :',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])->add('door', IntegerType::class, [
                'label' => 'Nombre de portes :',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])->add('place', IntegerType::class, [
                'label' => 'Nombre de place :',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;
    }

    public function getChoices() {
        $array = [];

        foreach (Vehicle::$types as $type) {
            $array[$type] = $type;
        }

        return $array;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'rental_item',
        ]);
    }
}
