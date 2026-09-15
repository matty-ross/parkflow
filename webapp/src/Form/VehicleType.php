<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('owner', EntityType::class, [
                'label' => 'label.owner',
                'placeholder' => '',
                'class' => User::class,
            ])
            ->add('licensePlate', TextType::class, [
                'label' => 'label.license_plate',
                'attr' => [
                    'maxlength' => 20,
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'label.description',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
