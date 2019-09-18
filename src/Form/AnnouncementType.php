<?php

namespace App\Form;

use App\Entity\Announce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', TextType::class, [
                'label' => 'Addresse :',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])->add('zipcode', TextType::class, [
                'label' => 'Code Postal :',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])->add('city', TextType::class, [
                'label' => 'Ville :',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])->add('price', IntegerType::class, [
                'label' => 'Prix :',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])->add('description', TextType::class, [
                'label' => 'Description :',
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Announce::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'announcement_item',
        ]);
    }
}
