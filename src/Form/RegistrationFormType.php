<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Mot de passe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au minimum {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('lastname',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom'
                ],
            ])
            ->add('firstname', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prénom'

                ],
            ])
            ->add('birthdayDate',DateType::class,[
                'attr' => [
                    'placeholder' => 'Date de naissance',
                    'class' => 'form-control',

                ],
                'widget' => 'single_text'
            ])
            ->add('address',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Adresse'
                ],
            ])
            ->add('zipcode',NumberType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Code Postal'

                ],
            ])
            ->add('city',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ville'

                ],
            ])
            ->add('phone',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Téléphone'

                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
