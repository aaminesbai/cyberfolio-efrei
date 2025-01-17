<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'label' => 'Nom Complet',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre Nom Complet', 'type' => 'text'],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3, 
                        'max' => 100, 
                        'minMessage' => 'Votre nom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('username', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre Nom d\'Utilisateur', 'type' => 'text'],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3, 
                        'max' => 100, 
                        'minMessage' => 'Votre nom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'form-control', 'placeholder' => 'Nouveau Mot de Passe']],
                'required' => true,
                'first_options'  => ['label' => 'Nouveau Mot de Passe'],
                'second_options' => ['label' => 'Répétez le Nouveau Mot de Passe'],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
