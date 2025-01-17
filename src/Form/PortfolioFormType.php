<?php

namespace App\Form;

use App\Entity\Portfolio;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PortfolioFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom du projet', 'type' => 'text'],
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
            ->add('category', ChoiceType::class, [
                'label' => 'Entrez la catégorie du projet',
                'attr' => ['class' => 'form-control', 'type' => 'text'],
                'multiple' => false, // Permettre un seul choix, plutôt que multiple
                'choices' => [
                    'SQL' => 'SQL', 
                    'React' => 'React',   
                    'Symfony' => 'Symfony', 
                    'APIs' => 'APIs',
                ],
                'constraints' => [new NotBlank()],
            ])
            ->add('note', CKEditorType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Tapez vos notes...', 'type' => 'text', 'rows' => '3'],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3, 
                        'minMessage' => 'Votre note doit comporter au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('cat', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez soit cat1 cat2 cat3 ou cat4 avec un espace', 'type' => 'text'],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 4, 
                        'max' => 20, 
                        'minMessage' => 'Votre catégorie doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre catégorie ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('client', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez un nom de client si applicable', 'type' => 'text'],
                'constraints' => [
                    new Length([
                        'min' => 3, 
                        'max' => 100, 
                        'minMessage' => 'Le nom du client doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom du client ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Téléchargez une image (jpeg, png)',
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
            ])
            ->add('link', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Collez une URL de vidéo ici', 'type' => 'text'],
                'constraints' => [
                    new Length([
                        'min' => 3, 
                        'max' => 100, 
                        'minMessage' => 'Le lien doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le lien ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('site_url', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez l\'URL du projet', 'type' => 'text'],
                'constraints' => [
                    new Length([
                        'min' => 3, 
                        'max' => 100, 
                        'minMessage' => 'L\'URL doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'L\'URL ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Portfolio::class,
        ]);
    }
}
