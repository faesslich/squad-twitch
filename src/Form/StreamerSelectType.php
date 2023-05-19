<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class StreamerSelectType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(eventName: FormEvents::PRE_SET_DATA, listener: function (FormEvent $event) use (
            $options
        ) {
            /** @var User $user */
            $user = $this->security->getUser();
            $form = $event->getForm();


            if ($user !== null) {
                if ($this->hasChoices($user)) {
                    $choices = $this->prepareChoices(delimiter: ' ', user: $user);
                    $form
                        ->add(
                            child: 'favorites',
                            type: ChoiceType::class,
                            options: [
                                'choices' => $choices,
                                'label' => 'label.favorites',
                                'multiple' => true,
                                'required' => false,
                            ]
                        )
                    ;
                }
            }

            $form
                ->add(child: 'slug1', type: TextType::class, options: [
                    'label' => null,
                    'required' => !($user && $this->hasChoices($user)),
                    'mapped' => true,
                    'attr' => [
                        'placeholder' => 'Streamer',
                    ],
                ])
                ->add(child: 'slug2', type: TextType::class, options: [
                    'required' => false,
                    'mapped' => true,
                    'attr' => [
                        'placeholder' => 'Streamer',
                    ]
                ])
                ->add(child: 'slug3', type: TextType::class, options: [
                    'required' => false,
                    'mapped' => true,
                    'attr' => [
                        'placeholder' => 'Streamer',
                    ]
                ])
                ->add(child: 'slug4', type: TextType::class, options: [
                    'required' => false,
                    'mapped' => true,
                    'attr' => [
                        'placeholder' => 'Streamer',
                    ]
                ])
            ;
        });
    }

    public function hasChoices(?User $user): bool
    {
        return $user->getStreamers() !== null;
    }

    function prepareChoices(string $delimiter, User $user): array
    {
        $choices = explode(separator: $delimiter, string: $user->getStreamers());
        return array_combine(keys: array_values($choices), values: array_values($choices));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            defaults: []
        );
    }
}
