<?php

namespace App\Form;

use App\Entity\Tricks;
use App\Entity\TricksImages;
use App\Entity\TricksVideos;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TricksCreateFormType extends AbstractType
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
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Description'
            ])
            ->add('category', NumberType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Catégorie'
            ])
            ->add('tricks_images', EntityType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Image(s)',
                'class' => TricksImages::class,
                'choice_label' => 'image',
                'mapped' => false
            ])
            ->add('tricks_videos', EntityType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Vidéo(s)',
                'class' => TricksVideos::class,
                'choice_label' => 'video',
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
            'cascade_validation' => true,
        ]);
    }
}
