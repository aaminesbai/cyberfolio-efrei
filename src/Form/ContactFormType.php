<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'pointer-input', 'placeholder' => 'Votre nom', 'type' => 'text'],
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length([
                        'min' => 3, 
                        'max' => 100, 
                        'minMessage' => 'Votre nom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('email', TextType::class, [
                'attr' => ['class' => 'pointer-input', 'placeholder' => 'Votre email', 'type' => 'email'],
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Email(['message' => 'L\'email "{{ value }}" n\'est pas un email valide.']),
                ],
            ])
            ->add('subject', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'pointer-input', 'placeholder' => 'Sujet', 'type' => 'text'],
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['class' => 'pointer-input', 'placeholder' => 'Votre message', 'type' => 'text', 'rows' => '3'],
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length([
                        'min' => 3, 
                        'minMessage' => 'Votre message doit comporter au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
