<?php

namespace App\Form\Tricks;

use App\Entity\Tricks;
use App\Entity\TricksCategories;
use App\Entity\TricksImages;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TricksFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Titre'
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Description'
            ])
            ->add('category', EntityType::class, [
                'class' => TricksCategories::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control'
                ]
            ],
            [
                'label' => 'Catégorie'
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => TricksImagesFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'required' => false,
                'prototype' => true,
                'by_reference' => false,
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}
