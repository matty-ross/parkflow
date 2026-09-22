<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('action', ChoiceType::class, [
                'label' => 'label.action',
                'choices' => [
                    'label.action_entry' => Event::ACTION_ENTRY,
                    'label.action_exit' => Event::ACTION_EXIT,
                ],
            ])
            ->add('licensePlate', TextType::class, [
                'label' => 'label.license_plate',
                'attr' => [
                    'maxlength' => 20,
                ],
            ])
            ->add('recognizedUser', UserAutocompleteField::class, [
                'label' => 'label.recognized_user',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
