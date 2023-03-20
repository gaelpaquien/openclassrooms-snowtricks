<?php

namespace App\Form;

use App\Entity\TrickVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickVideoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newVideo', TextType::class, [
                'label' => 'Vidéo',
                'help' => 'Lien de la vidéo (Youtube, Dailymotion, Vimeo)',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('oldVideo', HiddenType::class, [
                'mapped' => false,
                'attr' => [
                    'readonly' => true
                ],
            ])
            ->add('trickId', HiddenType::class, [
                'mapped' => false,
                'attr' => [
                    'readonly' => true
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrickVideo::class,
        ]);
    }
}
