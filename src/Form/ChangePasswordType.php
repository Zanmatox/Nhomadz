<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'My email address'
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true,
                'label' => 'My firstname'
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true,
                'label' => 'My lastname'
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'My password',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Password'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
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
                'label' => 'Update'
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
