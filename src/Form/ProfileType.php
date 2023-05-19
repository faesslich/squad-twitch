<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                child: 'username',
                type: TextType::class,
                options: [
                    'label' => 'label.username',
                    'disabled' => true,
                ]
            )
            ->add(
                child: 'email',
                type: EmailType::class,
                options: [
                    'label' => 'label.email',
                ]
            )
            ->add(
                child: 'streamers',
                type: TextType::class,
                options: [
                    'required' => false,
                    'label' => 'Lieblings-Streamer (mit Leerzeichen trennen)',
                    'attr' => [
                        'autocomplete' => 'off',
                        'data-tagin' => 'true',
                        'data-tagin-separator' => ' ',
                        'data-tagin-placeholder' => 'Streamer hinzufügen...'
                    ],
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[a-zA-Z0-9,\s]+$/',
                            'message' => 'Es sind nur Zeichen von A bis Z und Zahlen erlaubt.',
                        ]),
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            defaults: [
                'data_class' => User::class,
            ]
        );
    }
}
