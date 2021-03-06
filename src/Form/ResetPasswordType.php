<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('new_password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The passwords are not the same.',
            'label' => 'My new password',
            'required' => true,
            'first_options' => [ 
                'label' => 'Password',
                'attr' => [
                    'placeholder' => 'Please enter your new password.'
                ]
            ],
            'second_options' => [ 
                'label' => 'Confirm your new password',
                'attr' => [
                    'placeholder' => 'Please confirm your new password.'                       
                ]
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Update my password',
            'attr' => [
                'class' => "btn-block btn-info"
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
