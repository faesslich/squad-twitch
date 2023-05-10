<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StreamerSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'slug1', type: TextType::class, options: [
                'label' => null,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Streamer',
                ],
            ])
            ->add(child: 'slug2', type: TextType::class, options: [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Streamer',
                ]
            ])
            ->add(child: 'slug3', type: TextType::class, options: [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Streamer',
                ]
            ])
            ->add(child: 'slug4', type: TextType::class, options: [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Streamer',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            defaults: []
        );
    }
}
