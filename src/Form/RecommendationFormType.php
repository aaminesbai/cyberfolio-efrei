<?php

namespace App\Form;

use App\Entity\Recommendations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecommendationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom complet', 'type' => 'text'],
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
            ->add('position', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez le poste', 'type' => 'text'],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3, 
                        'max' => 100, 
                        'minMessage' => 'Votre poste doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre poste ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Téléchargez une image (jpeg, png)',
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
            ])
            ->add('comment', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Commentaires', 'type' => 'text', 'rows' => '3'],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3, 
                        'minMessage' => 'Votre commentaire doit comporter au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('linkedin', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'URL LinkedIn', 'type' => 'text'],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3, 
                        'max' => 100, 
                        'minMessage' => 'Votre URL LinkedIn doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre URL LinkedIn ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recommendations::class,
        ]);
    }
}
