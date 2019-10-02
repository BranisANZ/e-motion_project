<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname')
            ->add('firstname')
            ->add('birthdayDate',DateType::class,[
                'attr' => [
                    'placeholder' => 'Date de naissance',

                ],
                'widget' => 'single_text'
            ])
            ->add('address')
            ->add('zipcode')
            ->add('city')
            //->add('signUpDate')
            ->add('licenseDriving')
            //->add('photo')
            ->add('email')
            ->add('phone')
            //->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
