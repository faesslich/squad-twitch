<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'currentPassword', type: PasswordType::class, options: [
                'constraints' => [
                    new UserPassword(),
                ],
                'label' => 'label.current_password',
                'attr' => [
                    'autocomplete' => 'off',
                ],
            ])
            ->add(child: 'newPassword', type: RepeatedType::class, options: [
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank(),
                    new Length(
                        min: 5,
                        max: 128,
                    ),
                ],
                'first_options' => [
                    'label' => 'label.new_password',
                ],
                'second_options' => [
                    'label' => 'label.new_password_confirm',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            defaults: [
                'allow_extra_fields' => true
            ]
        );
    }
}
