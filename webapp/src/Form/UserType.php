<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var bool */
        $edit = $options['edit'];
        
        $builder
            ->add('email', EmailType::class, [
                'label' => 'label.email',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'label.password',
                'required' => !$edit,
                'mapped' => false,
                'constraints' => $edit ? [] : [new Assert\NotBlank],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'label.first_name',
                'required' => false,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'label.last_name',
                'required' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'label.roles',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    User::ROLE_USER => User::ROLE_USER,
                    User::ROLE_ADMIN => User::ROLE_ADMIN,
                ],
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'label.is_active',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);

        $resolver->setRequired(['edit'])->setAllowedTypes('edit', ['bool']);
    }
}
