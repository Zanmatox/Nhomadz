<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Address;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Carrier;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
        ->add('addresses', EntityType::class, [
            'label' => false,
            'required' => true,
            'class' => Address::class,
            'choices' =>$user->getAddresses(),
            'multiple' => false,
            'expanded' => true
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
