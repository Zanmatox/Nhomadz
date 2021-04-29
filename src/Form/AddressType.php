<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
->add('name', TextType::class, [
                'label' => 'How do you want to name your address ?',
                'attr' => [
                    'placeholder' => 'Name your adrress'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Your firstname',
                'attr' => [
                    'placeholder' => 'Enter your firstname'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Your lastname',
                'attr' => [
                    'placeholder' => 'Enter your lastname'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Your company (optional)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Enter your company name'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Your address',
                'attr' => [
                    'placeholder' => 'Enter your address'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Your postal number',
                'attr' => [
                    'placeholder' => 'Enter your postal number'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'attr' => [
                    'placeholder' => 'Enter your city'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Country',
                'attr' => [
                    'placeholder' => 'Enter your country'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Your phone number',
                'attr' => [
                    'placeholder' => 'Enter your phone number'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Validate',
                'attr' => [
                     'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
